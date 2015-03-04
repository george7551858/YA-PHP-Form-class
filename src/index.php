<?php
// require_once 'config.php';
require_once 'FormInput.php';
require_once 'smarty.inc.php';


$db_path = sys_get_temp_dir();
$db_path .= DIRECTORY_SEPARATOR . 'db';
if(!is_dir($db_path)) mkdir($db_path);

class SuspendMessageCURDHandler extends BaseCURDHandler
{
	public function init($storage)
	{
		if(!$storage) return;

		foreach ($storage as $s) {
			if ( !file_exists($s) ) $this->create($s);
		}
		$this->storage = $storage[0];
		$this->storage2 = $storage[1];
		return $this->read();
	}
	public function update($value)
	{
		file_put_contents($this->storage, $value);
		file_put_contents($this->storage2, "<HTML>\n<BODY>\n".$value."\n</BODY>\n<HTML>");
	}
}


$finputs = array(
	'system_name' => 
		new FormInput_Text("system_name","$db_path/system_name"),
	'admin_contact_info' => 
		new FormInput_Text("admin_contact_info","$db_path/admin_contact_info"),
	'httpsCert' => 
		new FormInput_Select("httpsCert","$db_path/httpd_cert_idx",array("Default CERT"=>"0","AAA"=>"1")),
	'SSL' => 
		new FormInput_Radio("SSL","$db_path/SSL",array("Enabled","Disabled","Secure")),
	'useSSLCN' => 
		new FormInput_Checkbox("useSSLCN","$db_path/useSSLCN",array(0=>"Disabled",1=>"Enabled")),
	'device_name' => 
		new FormInput_Text("device_name","$db_path/device_name"),
	'HOMEPAGE_en' => 
		new FormInput_Radio("HOMEPAGE_en","$db_path/homepage_redirect_enable",array("Enabled","Disabled","None")),
	'succeed_page' => 
		new FormInput_Text("succeed_page","$db_path/succeed_page"),
	'Skip_portal_popup' => 
		new FormInput_Text("Skip_portal_popup","$db_path/Skip_portal_popup"),
	'billlog_ip' => 
		new FormInput_Text("billlog_ip","$db_path/billlog_ip"),
	'SNMP_en' => 
		new FormInput_Radio("SNMP_en","$db_path/snmp/snmp_server",array("Enabled","Disabled")),
	'suspend_message' => 
		new FormInput_Text("suspend_message",array("$db_path/vlan/suspend_message","$db_path/config/suspend_page.html"),NULL,new SuspendMessageCURDHandler),
);



// print_r($finputs);
// echo ">".$finputs['usessl_cn']->option_values["on"]."<";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
	foreach ($finputs as $name => $finput) {
		$finput->load_post();
	}
	foreach ($finputs as $name => $finput) {
		$finput->save();
	}
}

$smarty->assign("finputs",$finputs);

$smarty->assign("data",array("title"=>"cccccc","test"=>date(DATE_RFC2822)));



$smarty->display('index.tpl');

//http://www.smarty.net/docs/en/installing.smarty.extended.tpl
//https://github.com/Alfr0475/ff14news/blob/master/config.php