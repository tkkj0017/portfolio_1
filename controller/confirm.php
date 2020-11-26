<?php

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/confirm.php
ファイル名 confirm.php
アクセスURL http://localhost/DT/portfolio_1/controller/confirm.php
*/

namespace portfolio_1;

require_once dirname(__FILE__) . './../Bootstrap.class.php';
use portfolio_1\lib\PDODatabase;
use portfolio_1\lib\Common;
use portfolio_1\lib\Member;
use portfolio_1\lib\LoginSession;
use portfolio_1\master\initMaster;

//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$common = new Common($db);
$member = new Member($db);
$logses = new LoginSession($db);


// モード判定(どの画面から来たかの判断)
// 登録画面から来た場合
if(isset($_POST['regist']) === true){
  $mode = 'regist';
}

// 編集画面から来た場合
if(isset($_POST['edit']) === true){
  $mode = 'edit';
}

// 戻る場合
if(isset($_POST['back']) === true){
  $mode = 'back';
}

// 登録完了
if(isset($_POST['complete']) === true){
  $mode = 'complete';
}

// 更新完了
if(isset($_POST['update']) === true){
  $mode = 'update';
}

switch($mode){
  case 'regist': //新規登録
    
    // ログインセッションは念のため解除しておく
    session_destroy();

    unset($_POST['regist']);
    $dataArr = $_POST;

    if(isset($_POST['sex']) === false){
      $dataArr['sex'] = "";    
    }
    if(isset($_POST['traffic']) === false){
      $dataArr['traffic'] = []; 
    }
    $errArr = $common->registErrorCheck($dataArr);
    $err_check = $common->getErrorFlg();
    $template = ($err_check === true)? 'confirm.html.twig' : 'regist.html.twig';
    break;

  case 'edit': //会員情報編集
    unset($_POST['edit']);
    $dataArr = $_POST;
    if(isset($_POST['sex']) === false){
      $dataArr['sex'] = "";    
    }
    if(isset($_POST['traffic']) === false){
      $dataArr['traffic'] = []; 
    }
    $errArr = $common->editErrorCheck($dataArr);
    $err_check = $common->getErrorFlg();
    $template = ($err_check === true)? 'confirm.html.twig' : 'member_edit.html.twig';
    break;

  case 'back':  //戻る
    $dataArr = $_POST;
    unset($dataArr['back']);
    foreach($dataArr as $key => $value){
      $errArr[$key] = '';
    }
    if($_SESSION['mem_id']){
      $template = 'member_edit.html.twig';
    }else{
      $template = 'regist.html.twig';
    }
    break;

  case 'complete':  //登録完了
    $dataArr = $_POST;
    unset($dataArr['complete']);
    $res = $member->insertMember($dataArr);
    if($res === true){
      header('Location:' . Bootstrap::ENTRY_URL .'controller/complete.php');
      exit();
    }else{
      $template = 'regist.html.twig';
      foreach($dataArr as $key => $value){
        $errArr[$key] = '';
      }
    }
    break;

  case 'update':  //更新完了
    $dataArr = $_POST;
    unset($dataArr['update']);
    $res = $member->updateMember($dataArr);
    if($res === true){
      header('Location:' . Bootstrap::ENTRY_URL .'controller/mypage.php?mem_id=' . $_SESSION['mem_id']);
      exit();
    }else{
      $template = 'member_edit.html.twig';
      foreach($dataArr as $key => $value){
        $errArr[$key] = '';
      }
    }
    break;
}

$sexArr = initMaster::getSex();
$trafficArr = initMaster::getTrafficWay();

$context['sexArr'] = $sexArr;
$context['trafficArr'] = $trafficArr;
list($yearArr, $monthArr, $dayArr) = initMaster::getDate();
$context['yearArr'] = $yearArr;
$context['monthArr'] = $monthArr;
$context['dayArr'] = $dayArr;
$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;
$context['session'] = $_SESSION;

$template = $twig->loadTemplate($template);
$template->display($context);