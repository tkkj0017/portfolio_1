<?php

//トップページのプログラム

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/top.php
ファイル名 top.php
アクセスURL http://localhost/DT/portfolio_1/controller/top.php
*/

namespace portfolio_1;

require_once dirname(__FILE__) . './../Bootstrap.class.php';

use portfolio_1\Bootstrap;
use portfolio_1\lib\PDODatabase;
use portfolio_1\lib\LoginSession;
use portfolio_1\lib\News;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$loginses = new LoginSession($db);
$news = new News($db);

//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

//ニュースを取得する
$dataArr = $news->getNews();
// 登録日時のフォーマット修正
for ($i = 0; $i < count($dataArr); $i++) {
    $registDate = new \Datetime($dataArr[$i]['regist_date']);
    $dataArr[$i]['regist_date'] = $registDate->format('Y/m/d H時');
}

$context = [];
$context['dataArr'] = $dataArr;
$context['session'] = $_SESSION;
$context['post'] = $_POST;

$template = $twig->loadTemplate('top.html.twig');
$template->display($context);