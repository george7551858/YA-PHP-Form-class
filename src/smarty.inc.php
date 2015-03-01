<?php
require_once '../vendor/autoload.php';
require_once '../vendor/smarty/smarty/libs/plugins/shared.escape_special_chars.php';

$smarty = new Smarty();

$smarty->registerPlugin("function","form_text", "smarty_function_text");
$smarty->registerPlugin("function","form_checkbox", "smarty_function_checkbox");
$smarty->registerPlugin("function","form_select", "smarty_function_select");
$smarty->registerPlugin("function","form_radio", "smarty_function_radio");
$smarty->registerPlugin("function","form_submit", "smarty_function_submit");

function gen_title_anno($params)
{
	$title_format = '<label class="col-sm-2 control-label" %s>%s</label>';
	$anno_format  = '<p class="help-block">%s</p>';

	$title_for = (!empty($params['title']) && empty($params['label'])) ? 'for="'.$params['id'].'"' :'';
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

	$input_str = $forminput->html($params);

	list($title_str, $anno_str) = gen_title_anno($params);

	return output($title_str, $input_str, $anno_str);
}

function smarty_function_checkbox($params, $smarty)
{
	$forminput = $params["FI"];
	unset($params["FI"]);

	$input_str = $forminput->html($params);

	list($title_str, $anno_str) = gen_title_anno($params);

	return output($title_str, $input_str, $anno_str);
}

function smarty_function_select($params, $smarty)
{
	$forminput = $params["FI"];
	unset($params["FI"]);

	$input_str = $forminput->html($params);

	list($title_str, $anno_str) = gen_title_anno($params);


	return output($title_str, $input_str, $anno_str);
}

function smarty_function_radio($params, $smarty)
{
	$forminput = $params["FI"];
	unset($params["FI"]);

	$input_str = $forminput->html($params);

	list($title_str, $anno_str) = gen_title_anno($params);


	return output($title_str, $input_str, $anno_str);
}

function smarty_function_submit($params, $smarty)
{
	$str = <<<EOD
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Submit</button>
    </div>
  </div>
EOD;
	return $str;
}


