<?php

/*
*ファイルパス /Applications/MAMP/htdocs/DT/portfolio_1/controller/resign_complete.php
ファイル名 resign_complete.php
アクセスURL http://localhost/DT/portfolio_1/controller/resign_complete.php
*/

namespace portfolio_1;

require_once dirname(__FILE__) . './../Bootstrap.class.php';
use portfolio_1\Bootstrap;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
  'cache' => Bootstrap::CACHE_DIR
]);

$template = $twig->loadTemplate('resign_complete.html.twig');
$template->display([]);