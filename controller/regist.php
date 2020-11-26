<?php

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/regist.php
ファイル名 regist.php
アクセスURL http://localhost/DT/portfolio_1/controller/regist.php
*/

namespace portfolio_1;

require_once dirname(__FILE__) . './../Bootstrap.class.php';
use portfolio_1\master\initMaster;
use portfolio_1\Bootstrap;

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);


// 初期データ指定

$dataArr = [
  'family_name' => '',
  'first_name' => '',
  'family_name_kana' => '',
  'first_name_kana' => '',
  'email' => '',
  'user_id' => '',
  'password' => '',
  'sex' => '',
  'year' => '',
  'month' => '',
  'day' => '',
  'zip1' => '',
  'zip2' => '',
  'address' => '',
  'tel1' => '',
  'tel2' => '',
  'tel3' => '',
  'traffic' => '',
  'contents' => ''
];

// エラ〜メッセージの定義

$errArr = [];
foreach($dataArr as $key => $value){
  $errArr[$key] = '';
}

list($yearArr, $monthArr, $dayArr) = initMaster::getDate();

$sexArr = initMaster::getSex();
$trafficArr = initmaster::getTrafficway();
$context = [];
$context['yearArr'] = $yearArr;
$context['monthArr'] = $monthArr;
$context['dayArr'] = $dayArr;
$context['sexArr'] = $sexArr;
$context['trafficArr'] = $trafficArr;
$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;

$template = $twig->loadTemplate('regist.html.twig');
$template->display($context);