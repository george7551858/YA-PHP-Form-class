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
		new FormInput_Select("httpsCert","$db_path/httpd_cert_idx",array("0","1")),
	'SSL' => 
		new FormInput_Radio("SSL","$db_path/SSL",array("on","off")),
	'usessl_cn' => 
		new FormInput_Check("usessl_cn","$db_path/useSSLCN",array(0=>"off",1=>"on")),
	'usessl_cn2' => 
		new FormInput_Check("usessl_cn2","$db_path/useSSLCN2",array(0=>"off",1=>"on")),
	'usessl_cn3' => 
		new FormInput_Check("usessl_cn3","$db_path/useSSLCN3",array(0=>"off",1=>"on")),
);

// print_r($finputs);
// echo ">".$finputs['usessl_cn']->option_values["on"]."<";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
	foreach ($finputs as $name => $finput) {
		$finput->save();
	}
}

$smarty->assign("finputs",$finputs);

$smarty->assign("data",array("title"=>"cccccc","test"=>date(DATE_RFC2822)));



$smarty->display('index.tpl');

//http://www.smarty.net/docs/en/installing.smarty.extended.tpl
//https://github.com/Alfr0475/ff14news/blob/master/config.php