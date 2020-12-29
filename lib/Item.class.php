<?php

// 商品に関するプログラムのクラスファイル

/*
ファイルパス /Applications/MAMP/htdocs/DT/shopping/lib/Item.class.php
ファイル名 Item.class.php
*/

namespace portfolio_1\lib;

class Item{
  public $cateArr = [];
  public $db = null;
  
  public function __construct($db){
    $this->db = $db;
  }

  // カテゴリーリストの取得
  public function getCategoryList($cate_genre){
    $table = ' category ';
    $col = ' ctg_id, category_name, cate_genre';
    $where = ($cate_genre !== '') ? ' cate_genre = ? ' : '';
    $arrVal = ($cate_genre !== '') ? [$cate_genre] : [];
    $res = $this->db->select($table, $col, $where, $arrVal);
    return ($res !== false && count($res) !== 0) ? $res : false;
  }

  // 商品リストを取得する
  public function getItemList($ctg_id){
    $table = ' item ';
    $col = ' item_id, item_name, price, image, ctg_id ';
    $where = ($ctg_id !== '') ? ' ctg_id = ? ' : '';
    $arrVal = ($ctg_id !== '') ? [$ctg_id] : [];
    $res = $this->db->select($table, $col, $where, $arrVal);
    return ($res !== false && count($res) !== 0) ? $res : false;
  }

  // 商品の詳細情報を取得する
  public function getItemDetailData($item_id){
    $table = ' item ';
    $col = ' item_id, item_name, detail, price, image, ctg_id';
    $where = ($item_id !== '') ? ' item_id = ? ' : '';
    // カテゴリーによって表示させるアイテムを変える
    $arrVal = ($item_id !== '') ? [$item_id] : [];
    $res = $this->db->select($table, $col, $where, $arrVal);
    return ($res !== false && count($res) !== 0) ? $res : false;
  }

  // 商品を検索して取得する
  public function searchItem($ctg_id = '', $search_word){
    $table = 'item';
    $col = '  item_id, item_name, price, image, ctg_id ';
    $where = ($search_word !== '') ? ' item_name LIKE ? ' : '';
    $arrVal = ($search_word !== '') ? ['%'.$search_word.'%'] : [];
    $res = $this->db->select($table, $col, $where, $arrVal);
    return ($res !== false && count($res) !== 0) ? $res : false;
  }

  // 商品の価格のみ取得する(カート用)
  public function getItemPrice($item_id){
    $table = ' item ';
    $col = ' price ';
    $where = ($item_id !== '') ? ' item_id = ? ' : '';
    // カテゴリーによって表示させるアイテムを変える
    $arrVal = ($item_id !== '') ? [$item_id] : [];
    $res = $this->db->select($table, $col, $where, $arrVal);
    return ($res !== false && count($res) !== 0) ? $res : false;
  }

  // 購入されたアイテムの在庫を数量分だけ減らす
  public function reduceStockNum($item_id, $num){
    $table = ' item ';
    // 指定の商品の在庫数の取得
    $col = ' stock_num ';
    $where = ($item_id !== '') ? ' item_id = ? ' : '';
    // カテゴリーによって表示させるアイテムを変える
    $arrVal = ($item_id !== '') ? [$item_id] : [];
    $res = $this->db->select($table, $col, $where, $arrVal);
    var_dump($res);
    if ($res !== false) {
        // 在庫の数を減らす処理
        // 在庫数の型を数値に変える
        $stock_num = (int)$res[0]['stock_num'];
        $dataArr = ['stock_num' => $stock_num - $num];
        $where = ($item_id !== '') ? ' item_id = ? ' : '';
        $arrWhereVal = [$item_id];
        $res = $this->db->update($table, $dataArr, $where, $arrWhereVal);
        return ($res !== false && count($res) !== 0) ? $res : false;
    }
  }
}