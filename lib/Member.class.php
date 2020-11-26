<?php

// 登録者データを扱うクラスファイル

/*
ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/lib/Member.class.php
ファイル名 Member.class.php
*/

namespace portfolio_1\lib;

class Member{
  public $dataArr = [];
  public $db = null;

  public function __construct($db){
    $this->db = $db;
  }

  // 登録者を新たに追加する
  public function insertMember($dataArr){
    $table = ' member ';
    // パスワードのハッシュ化
    $dataArr['password'] = password_hash($dataArr['password'], PASSWORD_DEFAULT);
    // 交通手段の配列の解除
    $dataArr['traffic'] = implode('_', $dataArr['traffic']);
    // 登録時刻の取得
    $dataArr['regist_date'] = date("Y-m-d H:i:s");
    $dataArr['delete_flg'] = '';
    $res = $this->db->insert($table, $dataArr);
    return ($res !== false && count($res) !== 0) ? $res : false;
  }
  
  // 登録者のプロフィールの更新をする
  public function updateMember($dataArr){
    $table = ' member ';
    // パスワードのハッシュ化
    $dataArr['password'] = password_hash($dataArr['password'], PASSWORD_DEFAULT);
    // 交通手段の配列の解除
    $dataArr['traffic'] = implode('_', $dataArr['traffic']);
    $dataArr['update_date'] = date("Y-m-d H:i:s");
    $dataArr['delete_flg'] = '';
    $where = 'mem_id = ?';
    // メンバーIDの指定
    $arrWhereVal = [$_SESSION['mem_id']];
    $res = $this->db->update($table, $dataArr, $where, $arrWhereVal);
    return ($res !== false && count($res) !== 0) ? $res : false;
  }

  // 登録者を退会させる→論理的な消去(DBにレコードは残す)
  public function deleteMember($mem_id){
    $table = ' member ';
    // emailカラムの中身消去。
    // 退会時間の設定
    // delete_flgを立てる。
    $dataArr = ['email' => '', 'delete_date' => date("Y-m-d H:i:s") ,'delete_flg' => 1];
    $where = ' mem_id = ? ';
    $arrWhereVal = [$mem_id];

    return $this->db->update($table, $dataArr, $where, $arrWhereVal);
  }

  // 登録者全員のデータを取得する
  public function getMemberList(){
    $table = ' member ';
    $col = ' mem_id, family_name, first_name, family_name_kana, first_name_kana, email, user_id, sex, traffic, regist_date, update_date ';
    $res = $this->db->select($table, $col);
    return ($res !== false && count($res) !== 0) ? $res : false;
  }
  
  // 登録者の詳細情報を取得する(passwordはHTMLで******と伏せて表示)
  public function getMemberDetail($mem_id){ 
    $table = ' member ';
    $col = ' * ';
    $where = ($mem_id !== '') ? ' mem_id = ? ' : '';
    $arrVal = ($mem_id !== '') ? [$mem_id] : [];
    $res = $this->db->select($table, $col, $where, $arrVal);
    return ($res !== false && count($res) !== 0) ? $res : false;
  }
  
  // ログインする(メールアドレス・パスワード)
  public function login($email){
    $table = ' member ';
    $col = ' * ';
    $where = ($email !== '') ? ' email = ? ' : '';
    $arrVal = ($email !== '') ? [$email] : '';
    $res = $this->db->select($table, $col, $where, $arrVal);
    return ($res !== false && count($res) !== 0) ? $res : false;
  }
}