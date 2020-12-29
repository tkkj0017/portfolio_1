<?php

// カートに関するプログラムのクラスファイル

/*
ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/lib/Cart.class.php
ファイル名 Cart.class.php
*/

namespace portfolio_1\lib;

class Cart{
  private $db = null;

  public function __construct($db = null){
    $this->db = $db;
  }

  //カートに登録する(誰が($customer_no)、何を($item_id)、何個(num)、いつ(regist_date))

  public function insCartData($mem_id, $item_id, $num, $price){
    $sub_total_price = $num * $price;
    $table = ' cart ';
    $insData = [
      'mem_id' => $mem_id,
      'item_id' => $item_id, 
      'num' => $num,
      'unit_price' => $price,
      'sub_total_price' => $sub_total_price,
      'regist_date' => date("Y-m-d H:i:s")
    ];
    return $this->db->insert($table, $insData);
  }

  
  //カートの情報を取得する(必要な情報は、誰が($customer_no)。必要な商品情報は名前、商品画像、金額)
  public function getCartData($mem_id){
    // SELECT
    // c.crt_id,
    // i.item_id,
    // i.item_name,
    // i.price,
    // i.image';
    // FROM
    // cart c
    // LEFT JOIN
    // item i
    // ON
    // c.item_id = i.item_id';
    // WHERE
    // c.customer_no = ? AND c.delete_flg = ? ';
    $table = ' cart c LEFT JOIN item i ON c.item_id = i.item_id ';
    $column = ' c.crt_id, c.num, c.unit_price, c.sub_total_price, i.item_id, i.item_name, i.price, i.image';
    // $where = ' c.mem_id = ? AND c.delete_flg = ? GROUP BY i.item_id';
    $where = ' c.mem_id = ? AND c.delete_flg = ? ';
    $arrVal = [$mem_id, 0];
    
    return $this->db->select($table, $column, $where, $arrVal);
  }
  
  //カート情報を削除する(必要な情報はどのカート($crt_id)を消すか)
  public function delCartData($crt_id){
    $table = ' cart ';
    $delData = ['delete_date' => date("Y-m-d H:i:s") ,'delete_flg' => 1];
    $where = ' crt_id = ? ';
    $arrWhereVal = [$crt_id];
    
    return $this->db->update($table, $delData, $where, $arrWhereVal);
  }
  
  //合計金額と合計アイテム数を取得する
  public function getSumPriceNum($mem_id){
    // 合計金額
    // SELECT
    // SUM(i.price) AS totalPrice ";
    // FROM
    // cart c
    // LEFT JOIN
    // item i
    // ON
    // c.item_id = i.item_id"
    // WHERE
    // c.customer_no = ? AND c.delete_flg =?';
    
    // 合計金額取得
    $table = " cart c LEFT JOIN item i ON c.item_id = i.item_id ";
    $column = " SUM(c.sub_total_price) AS totalPrice ";
    $where = ' c.mem_id = ? AND c.delete_flg = ?';
    $arrWhereVal = [$mem_id, 0];
    
    $res = $this->db->select($table, $column, $where, $arrWhereVal);
    $price = ($res !== false && count($res) !== 0) ? $res[0]['totalPrice'] : 0;
    
    // アイテム数取得
    $table = ' cart c ';
    $column = ' SUM(num) AS num ';
    $res = $this->db->select($table, $column, $where, $arrWhereVal);
    
    $num = ($res !== false && count($res) !== 0) ? $res[0]['num'] : 0;
    return [$num, $price];
  }
  
  // 商品ごとの合計金額とアイテム数を取得する
  public function getItemNumPrice(){
    $table = " cart c LEFT JOIN item i ON c.item_id = i.item_id ";
    $column = " SUM(i.price) AS totalPrice ";
    $where = ' c.mem_id = ? AND c.delete_flg = ?';
    
  }
  
  // 数量変更があった時にデータを書き換える
  public function numUpdate($crt_id, $num, $price){
    $table = ' cart ';
    $sub_total_price = $num * $price;
    $dataArr = ['num' => $num, 'sub_total_price' => $sub_total_price];
    $where = ' crt_id = ? ';
    $arrWhereVal = [$crt_id];
    return $this->db->update($table, $dataArr, $where, $arrWhereVal);
  }
  public function updateCartData(){
    $table = ' cart ';
  }
}