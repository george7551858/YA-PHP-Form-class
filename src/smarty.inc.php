<?php
require_once '../vendor/autoload.php';
require_once '../vendor/smarty/smarty/libs/plugins/shared.escape_special_chars.php';

$smarty = new Smarty();

$smarty->registerPlugin("function","form_text", "smarty_function_text");
$smarty->registerPlugin("function","form_checkbox", "smarty_function_checkbox");
$smarty->registerPlugin("function","form_select", "smarty_function_select");
$smarty->registerPlugin("function","form_radio", "smarty_function_radio");

function gen_input_attr($type,$params,$forminput)
{
	$attr = array();
	$attr["type"] = $type;
	$attr["id"] = $forminput->name;
	$attr["name"] = $forminput->name;
	$attr["value"] = $forminput->value;

	foreach ($params as $_key => $_val) {
		switch ($_key) {
			case "title":
			case "anno":
			case "label":
				break;
			default:
				$attr[$_key] = smarty_function_escape_special_chars($_val);
				break;
		}
	}
	return $attr;
}

function gen_title_anno($params,$input_attr)
{
	$title_format = '<label class="col-sm-2 control-label" %s>%s</label>';
	$anno_format  = '<p class="help-block">%s</p>';

	$title_for = (!empty($params['title']) && empty($params['label'])) ? 'for="'.$input_attr['id'].'"' :'';
	$title_str = sprintf($title_format, $title_for, $params['title']);

	$anno_str = '';
	if (isset($params['anno'])) {
		$anno_str = sprintf($anno_format, $params['anno']);
	}
	return array($title_str, $anno_str);
}

function output($title_str, $input_str, $anno_str)
{
	$format = <<<EOD
<div class="form-group">
    %s
    <div class="col-sm-10">
      %s %s
    </div>
  </div>
EOD;
	return sprintf($format, $title_str, $input_str, $anno_str);
}

function smarty_function_text($params, $smarty)
{
	$forminput = $params["FI"];
	unset($params["FI"]);

	$input_attr = gen_input_attr("text",$params,$forminput);

	$input_str = '<input class="form-control"';
	foreach ($input_attr as $_key => $_val) {
		$input_str .=" $_key=\"$_val\"";
	}
	$input_str .='>';

	list($title_str, $anno_str) = gen_title_anno($params,$input_attr);

	return output($title_str, $input_str, $anno_str);
}

function smarty_function_checkbox($params, $smarty)
{
	$forminput = $params["FI"];
	unset($params["FI"]);

	$input_attr = gen_input_attr("checkbox",$params,$forminput);

	$input_str = '<input ';
	foreach ($input_attr as $_key => $_val) {
		$input_str .=" $_key=\"$_val\"";
	}
	if( $forminput->value === true ) $input_str.= " checked";
	$input_str .='>';

	if( !empty($params['label']) ) {
		$input_str = '<div class="checkbox"><label>'. $input_str .' '. $params['label'] . '</label></div>';
	}

	list($title_str, $anno_str) = gen_title_anno($params,$input_attr);


	return output($title_str, $input_str, $anno_str);
}

function smarty_function_select($params, $smarty)
{
	$forminput = $params["FI"];
	unset($params["FI"]);

	$input_attr = gen_input_attr("select",$params,$forminput);

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

	list($title_str, $anno_str) = gen_title_anno($params,$input_attr);


	return output($title_str, $input_str, $anno_str);
}

function smarty_function_radio($params, $smarty)
{
	$forminput = $params["FI"];
	unset($params["FI"]);

	$input_attr = gen_input_attr("radio",$params,$forminput);

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

	list($title_str, $anno_str) = gen_title_anno($params,$input_attr);


	return output($title_str, $input_str, $anno_str);
}