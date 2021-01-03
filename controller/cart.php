<?php

//カートを表示するプログラム

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/cart.php
ファイル名 cart.php
アクセスURL http://localhost/DT/portfolio_1/controller/cart.php
*/

namespace portfolio_1;

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use portfolio_1\Bootstrap;
use portfolio_1\master\initMaster;
use portfolio_1\lib\PDODatabase;
// use portfolio_1\lib\Session;
use portfolio_1\lib\LoginSession;
use portfolio_1\lib\Cart;
use portfolio_1\lib\Like;
use portfolio_1\lib\Item;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
// $ses = new Session($db);
$logses = new LoginSession($db);
$cart = new Cart($db);
$like = new Like($db);
$item = new Item($db);

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

// 各変数の取得
$mem_id = $_SESSION['mem_id'];

$crt_id = (isset($_GET['crt_id']) === true && preg_match('/^\d+$/', $_GET['crt_id']) === 1) ? $_GET['crt_id'] : '';
$item_id = (!empty($_POST['item_id']) === true) ? $_POST['item_id'] : '';
$num = (!empty($_POST['num']) === true) ? $_POST['num'] : '';
$errArr = '';

// // 二重リロード対策
// // POSTされたトークンを取得
// $token = isset($_POST["token"]) ? $_POST["token"] : "";
// // セッション変数のトークンを取得
// $session_token = isset($_SESSION["token"]) ? $_SESSION["token"] : "";
// // セッション変数のトークンを削除
// unset($_SESSION["token"]);
// // POSTされたトークンとセッション変数のトークンを比較
// if(($token == "" && $token != $session_token)){
//   $errArr = 'ERROR: 不正な更新処理です。';
// }
// アイテム単品の価格を取得

// item_idが設定されていれば、ショッピングカートに登録する
if($item_id !== '' && $num !== ''){
  $price = $item->getItemPrice($item_id);
  // 小計を出すために数量と価格を数値化
  $num = intval($num);
  $price = intval($price[0]['price']);
  $res = $cart->insCartData($mem_id, $item_id, $num, $price);
  //登録に失敗した場合、エラーページを表示する
  if($res ===false){
    echo "商品購入に失敗しました。";
    exit();
  }
}

// 数量が変更された時にAjax通信を用いて、データベースを書き換える
if(isset($_POST['num-chenge']) === true){
  $num = $_POST['num-change'];
  $crt_id = $_POST['crt_id'];
  $cartData = $cart->getCartData($mem_id);
  $price = $cartData[$crt_id]['c.unit_price'];
  $res = $cart->numUpdate($crt_id,$num,$price);
  if ($res !== false) {
    echo $cart->getCartData($mem_id);
  }
}

// crt_idが設定されていれば、削除する
if($crt_id !== ''){
  $res = $cart->delCartData($crt_id);
}

// for($i = 0; $i < count($dataArr)-1 ; $i++){
//   // 各商品のいいね数を取得
//   $item_id = $dataArr[$i]['item_id'];
//   $dataArr['likeCnt'] = $like->countLike($item_id);
//   // 各商品をいいねしているか判別
// }

// カート情報を取得する
$dataArr = $cart->getCartData($mem_id);

// アイテム数と合計金額を取得する。listは配列をそれぞれの変数に分ける
// $cartSumAndNumData = $cart->getItemAndSumPrice($mem_id);

list($sumNum, $sumPrice) = $cart->getSumPriceNum($mem_id);
// アイテム毎の数量と合計金額を取得する
$cart->getItemNumPrice();

// カートの中身が無い時に0と表示できるようにする
if($sumNum === null){
  $sumNum = '0';
}

$numArr = initMaster::getItemNum();
$context = [];
$context['sumNum'] = $sumNum;
$context['sumPrice'] = $sumPrice;
$context['dataArr'] = $dataArr;
$context['numArr'] = $numArr;
// $context['errArr'] = $errArr;
$context['session'] = $_SESSION;

$template = $twig->loadTemplate('cart.html.twig');
$template->display($context);