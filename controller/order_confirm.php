<?php

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/order_confirm.php
ファイル名 order_confirm.php
アクセスURL http://localhost/DT/portfolio_1/controller/order_confirm.php
*/

namespace portfolio_1;

require_once dirname(__FILE__) . './../Bootstrap.class.php';
use portfolio_1\Bootstrap;
use portfolio_1\lib\PDODatabase;
use portfolio_1\lib\Member;
use portfolio_1\lib\LoginSession;
use portfolio_1\lib\Cart;
use portfolio_1\lib\Item;
use portfolio_1\lib\Orders;

//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$member = new Member($db);
$logses = new LoginSession($db);
$cart = new Cart($db);
$item = new Item($db);
$order = new Orders($db);
// 購入確認画面の表示
if(isset($_POST['order_confirm']) === true){
  $mode = 'order_confirm'; 
}

// 戻る場合
if(isset($_POST['back']) === true){
  $mode = 'back';
}

// 購入を完了する
if(isset($_POST['order_complete']) === true){
  $mode = 'order_complete';
}

switch($mode){
  case 'order_confirm':  // 購入確認画面の表示
    unset($_POST['order_confirm']);

    // メンバーIDを取得
    $mem_id = $_SESSION['mem_id'];
    
    // カート情報取得
    $cartArr = $cart->getCartData($mem_id);

      // var_dump($dataArr);
      // var_dump($res);
    
    // 合計アイテム数と合計金額を取得
    list($sumNum, $sumPrice) = $cart->getSumPriceNum($mem_id);

    $template = 'order_confirm.html.twig';  
    break;

  case 'back':  // 戻る
    unset($_POST['back']);
    $template = 'cart.html.twig';
    break;

  case 'order_complete':  // 購入完了
    
    unset($_POST['order_complete']);
    
    $dataArr = [];
    // メンバーIDを取得
    $mem_id = $_SESSION['mem_id'];
    // カート情報取得
    $cartArr = $cart->getCartData($mem_id);
    // 購入者のE-mailを取得
    $mem = $member->getMemberDetail($mem_id);

    // var_dump($k);
    // 使用する文字列を配列に格納
    $order_key = '';
    $max_length = 15;
    $characters = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));

    for ($i=0; $i<$max_length; $i++) {
      $order_key .= $characters[mt_rand(0, count($characters)-1)];
    }

    // 購入履データ追加用の変数の用意
    $k = count($cartArr);
    $res[] = '';

    for($i = 0; $i < $k; $i++){
      $dataArr[$i]['order_key'] = $order_key;
      $dataArr[$i]['item_id'] = $cartArr[$i]['item_id'];
      $dataArr[$i]['num'] = $cartArr[$i]['num'];
      $dataArr[$i]['sub_total_price'] = $cartArr[$i]['sub_total_price'];
      $dataArr[$i]['mem_id'] = $_SESSION['mem_id'];
      $dataArr[$i]['email'] = $mem[0]['email'];
      $insRes = $order->insertOrders($dataArr[$i]);
      if($insRes !== true){
        break;
      }else{
        $delRes = $cart->delCartData($cartArr[$i]['crt_id']);
        $reduceStockRes = $item->reduceStockNum($cartArr[$i]['item_id'], $cartArr[$i]['num']);
      }
    }
    if($insRes === true && $delRes === true && $reduceStockRes){
      header('Location:' . Bootstrap::ENTRY_URL .'controller/order_complete.php?mem_id=' . $_SESSION['mem_id']);
      exit();
    }else{
      $template = 'order_confirm.html.twig';
      foreach($dataArr as $key => $value){
        $errArr[$key] = '購入に失敗しました';
      }
    }
    break;
}
$context = [];
$context['sumNum'] = $sumNum;
$context['sumPrice'] = $sumPrice;
$context['session'] = $_SESSION;
$context['cartArr'] = $cartArr;

$template = $twig->loadTemplate($template);
$template->display($context);