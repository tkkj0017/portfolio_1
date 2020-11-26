<?php
// いいねボタンが押された時の処理を行うファイル

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/like.php
ファイル名 like.php
*/

namespace portfolio_1;

require_once dirname(__FILE__) . './../Bootstrap.class.php';
use portfolio_1\lib\PDODatabase;
use portfolio_1\lib\Like;
use portfolio_1\lib\LoginSession;
use portfolio_1\Bootstrap;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$like = new Like($db);
$logses = new LoginSession($db);

if(isset($_POST['itemId']) === true){
  $item_id = $_POST['itemId'];
  $mem_id = $_SESSION['mem_id'];
  
  $res = $like->getLike($item_id, $mem_id);

  if($res !== false){
    $res = $like->deleteLike($item_id, $mem_id);
    echo $like->countLike($item_id);
  }else{
    $res = $like->insertLike($item_id, $mem_id);
    echo $like->countLike($item_id); 
  }
}else{
  echo 'no';
}