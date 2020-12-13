<?php

// エラーチェックなどに使う、共通関数を格納したプログラムファイル

/*
ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/lib/Common.class.php
ファイル名 Common.class.php
*/

namespace portfolio_1\lib;

class Common{
  private $dataArr = [];
  private $errArr = [];
  public $db = null; 
  
  public function __construct($db){
    $this->db = $db;
  }

  public function registErrorCheck($dataArr){
    $this->dataArr = $dataArr;
    //クラス内のメソッドを読み込む
    $this->createErrorMessage();
    $this->familyNameCheck();
    $this->firstNameCheck();
    $this->mailCheck('regist');
    $this->userNameCheck();
    $this->passwordCheck('regist');
    $this->sexCheck();
    $this->birthCheck();
    $this->zipCheck();
    $this->addCheck();
    $this->telCheck();
    $this->genreCheck();
    return $this->errArr;
  }

  public function loginErrorCheck($dataArr){
    $this->dataArr = $dataArr;
    //クラス内のメソッドを読み込む
    $this->mailCheck('login');
    $this->passwordCheck('login');
    return $this->errArr;
  }

  public function editErrorCheck($dataArr){
    $this->dataArr = $dataArr;
    //クラス内のメソッドを読み込む
    $this->createErrorMessage();
    $this->familyNameCheck();
    $this->firstNameCheck();
    $this->mailCheck('edit');
    $this->userNameCheck();
    $this->passwordCheck('edit');
    $this->sexCheck();
    $this->birthCheck();
    $this->zipCheck();
    $this->addCheck();
    $this->telCheck();
    $this->genreCheck();
    return $this->errArr;
  }

  public function contactErrorCheck($dataArr){
    $this->dataArr = $dataArr;
    if($this->dataArr['subject'] === ''){
      $this->errArr['subject'] = '件名を入力してください';
    }
    if($this->dataArr['body'] === ''){
      $this->errArr['body'] = '本文を入力してください';
    }
    return $this->errArr;
  }

  private function createErrorMessage(){
    foreach($this->dataArr as $key => $val){
      $this->errArr[$key] = '';
    }
  }

  private function familyNameCheck(){
    if($this->dataArr['family_name'] === ''){
      $this->errArr['family_name'] = 'お名前(氏)を力入してください';
    }
  }

  private function firstNameCheck(){
    if($this->dataArr['first_name'] === ''){
      $this->errArr['first_name'] = 'お名前(名)を入力してください';
    }
  }
  
  private function mailCheck($mode){
    switch($mode){
      //登録の場合
      case 'regist': 
        $table = 'member';
        $col = ' * ';
        $where = ' email = ? ';
        $arrVal = [$this->dataArr['email']];

        // メールアドレスが重複していないかチェック
        if($this->db->select($table, $col, $where, $arrVal)){
          $this->errArr['email'] = 'このメールアドレスは既に登録されています';
        }
        // メールアドレスの形式をチェック
        if(preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+\.([a-zA-Z0-9\_-])+$/', $this->dataArr['email']) === 0){
          $this->errArr['email'] = 'メールアドレスを正しい形式で入力してください';
        }
        break;

      //ログインの場合
      case 'login': 
        // 空でないかチェック
        if($this->dataArr['email'] === ''){
          $this->errArr['email'] = 'メールアドレスを入力してください';
        }
        break;
      
      //編集の場合
      case 'edit':
        $table = 'member';
        $col = ' * ';
        $where = ' email = ? ';
        $arrVal = [$this->dataArr['email']];
        
        // メールアドレスが重複していないかチェック(自分のメールアドレスを除く)
        $res = $this->db->select($table, $col, $where, $arrVal);
        if(isset($res) && count($res) > 0 && $res[0]['mem_id'] !== $_SESSION['mem_id']){
            $this->errArr['email'] = 'このメールアドレスは既に登録されています';
        }

        // メールアドレスの形式をチェック
        if(preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+\.([a-zA-Z0-9\_-])+$/', $this->dataArr['email']) === 0){
          $this->errArr['email'] = 'メールアドレスを正しい形式で入力してください';
        }
        break;
    }
  }

  private function userNameCheck(){
    if($this->dataArr['user_name'] === ''){
      $this->errArr['user_name'] = 'ユーザー名を入力してください';
    }
  }

  private function passwordCheck($mode){
    //登録 or 編集の場合
    if($mode === 'regist' || $mode === 'edit'){
        // パスワードの条件を満たしているかチェック
        if(preg_match('/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{8,100}+\z/', $this->dataArr['password']) === 0){
          $this->errArr['password'] = 'パスワードは "半角の英小文字、英大文字、数字を組み合わせた8文字以上" で入力してください'; 
        }
    }else if($mode === 'login'){  //ログインの場合
        // 空でないかチェック
        if($this->dataArr['password'] === ''){
          $this->errArr['password'] = 'パスワードを入力してください';
        }
    }
  }

  private function sexCheck(){
    if($this->dataArr['sex'] === ''){
      $this->errArr['sex'] = '性別を選択してください';
    }
  }

  private function birthCheck(){
    if($this->dataArr['year'] === ''){
      $this->errArr['year'] = '生年月日の(年)を選択してください';
    }
    if($this->dataArr['month'] === ''){
      $this->errArr['month'] = '生年月日の(月)を選択してください';
    }
    if($this->dataArr['day'] === ''){
      $this->errArr['day'] = '生年月日の(日)を選択してください';
    }
    if(checkdate($this->dataArr['month'], $this->dataArr['day'], $this->dataArr['year']) === false){
      $this->errArr['year'] = '正しい日付を入力してください';
    }
    if(strtotime($this->dataArr['year'] . '-' . $this->dataArr['month']. '-' . $this->dataArr['day']) - strtotime('now') > 0){
      $this->errArr['year'] = '正しい日付を入力してください';
    }
  }

  private function zipCheck(){
    if(preg_match('/^[0-9]{3}$/', $this->dataArr['zip1']) === 0){
      $this->errArr['zip1'] = '郵便番号の上は半角数字3桁で入力してください';
    }
    if(preg_match('/^[0-9]{4}$/', $this->dataArr['zip2']) === 0){
      $this->errArr['zip2'] = '郵便番号の下は半角数字4桁で入力してください';
    }
  }

  private function addCheck(){
    if($this->dataArr['address'] === ''){
      $this->errArr['address'] = '住所を入力してください';
    }
  }
 
  private function telCheck(){
    if(preg_match('/^\d{1,6}$/', $this->dataArr['tel1']) === 0 ||
    preg_match('/^\d{1,6}$/', $this->dataArr['tel2']) === 0 ||
    preg_match('/^\d{1,6}$/', $this->dataArr['tel3']) === 0 ||
    strlen($this->dataArr['tel1'] . $this->dataArr['tel2'] . $this->dataArr['tel3']) >= 12){
      $this->errArr['tel1'] = '電話番号は、半角数字で11桁以内で入力してください';
    }
  }
 
  private function genreCheck(){
    if($this->dataArr['genre'] === []){
      $this->errArr['genre'] = '最低1つの得意ジャンルを入力してください。';
    }
  }

  public function getErrorFlg(){
    $err_check = true;
    foreach($this->errArr as $key => $value){
      if($value !== ''){
        $err_check = false;
      }
    }
    return $err_check;
  }
}