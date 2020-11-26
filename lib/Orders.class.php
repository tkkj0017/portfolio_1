<?php
// 売上データを扱うクラスファイル

/*
ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/lib/Order.class.php
ファイル名 Order.class.php
*/

namespace portfolio_1\lib;

class Orders{
  public $dataArr = [];
  public $db = null;

  public function __construct($db){
    $this->db = $db;
  }

  // 売上データを新たに追加する
  public function insertOrder(){

    $table = ' order ';
    $dataArr = [
      
    ];

  }

  // 売上データを消去する(注文を取り消す)
  public function deleteOrder(){

    $table = ' order ';
    $dataArr = [
      
    ];


  }

}