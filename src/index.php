<?php
require_once 'config.php';
require_once '../vendor/autoload.php';

$smarty = new Smarty();
$smarty->template_dir = dirname(__FILE__) . "/templates/";
$smarty->compile_dir  = dirname(__FILE__) . "/templates_c/";
$smarty->assign("data",array("title"=>"cccccc","test"=>123));
$smarty->display('index.tpl');

//http://www.smarty.net/docs/en/installing.smarty.extended.tpl
//https://github.com/Alfr0475/ff14news/blob/master/config.php