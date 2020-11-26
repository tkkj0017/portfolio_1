<?php
// 郵便番号を基に、DBから住所を取得するプログラムファイル

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/postcode_search.php
ファイル名 postcode_search.php
*/

namespace portfolio_1;

require_once dirname(__FILE__) . './../Bootstrap.class.php';
use portfolio_1\lib\PDODatabase;
use portfolio_1\lib\Postcode_search;
use portfolio_1\Bootstrap;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$ps = new Postcode_search($db);

if(isset($_GET['zip1']) === true && isset($_GET['zip2']) === true){
  $zip1 = $_GET['zip1'];
  $zip2 = $_GET['zip2'];

  $res = $ps->searchPostcode($zip1, $zip2);

  echo ($res !== "" && count($res) !== 0) ? $res[0]['pref'] . $res[0]['city'] .$res[0]['town'] : '';
}else{
  echo "no";
}