<?php
require_once '../vendor/autoload.php';
require_once '../vendor/smarty/smarty/libs/plugins/shared.escape_special_chars.php';

$smarty = new Smarty();

$smarty->registerPlugin("function","form_text", "smarty_function_text");
$smarty->registerPlugin("function","form_checkbox", "smarty_function_checkbox");
$smarty->registerPlugin("function","form_select", "smarty_function_select");
$smarty->registerPlugin("function","form_radio", "smarty_function_radio");

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

	$input_str = '<input ';
	foreach ($input_attr as $_key => $_val) {
		$input_str .=" $_key=\"$_val\"";
	}
	if( $forminput->value === true ) $input_str.= " checked";
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
		$input_str = '<div class="checkbox"><label>'. $input_str .' '. $params['label'] . '</label></div>';
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
	$input_attr["value"] = $forminput->value;

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

	$option_labels = explode(';', $params["label"]);
	if (count($option_labels) !== count($forminput->option_values)) {
	}

	$options = array_combine($option_labels,$forminput->option_values);
	unset($input_attr["option_values"]);

	$input_str = '<select class="form-control"';
	foreach ($input_attr as $_key => $_val) {
		$input_str .=" $_key=\"$_val\"";
	}
	$input_str .='>';

	foreach ($options as $label => $value) {
		$input_str.= "<option value=\"$value\"";
		if( $input_attr["value"] == $value) $input_str.= " selected";
		$input_str.= ">$label";
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

function smarty_function_radio($params, $smarty)
{
	$forminput = $params["FI"];
	unset($params["FI"]);

	$input_attr = array();
	$input_attr["type"] = "radio";
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
				$input_attr[$_key] = $_val;
				break;
		}
	}

	$option_labels = explode(';', $params["label"]);
	if (count($option_labels) !== count($forminput->option_values)) {
	}

	$options = array_combine($option_labels,$forminput->option_values);
	unset($input_attr["option_values"]);

	$input_str = "";
	foreach ($options as $label => $value) {
		$input_str.= "<label class=\"radio-inline\">";
		$input_str.= "<input type=\"radio\" name=\"".$input_attr["name"]."\" value=\"$value\"";
		if( $input_attr["value"] == $value) $input_str.= " checked";
		$input_str.= ">$label";
		$input_str.= "</label>";
	}

	$title_str = '<label class="col-sm-2 control-label" ';
	if (!empty($params['title']) && empty($params['label'])){
		$title_str .= ' for="'.$input_attr['id'].'"';
	}
	$title_str .= '>' . $params['title'] . '</label>';

	$anno_str = '';
	if (isset($params['anno'])) {
		$anno_str = '<p class="help-block">' . $params['anno'] . '</p>';
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