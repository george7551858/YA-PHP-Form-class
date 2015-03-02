<?php
require_once '../vendor/autoload.php';
require_once 'FI_Style.php';

$smarty = new Smarty();

$smarty->registerPlugin("function","form_text", "smarty_function_input");
$smarty->registerPlugin("function","form_checkbox", "smarty_function_input");
$smarty->registerPlugin("function","form_select", "smarty_function_input");
$smarty->registerPlugin("function","form_radio", "smarty_function_input");
$smarty->registerPlugin("function","form_submit", "smarty_function_submit");


function gen_title_anno($params, $forminput_style)
{
	$title_format = $forminput_style['title'];
	$anno_format  = $forminput_style['anno'];

	$title_for = (!empty($params['title']) && empty($params['label'])) ? 'for="'.$params['id'].'"' :'';
	$title_str = sprintf($title_format, $title_for, $params['title']);

	$anno_str = '';
	if (isset($params['anno'])) {
		$anno_str = sprintf($anno_format, $params['anno']);
	}
	return array($title_str, $anno_str);
}

function output($title_str, $input_str, $anno_str, $forminput_style)
{
	$format = $forminput_style['output'];
	return sprintf($format, $title_str, $input_str, $anno_str);
}

function smarty_function_input($params, $smarty)
{
	$forminput = $params["FI"];
	unset($params["FI"]);

	$forminput_style = FI_Style::create($smarty);

	$input_str = $forminput->html($params,$forminput_style);

	list($title_str, $anno_str) = gen_title_anno($params,$forminput_style);

	return output($title_str, $input_str, $anno_str,$forminput_style);
}

function smarty_function_submit($params, $smarty)
{
	$forminput_style = FI_Style::create($smarty);

	$format = $forminput_style['submit'];
	return $format;
}


