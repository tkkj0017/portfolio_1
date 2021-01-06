<?php
// トップページのニュースの投稿データを扱うクラスファイル

/*
ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/lib/News.class.php
ファイル名 News.class.php
*/

namespace portfolio_1\lib;

class News{
  public $db = null;

  public function __construct($db){
    $this->db = $db;
  }

  // ニュースを取得する
  public function getNews(){
    $table = ' news ';

    // 日付を降順で取得する
    $this->db->setOrder(' regist_date DESC ');

    $column = ' news_id, title, body, regist_date, update_date ';
    $where = ' delete_flg = ? ';
    $arrWhereVal = [0];

    return $this->db->select($table, $column, $where, $arrWhereVal);
  }

  // ニュースを投稿する
  public function insertNews($dataArr){
    $table = ' news ';
    $dataArr['regist_date'] = date("Y-m-d H:i:s");
    $dataArr['delete_flg'] = '';
    $res = $this->db->insert($table, $dataArr);
    return ($res !== false) ? $res : false;
  }

  // ニュースを1データ削除する
  public function deleteNews($news_id){
    $table = ' news ';
    $delData = ['delete_date' => date("Y-m-d H:i:s"), 'delete_flg' => 1];
    $where = ' news_id  = ? ';
    $arrWhereVal = [$news_id];

    return $this->db->update($table, $delData, $where, $arrWhereVal);
  }

  // ニュースの内容を更新する
  public function updateNews($dataArr){
    $table = ' news ';

    $dataArr['update_date'] = date("Y-m-d H:i:s");
    $dataArr['delete_flg'] = '';
    $where = 'news_id = ?';
    $arrWhereVal = [$dataArr['news_id']];

    $res = $this->db->update($table, $dataArr, $where, $arrWhereVal);
    return ($res !== false && count($res) !== 0) ? $res : false;
  }
}