<?php

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/resign.php
ファイル名 resign.php
アクセスURL http://localhost/DT/portfolio_1/controller/resign.php
*/

namespace portfolio_1;

require_once dirname(__FILE__) . './../Bootstrap.class.php';
use portfolio_1\Bootstrap;
use portfolio_1\lib\PDODatabase;
use portfolio_1\lib\Member;
use portfolio_1\lib\LoginSession;

//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$member = new Member($db);
$logses = new LoginSession($db);

// マイページから来た場合
if(isset($_POST['resign_confirm']) === true){
  $template = 'resign_confirm.html.twig';
}

// 退会完了
if(isset($_POST['resign_complete']) === true){
  $res = $member->deleteMember($_SESSION['mem_id']);
  if($res === true){
    $logses->endSession();
    header('Location:' . Bootstrap::ENTRY_URL .'controller/resign_complete.php');
    exit();
  }else{
    echo '退会処理に失敗しました。もう一度マイページからやり直してください。';
  }
}

$context['session'] = $_SESSION;
var_dump($_SESSION);

$template = $twig->loadTemplate($template);
$template->display($context);