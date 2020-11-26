<?php

// 郵便番号検索のクラスファイル

/*
ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/lib/Postcode_search.class.php
ファイル名 Postcode_search.class.php
*/

namespace portfolio_1\lib;

class Postcode_search{

  public $db = null;

  public function __construct($db){
    $this->db = $db;
  }

  // 郵便番号検索
  public function searchPostcode($zip1, $zip2){
    $table = ' postcode ';
    $col = ' pref, city, town ';
    $where = ($zip1 !== '' || $zip2 !== '') ? ' zip = ? ' : '';
    $arrVal = ($zip1 !== '' || $zip2 !== '') ? [$zip1 . $zip2] : '';
    $limit = ' 1 ';
    $this->db->setLimitOff($limit);
    $res = $this->db->select($table, $col, $where, $arrVal);
    return ($res !== false && count($res) !== 0) ? $res : '';
  }
}