<?php

// セッション関係のクラスファイル

/*
ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/lib/Session.class.php
ファイル名 Session.class.php
*/

namespace portfolio_1\lib;

class Session{
  public $session_key = '';
  public $db = NULL;

  public function __construct($db){
    //セッションをスタートする(セッション関連の記述をするために必要)
    session_start();
    //セッションIDを取得する(PHPSESSID)
    $this->session_key = session_id(); //クッキーから保存するか新しく加工するか
    //DBの登録
    $this->db = $db; //インスタンス化されたDBを使える
  }

  public function checkSession(){
    //セッションIDのチェック
    $customer_no = $this->selectSession();
    //セッションIDがある(過去にショッピングカートに来た事がある)
    if($customer_no !== false){
      $_SESSION['customer_no'] = $customer_no;
    }else{
      //セッションIDが無い場合(初めてこのサイトに来ている)
      $res = $this->insertSession();
      if($res === true){
        $_SESSION['customer_no'] = $this->db->getLastId();
      }else{
        $_SESSION['customer_no'] = '';
      }
    }
  }
  
  private function selectSession(){
    $table = ' session ';
    $col = ' customer_no ';
    $where = ' session_key = ? ';
    $arrVal = [$this->session_key];
    $res = $this->db->select($table, $col, $where, $arrVal);
    return(count($res) !== 0) ? $res[0]['customer_no'] : false;
  }

  private function insertSession(){
    $table = ' session ';
    $insData = ['session_key' => $this->session_key];
    // 現在時刻の取得
    $insData['regist_date'] = date("Y-m-d H:i:s");
    $insData['delete_flg'] = '';
    $res = $this->db->insert($table, $insData);
    return $res;
  }

  private function deleteSession(){
    
  }
}