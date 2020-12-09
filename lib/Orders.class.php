<?php
// 顧客側の購入データを扱うクラスファイル

/*
ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/lib/Purchases.class.php
ファイル名 Purchases.class.php
*/

namespace portfolio_1\lib;

class Orders{
  public $dataArr = [];
  public $db = null;

  public function __construct($db){
    $this->db = $db;
  }

  // 売上データを新たに追加する
  public function insertOrders($dataArr){
    $table = ' orders ';
    // 登録時刻の取得

    $dataArr['regist_date'] = date("Y-m-d H:i:s");
    $dataArr['delete_flg'] = '';
    $res = $this->db->insert($table, $dataArr);
    return ($res !== false) ? $res : false;
  }

  // 売上データを消去する(注文を取り消す)
  public function deleteOrders(){

    $table = ' order ';
    $dataArr = [
      
    ];


  }

}