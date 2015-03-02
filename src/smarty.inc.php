<?php
require_once '../vendor/autoload.php';
require_once '../vendor/smarty/smarty/libs/plugins/shared.escape_special_chars.php';

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

	$input_str = $forminput->html($params);

	list($title_str, $anno_str) = gen_title_anno($params,$forminput_style);

	return output($title_str, $input_str, $anno_str,$forminput_style);
}

function smarty_function_submit($params, $smarty)
{
	$forminput_style = FI_Style::create($smarty);

	$format = $forminput_style['submit'];
	return $format;
}


class FI_Style
{
	public static function create($smarty)
	{
		$type = $smarty->getTemplateVars('STYLE');
		$type = ($type) ? strtolower($type) : "bootstrap3__h_3:9";

		switch ($type) {
			case "bootstrap3__h_2:10":
				return array(
					"title"  =>'<label class="col-sm-2 control-label" %s>%s</label>'
					,"anno"   =>'<p class="help-block">%s</p>'
					,"output" => <<<EOD
<div class="form-group">
    %s
    <div class="col-sm-10">
      %s %s
    </div>
  </div>
EOD
					,"submit" => <<<EOD
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Submit</button>
    </div>
  </div>
EOD
				);
				break;
			case "bootstrap3__h_3:9":
				return array(
					"title"  =>'<label class="col-sm-3 control-label" %s>%s</label>'
					,"anno"   =>'<p class="help-block">%s</p>'
					,"output" => <<<EOD
<div class="form-group">
    %s
    <div class="col-sm-9">
      %s %s
    </div>
  </div>
EOD
					,"submit" => <<<EOD
<div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
      <button type="submit" class="btn btn-default">Submit</button>
    </div>
  </div>
EOD
				);
				break;
			default:
				break;
		}
	}
}
