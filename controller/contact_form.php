<?php

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/contact_form.php
ファイル名 contact_form.php
アクセスURL http://localhost/DT/portfolio_1/controller/contact_form.php
*/

namespace portfolio_1;

require_once dirname(__FILE__) . './../Bootstrap.class.php';
use portfolio_1\Bootstrap;
use portfolio_1\lib\LoginSession;
use portfolio_1\lib\PDODatabase;

// テンプレート指定
$loader = new \Twig_Loader_Filesystem
(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$logses = new LoginSession($db);

// 初期データ
$dataArr = [
  'subject' => '',
  'body' => ''
];

// エラーメッセージの定義
$errArr = [];
foreach($dataArr as $key => $value){
  $errArr[$key] = '';
}

$context = [];

$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;
$context['session'] = $_SESSION;

$template = $twig->loadTemplate('contact_form.html.twig');
$template->display($context);