<?php

// 投稿されたお問い合わせのデータを扱うクラスファイル

/*
ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/lib/Contact.class.php
ファイル名 Contact.class.php
*/

namespace portfolio_1\lib;

class Contact{
  public $dataArr = [];
  public $db = null;

  public function __construct($db){
    $this->db = $db;
  }

  public function insertContact($dataArr){
    $table = ' contact ';
    $dataArr['regist_date'] = date("Y-m-d H:i:s");
    $dataArr['delete_flg'] = '';
    $res = $this->db->insert($table, $dataArr);
    return ($res !== false) ? $res : false;
  }
}