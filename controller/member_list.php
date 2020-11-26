<?php
// 登録されたデータを一覧で表示する

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/member_list.php
ファイル名 member_list.php
アクセスURL http://localhost/DT/portfolio_1/controller/member_list.php
*/

namespace portfolio_1;
require_once dirname(__FILE__) . './../Bootstrap.class.php';
use portfolio_1\Bootstrap;
use portfolio_1\master\initMaster;
use portfolio_1\lib\PDODatabase;
use portfolio_1\lib\Common;
use portfolio_1\lib\Member;
use portfolio_1\lib\LoginSession;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$member = new Member($db);
$loginses = new LoginSession($db);


//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

$dataArr = $member->getMemberList($db);
$context = [];
$context['dataArr'] = $dataArr;
$context['session'] = $_SESSION;
$template = $twig->loadTemplate('member_list.html.twig');
$template->display($context);