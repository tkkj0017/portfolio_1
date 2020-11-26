<?php
// 設定ファイル
/*
ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/Bootstrap.class.php
ファイル名 Bootstrap.class.php
*/

namespace portfolio_1;

// ブラウザでの時刻を固定
date_default_timezone_set('Asia/Tokyo');

require_once dirname(__FILE__) . './../vendor/autoload.php';

class Bootstrap{
  const DB_HOST = 'localhost';
  const DB_NAME = 'portfolio_1';
  const DB_USER = 'portfolio_1';
  const DB_PASS = 'portfolio_1';
  const DB_TYPE = 'mysql';

  const APP_DIR = '/Applications/mamp/htdocs/DT/';
  const TEMPLATE_DIR = self::APP_DIR . 'templates/portfolio_1/';
  // const CACHE_DIR = false;
  const CACHE_DIR = self::APP_DIR . 'templates_c/portfolio_1/';
  const APP_URL = 'http://localhost/DT/';
  const ENTRY_URL = self::APP_URL . 'portfolio_1/';
  public static function loadClass($class){
    $path = str_replace('\\', '/', self::APP_DIR . $class . '.class.php');
    require_once $path;
  }
}

// オートローダー
spl_autoload_register([
  'portfolio_1\Bootstrap',
  'loadClass'
]);