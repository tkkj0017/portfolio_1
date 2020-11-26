<?php

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/contact_complete.php
ファイル名 contact.php
アクセスURL http://localhost/DT/portfolio_1/controller/contact_complete.php
*/

namespace portfolio_1;

require_once dirname(__FILE__) . './../Bootstrap.class.php';
use portfolio_1\Bootstrap;
use portfolio_1\lib\LoginSession;
use portfolio_1\lib\PDODatabase;


// テンプレート指定
$loader = new \Twig_Loader_Filesystem
(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$logses = new LoginSession($db);


$context['session'] = $_SESSION;

$template = $twig->loadTemplate('contact_complete.html.twig');
$template->display($context);