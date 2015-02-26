<?php
require_once '../vendor/autoload.php';
require_once '../vendor/smarty/smarty/libs/plugins/shared.escape_special_chars.php';

$smarty = new Smarty();

$smarty->registerPlugin("function","form_text", "smarty_function_text");
$smarty->registerPlugin("function","form_checkbox", "smarty_function_checkbox");
$smarty->registerPlugin("function","form_select", "smarty_function_select");


function smarty_function_text($params, $smarty)
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
			case "title":
			case "anno":
			case "label":
				break;
			default:
				$input_attr[$_key] = smarty_function_escape_special_chars($_val);
				break;
		}
	}

	$input_str = '<input class="form-control"';
	foreach ($input_attr as $_key => $_val) {
		$input_str .=" $_key=\"$_val\"";
	}
	$input_str .='>';


	$title_str = '<label class="col-sm-2 control-label" ';
	if (!empty($params['title']) && empty($params['label'])){
		$title_str .= ' for="'.$input_attr['id'].'"';
	}
	$title_str .= '>' . $params['title'] . '</label>';

	$anno_str = '';
	if (isset($params['anno'])) {
		$anno_str = '<p class="help-block">' . $params['anno'] . '</p>';
	}

	if( !empty($params['label']) ) {
		$input_str = '<label>'. $input_str .' '. $params['label'] . '</label>';
	}

	$str = <<<EOD
<div class="form-group">
    $title_str
    <div class="col-sm-10">
      $input_str $anno_str
    </div>
  </div>
EOD;
	return $str;
}

function smarty_function_checkbox($params, $smarty)
{
	$forminput = $params["FI"];
	unset($params["FI"]);

	$input_attr = array();
	$input_attr["type"] = "checkbox";
	$input_attr["id"] = $forminput->name;
	$input_attr["name"] = $forminput->name;
	$input_attr["value"] = "Enable";

	foreach ($params as $_key => $_val) {
		switch ($_key) {
			case "title":
			case "anno":
			case "label":
				break;
			default:
				$input_attr[$_key] = smarty_function_escape_special_chars($_val);
				break;
		}
	}

	$input_str = '<input class="form-control"';
	foreach ($input_attr as $_key => $_val) {
		$input_str .=" $_key=\"$_val\"";
	}
	$input_str .='>';


	$title_str = '<label class="col-sm-2 control-label" ';
	if (!empty($params['title']) && empty($params['label'])){
		$title_str .= ' for="'.$input_attr['id'].'"';
	}
	$title_str .= '>' . $params['title'] . '</label>';

	$anno_str = '';
	if (isset($params['anno'])) {
		$anno_str = '<p class="help-block">' . $params['anno'] . '</p>';
	}

	if( !empty($params['label']) ) {
		$input_str = '<label>'. $input_str .' '. $params['label'] . '</label>';
	}

	$str = <<<EOD
<div class="form-group">
    $title_str
    <div class="col-sm-10">
      $input_str $anno_str
    </div>
  </div>
EOD;
	return $str;
}

function smarty_function_select($params, $smarty)
{
	$forminput = $params["FI"];
	unset($params["FI"]);

	$input_attr = array();
	$input_attr["type"] = "select";
	$input_attr["id"] = $forminput->name;
	$input_attr["name"] = $forminput->name;

	foreach ($params as $_key => $_val) {
		switch ($_key) {
			case "title":
			case "anno":
			case "label":
				break;
			default:
				$input_attr[$_key] = $_val;
				break;
		}
	}

	if (count($input_attr["option_labels"]) !== count($forminput->option_values)) {
	}

	$options = array_combine($input_attr["option_labels"],$forminput->option_values);
	unset($input_attr["option_labels"]);
	unset($input_attr["option_values"]);

	$input_str = '<select class="form-control"';
	foreach ($input_attr as $_key => $_val) {
		$input_str .=" $_key=\"$_val\"";
	}
	$input_str .='>';

	foreach ($options as $_key => $_val) {
		$input_str.= "<option value=\"$_val\">$_key";
		$input_str.= "</option>";
	}
	$input_str.= "</select>";

	$title_str = '<label class="col-sm-2 control-label" ';
	if (!empty($params['title']) && empty($params['label'])){
		$title_str .= ' for="'.$input_attr['id'].'"';
	}
	$title_str .= '>' . $params['title'] . '</label>';

	$anno_str = '';
	if (isset($params['anno'])) {
		$anno_str = '<p class="help-block">' . $params['anno'] . '</p>';
	}

	if( !empty($params['label']) ) {
		$input_str = '<label>'. $input_str .' '. $params['label'] . '</label>';
	}

	$str = <<<EOD
<div class="form-group">
    $title_str
    <div class="col-sm-10">
      $input_str $anno_str
    </div>
  </div>
EOD;
	return $str;
}
