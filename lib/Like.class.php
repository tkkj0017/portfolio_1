<?php

// いいねが押された時にDBを操作するクラスファイル

/*
ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/lib/Postcode_search.class.php
ファイル名 Postcode_search.class.php
*/

namespace portfolio_1\lib;

class Like{
  public $db = null;

  public function __construct($db){
    $this->db = $db;
  }

  public function getLike($item_id, $mem_id){
    $table = ' likes ';
    $col = ' * ';
    $where = ' item_id = ? AND mem_id = ? ';
    $arrVal = [$item_id, $mem_id];
    $res = $this->db->select($table, $col, $where, $arrVal);
    return ($res !== false && count($res) !== 0) ? $res : false;
  }

  public function deleteLike($item_id, $mem_id){
    $table = ' likes ';
    $where = ' item_id = ? AND mem_id = ? ';
    $arrVal = [$item_id, $mem_id];
    return $this->db->delete($table, $where, $arrVal);
  }
  
  public function insertLike($item_id, $mem_id){
    $table = ' likes ';
    $insData = [
      'item_id' => $item_id,
      'mem_id' => $mem_id,
      'regist_date' => date("Y-m-d H:i:s")
    ];
    return $this->db->insert($table, $insData);
  }

  public function countLike($item_id){
    $table = ' likes ';
    $where = ' item_id = ? ';
    $arrVal = [$item_id];
    return $this->db->count($table, $where, $arrVal);
  }
}