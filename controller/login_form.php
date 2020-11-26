<?php

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/login_form.php
ファイル名 login_form.php
アクセスURL http://localhost/DT/portfolio_1/controller/login_form.php
*/

namespace portfolio_1;

require_once dirname(__FILE__) . './../Bootstrap.class.php';
use portfolio_1\lib\PDODatabase;
use portfolio_1\lib\Member;
use portfolio_1\lib\LoginSession;
use portfolio_1\Bootstrap;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$loginses = new LoginSession($db);

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

if($_SESSION){
  header('Location:' . Bootstrap::ENTRY_URL .'controller/top.php?mem_id=' . $_SESSION['mem_id']);
  exit();
}

// データ指定
$dataArr = [
  'email' => '',
  'password' => '',
];

$errArr = [];
foreach($dataArr as $key => $value){
  $errArr[$key] = '';
}

$context = [];
$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;
$context['session'] = $_SESSION;

$template = $twig->loadTemplate('login_form.html.twig');
$template->display($context);