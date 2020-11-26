<?php

//商品詳細を表示するプログラム

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/item_detail.php
ファイル名 item_detail.php
アクセスURL http://localhost/DT/portfolio_1/controller/item_detail.php
*/

namespace portfolio_1;

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use portfolio_1\Bootstrap;
use portfolio_1\lib\PDODatabase;
// use portfolio_1\lib\Session;
use portfolio_1\lib\LoginSession;
use portfolio_1\lib\Item;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
// $ses = new Session($db);
$loginses = new LoginSession($db);
$itm = new Item($db);

//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

//セッションに、セッションIDを設定する
// $ses->checkSession();

//item_idを取得する //三項演算子
$item_id = (isset($_GET['item_id']) === true && preg_match('/^\d+$/', $_GET['item_id']) === 1) ? $_GET['item_id'] : '';

//item_idが取得できていない場合、商品一覧へ遷移させる
if($item_id === ''){
  header('Location: ' . Bootstrap::ENTRY_URL. 'item_list.php');
}

//カテゴリーリスト(一覧)を取得する
$cateArr = $itm->getCategoryList();

//商品情報を取得する
$itemData = $itm->getItemDetailData($item_id);

$context = [];
$context['cateArr'] = $cateArr;
$context['itemData'] = $itemData[0]; //なぜ0が必要か→ itemDataのidを読み込むため
// var_dump($itemData);
$context['session'] = $_SESSION;


$template = $twig->loadTemplate('item_detail.html.twig');
$template->display($context);