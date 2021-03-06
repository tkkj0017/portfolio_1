<?php

//トップページのプログラム

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/item_list.php
ファイル名 top.php
アクセスURL http://localhost/DT/portfolio_1/controller/item_list.php
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

$ctg_id = (isset($_GET['ctg_id']) === true && preg_match('/^[0-9]+$/', $_GET['ctg_id']) === 1) ? $_GET['ctg_id'] : '';

// エラ〜メッセージの定義(商品検索用)
$errArr = [];

// カテゴリーリスト(一覧)を取得する
$cateArr = $itm->getCategoryList('item');
//商品リストを取得する
$dataArr = $itm->getItemList($ctg_id);


$context = [];
$context['cateArr'] = $cateArr;
$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;
$context['session'] = $_SESSION;
$context['post'] = $_POST;

$template = $twig->loadTemplate('item_list.html.twig');
$template->display($context);