<?php

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/logout.php
ファイル名 logout.php
アクセスURL http://localhost/DT/portfolio_1/controller/logout.php
*/

namespace portfolio_1;

require_once dirname(__FILE__) . './../Bootstrap.class.php';
use portfolio_1\lib\PDODatabase;
use portfolio_1\lib\Common;
use portfolio_1\lib\Member;
use portfolio_1\lib\LoginSession;

//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$common = new Common($db);
$member = new Member($db);
// $ses = new Session($db);
$logses = new LoginSession($db);

$logses->endSession();
header('Location:' . Bootstrap::ENTRY_URL . '/controller/top.php');