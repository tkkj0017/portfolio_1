<?php

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/order_confirm.php
ファイル名 order_confirm.php
アクセスURL http://localhost/DT/portfolio_1/controller/order_confirm.php
*/

namespace portfolio_1;

require_once dirname(__FILE__) . './../Bootstrap.class.php';
use portfolio_1\lib\PDODatabase;
use portfolio_1\lib\Member;
use portfolio_1\lib\LoginSession;
use portfolio_1\lib\Cart;

//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$member = new Member($db);

$logses = new LoginSession($db);
$cart = new Cart($db);

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
    $dataArr = $_POST;
    var_dump($dataArr) . "¥n";
    var_dump($_POST) . "¥n";
    unset($dataArr['order_confirm']);
    $template = 'order_confirm.html.twig';
    

    break;


  case 'back':  // 戻る


    break;

  case 'order_complete':  // 購入完了
    
    
    break;
}


$context['dataArr'] = $dataArr;
$context['session'] = $_SESSION;

$template = $twig->loadTemplate($template);
$template->display($context);