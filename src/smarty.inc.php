<?php
require_once '../vendor/autoload.php';
require_once 'FI_Style.php';
require_once 'HTMLHandler.php';

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

	$title_for = '';
	if (!empty($params['title']) && empty($params['label'])){
		$title_for = 'for="'.smarty_function_escape_special_chars($params['id']).'"';
	}

	$title = smarty_function_escape_special_chars(@$params['title']);
	$title_html = sprintf($title_format, $title_for, $title);

	$anno_html = '';
	if (isset($params['anno'])) {
		$anno = smarty_function_escape_special_chars($params['anno']);
		$anno_html = sprintf($anno_format, $anno);
	}
	return array($title_html, $anno_html);
}

function output($title_html, $input_html, $anno_html, $forminput_style)
{
	$format = $forminput_style['output'];
	return sprintf($format, $title_html, $input_html, $anno_html);
}

function get_HTML_builder($type)
{
	$className = ucfirst(strtolower($type)).'HTMLHandler';
	if (class_exists($className)) {
		return new $className();
	}
}

function smarty_function_input($params, $smarty)
{
	require_once(SMARTY_PLUGINS_DIR . 'shared.escape_special_chars.php');

	$fi = $params["FI"];
	unset($params["FI"]);

	if( ! isset($params['id']) ) $params['id'] = $fi->name;

	$fi_html_builder = get_HTML_builder($fi->type);
	$style = $smarty->getTemplateVars('STYLE');

	$fi_style = FI_Style::create($style);
	$fi_format = $fi_style[$fi->type];


	$input_html = $fi_html_builder->output($params,$fi_format,$fi);

	list($title_html, $anno_html) = gen_title_anno($params,$fi_style);

	return output($title_html, $input_html, $anno_html,$fi_style);
}

function smarty_function_submit($params, $smarty)
{
	$style = $smarty->getTemplateVars('STYLE');
	$fi_style = FI_Style::create($style);

	$format = $fi_style['submit'];
	return $format;
}


