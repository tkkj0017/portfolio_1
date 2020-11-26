<?php
// 一覧ページで選択されたデータの詳細を表示する

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/member_detail.php
ファイル名 member_detail.php
アクセスURL http://localhost/DT/portfolio_1/controller/member_detail.php
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

if(isset($_GET['mem_id'])===true && $_GET['mem_id']!==''){
  $mem_id = $_GET['mem_id'];

  $data = $member->getMemberDetail($mem_id);
  
  $dataArr = ($data !== "" && $data !== []) ? $data[0] : '';
  $dataArr['traffic'] = explode('_', $dataArr['traffic']);
  $context = [];
  $context['dataArr'] = $dataArr;
  $context['session'] = $_SESSION;
  $template = $twig->loadTemplate('member_detail.html.twig');
  $template->display($context);
}else{
  header('Location: ' . Bootstrap::ENTRY_URL . 'controller/member_list.php');
  exit();
}