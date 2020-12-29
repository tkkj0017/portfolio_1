<?php

//商品検索のプログラム

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/item_search.php
ファイル名 item_search.php
アクセスURL http://localhost/DT/portfolio_1/controller/item_search.php
*/

namespace portfolio_1;

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use portfolio_1\Bootstrap;
use portfolio_1\lib\PDODatabase;
use portfolio_1\lib\LoginSession;
use portfolio_1\lib\Item;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$loginses = new LoginSession($db);
$itm = new Item($db);

//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

// エラ〜メッセージの定義(商品検索用)
$errArr = [];

$ctg_id = (isset($_GET['ctg_id']) === true && preg_match('/^[0-9]+$/', $_GET['ctg_id']) === 1) ? $_GET['ctg_id'] : '';

// カテゴリーリスト(一覧)を取得する
$cateArr = $itm->getCategoryList('item');

// 検索ワードのPOSTがあれば、商品の取得データを処理をする
if($_POST['item_search'] !== false){
  $res = $itm->searchItem($ctg_id , $_POST['search_word']);
  if($res !== false){
    $dataArr = $res;
    // $count = strval(count($res));

  }else{
    // 検索ワードがヒットしなかった場合
    $errArr = '検索結果は0件です';
    $dataArr = '';
  }
}else{
  $dataArr = $itm->getItemList($ctg_id);
}

$context = [];
$context['cateArr'] = $cateArr;
$context['dataArr'] = $dataArr;
// $context['count'] = $count;
$context['errArr'] = $errArr;
$context['session'] = $_SESSION;
$context['post'] = $_POST;

$template = $twig->loadTemplate('item_list.html.twig');
$template->display($context);