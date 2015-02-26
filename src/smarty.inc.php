<?php
require_once '../vendor/autoload.php';

$smarty = new Smarty();
$smarty->template_dir = dirname(__FILE__) . "/templates/";
$smarty->compile_dir  = dirname(__FILE__) . "/templates_c/";

$smarty->registerPlugin("function","form_input_text", "smarty_function_input_text");

function smarty_function_input_text($params, $smarty)
{

	$forminput = $params["FI"];
	unset($params["FI"]);

	$input_attr = array();
	$input_attr["type"] = "text";
	$input_attr["id"] = $forminput->name;
	$input_attr["name"] = $forminput->name;
	$input_attr["value"] = $forminput->value;

	foreach ($params as $_key => $_val) {
		switch ($_key) {
			case "label":
				break;
			default:
				$input_attr[$_key] = $_val;
				break;
		}
	}

	$input_str = '<input class="form-control"';
	foreach ($input_attr as $_key => $_val) {
		$input_str .=" $_key=\"$_val\"";
	}
	$input_str .='>';

	$label_str = '';
	if (isset($params['label'])) {
		$label_str = '<label class="col-sm-2 control-label" for="'.$input_attr['id'].'">';
		$label_str .= $params['label'] . '</label>';
	}

	$anno_str = '';
	if (isset($params['anno'])) {
		$anno_str = '<p class="help-block">' . $params['anno'] . '</p>';
	}

	$str = <<<EOD
<div class="form-group">
    $label_str <!-- {$input_attr['name']} -->
    <div class="col-sm-10">
      $input_str $anno_str
    </div>
  </div>
EOD;
	return $str;
}