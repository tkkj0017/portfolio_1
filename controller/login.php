<?php

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/login.php
ファイル名 login.php
アクセスURL http://localhost/DT/portfolio_1/controller/login.php
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
$logses= new LoginSession($db);

if(isset($_POST['login']) === true){
  unset($_POST['login']);
  $dataArr = $_POST;
  
  if(isset($_POST['email']) === false){
    $dataArr['email'] = "";    
  }
  if(isset($_POST['password']) === false){
    $dataArr['password'] = ''; 
  }
  
  // 入力が空でないかチェック
  $errArr = $common->loginErrorCheck($dataArr);
  $err_check = $common->getErrorFlg();
  
  if($err_check === true){
    $res = $member->login($dataArr['email']);
    // SQLエラー無し & パスワードハッシュ化成功 & delete_flgが立っていない
    // if文の判定はlogin関数内で行いましょう
    if($res && password_verify($dataArr['password'], $res[0]['password']) && $res[0]['delete_flg'] === '0'){ 
      $dataArr = $res[0];
      // $ses->checkSession();
      $logses->checkSession($dataArr['mem_id'], $dataArr['user_id']);
      header('Location:' . Bootstrap::ENTRY_URL . '/controller/top.php?mem_id=' . $dataArr['mem_id']);
      exit();
     }else{
      $template = 'login_form.html.twig';
      $errArr['auth'] = 'メールアドレスもしくはパスワードが間違っています';
     }
  }else{
    $template = 'login_form.html.twig';
  }
}

$context = [];
$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;
$context['session'] = $_SESSION;

$template = $twig->loadTemplate($template);
$template->display($context);