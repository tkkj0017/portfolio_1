<?php

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/mypage.php
ファイル名 mypage.php
アクセスURL http://localhost/DT/portfolio_1/controller/mypage.php
*/

namespace portfolio_1;

require_once dirname(__FILE__) . './../Bootstrap.class.php';
use portfolio_1\Bootstrap;
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
$member = new Member($db);
$loginses = new LoginSession($db);


if(isset($_SESSION['mem_id'])===true && $_SESSION['mem_id']!==''){
  $mem_id = $_SESSION['mem_id'];

  $data = $member->getMemberDetail($mem_id);
  
  $dataArr = ($data !== "" && $data !== []) ? $data[0] : '';
  $dataArr['genre'] = explode('_', $dataArr['genre']);
  $context = [];
  $context['dataArr'] = $dataArr;
  $context['session'] = $_SESSION;
  $template = $twig->loadTemplate('mypage.html.twig');
  $template->display($context);
}else{
  echo '⚠️マイページを開けません';
  exit();
}