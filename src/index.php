<?php
// require_once 'config.php';
require_once 'FormInput.php';
require_once 'smarty.inc.php';


$db_path = sys_get_temp_dir();
$db_path .= DIRECTORY_SEPARATOR . 'db';
if(!is_dir($db_path)) mkdir($db_path);

$finputs = array(
	'system_name' => 
	new FormInput("system_name","$db_path/system_name"),
	'admin_contact_info' => 
	new FormInput("admin_contact_info","$db_path/admin_contact_info"),
	'httpsCert' => 
	new FormInput("httpsCert","$db_path/httpd_cert_idx"),
	'SSL' => 
	new FormInput("SSL","$db_path/SSL"),
	'usessl_cn' => 
	new FormInput("usessl_cn","$db_path/useSSLCN"),
);

$admin_contact_anno = 'When there is a warning of "Please contact your network administrator"';

$smarty->assign("finputs",$finputs);
$smarty->assign("admin_contact_anno",$admin_contact_anno);

$smarty->assign("data",array("title"=>"cccccc","test"=>date(DATE_RFC2822)));



$smarty->display('index.tpl');

//http://www.smarty.net/docs/en/installing.smarty.extended.tpl
//https://github.com/Alfr0475/ff14news/blob/master/config.php