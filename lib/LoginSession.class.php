<?php

// セッション関係のクラスファイル

/*
ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/lib/LoginSession.class.php
ファイル名 LoginSession.class.php
*/

namespace portfolio_1\lib;

class LoginSession{
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

  public function checkSession($mem_id='', $user_id= ''){
    //セッションIDのチェック
    // $this->selectSession($mem_id);
    //セッションIDがある(過去にショッピングカートに来た事がある)
    if($this->selectSession($mem_id) !== false){
      $_SESSION['login_flg'] = '1';
      $_SESSION['mem_id'] = $mem_id;
      $_SESSION['user_id'] = $user_id;
    }else{
      //セッションIDが無い場合(初めてこのサイトに来ている)
      $res = $this->insertSession($mem_id, $user_id);
      if($res === true){
        $_SESSION['login_flg'] = '1';
        $_SESSION['mem_id'] = $mem_id;
        $_SESSION['user_id'] = $user_id;
      }else{
        $_SESSION['login_flg'] = '';
        $_SESSION['mem_id'] = '';
        $_SESSION['user_id'] = '';
      }
    }
  }
  
  private function selectSession($mem_id){
    $table = ' loginsession ';
    $col = ' session_key ';
    $where = ' mem_id = ? ';
    $arrVal = [$mem_id];
    $res = $this->db->select($table, $col, $where, $arrVal);
    return(count($res) !== 0) ? true : false;
  }

  private function insertSession($mem_id, $user_id){
    $table = ' loginsession ';
    $insData = [];
    $insData['mem_id'] = $mem_id;
    $insData['user_id'] = $user_id;
    $insData['session_key'] = $this->session_key;
    // 現在時刻の取得
    $insData['regist_date'] = date("Y-m-d H:i:s");
    $insData['delete_flg'] = '';
    $res = $this->db->insert($table, $insData);
    return $res;
  }

  public function endSession(){
    session_destroy();
  }
}