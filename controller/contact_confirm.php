<?php

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/contact.php
ファイル名 contact.php
アクセスURL http://localhost/DT/portfolio_1/controller/contact.php
*/

namespace portfolio_1;

require_once dirname(__FILE__) . './../Bootstrap.class.php';
use portfolio_1\Bootstrap;
use portfolio_1\lib\Member;
use portfolio_1\lib\Contact;
use portfolio_1\lib\LoginSession;
use portfolio_1\lib\PDODatabase;

// テンプレート指定
$loader = new \Twig_Loader_Filesystem
(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$member = new Member($db);
$contact = new Contact($db);
$logses = new LoginSession($db);

if(isset($_POST['contact']) === true){
  $mode = 'contact';
}

if(isset($_POST['back']) === true){
  $mode = 'back';
}

if(isset($_POST['complete'])){
  $mode = 'complete';
}

switch($mode){
  case 'contact':
    unset($_POST['contact']);
    $dataArr = $_POST;
    $err_check = '';
    $template = ($err_check === '') ? 'contact_confirm.html.twig' : 'contact_form.html.twig';
    break;
  
  case 'back':
    $dataArr = $_POST;
    unset($dataArr['back']);
    foreach($dataArr as $key => $value){
      $errArr[$key] = '';
    }
    $template = 'contact_form.html.twig';
    break;
  
  case 'complete':
    $dataArr = $_POST;
    unset($dataArr['complete']);
    $dataArr['mem_id'] = $_SESSION['mem_id'];
    $dataArr['user_id'] = $_SESSION['user_id'];
    $res = $member->getMemberDetail($_SESSION['mem_id']);
    $dataArr['email'] = $res[0]['email'];
    $res = $contact->insertContact($dataArr);

    if($res === true){

      // メールの言語と文字コードを指定
      mb_language("Japanese");
      mb_internal_encoding("UTF-8");
      
      // 完了のメールを投稿者本人のアドレスに送信
      $to = $dataArr['email'];
      $subject = 'お問い合わせの送信が完了しました。';
      $body = '以下の内容でお問い合わせが完了しました。'. $dataArr['subject'] . $dataArr['body'];
      $res1 = mb_send_mail($to, $subject, $body);

      // 投稿メールをサイト管理者のアドレスに送信
      $to = 'tttvvvaken@gmail.com';
      $subject = $_SESSION['user_id'] . '様からのお問い合わせです';
      $body = 'お問い合わせ内容は以下です。'. $dataArr['subject'] . $dataArr['body'];
      $res2 = mb_send_mail($to, $subject, $body);

      // 失敗したらエラーメッセージ
      if($res1 !== true || $res2 !== true){
        $template = 'contact_form.html.twig';
        foreach($dataArr as $key => $value){
          $errArr[$key] = 'メールの送信に失敗しました。';
        }
      }

      header('Location:' . Bootstrap::ENTRY_URL . 'controller/contact_complete.php?mem_id=' . $_SESSION['mem_id']);
      exit();
    }else{
      $template = 'contact_form.html.twig';
      foreach($dataArr as $key => $value){
        $errArr[$key] = '';
      }
    }
    break;
}

$errArr = [];

$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;
$context['session'] = $_SESSION;

$template = $twig->loadTemplate($template);
$template->display($context);