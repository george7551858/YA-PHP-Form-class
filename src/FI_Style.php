<?php

class FI_Style
{
	public static $style_pool = array(
		'default' => array(
			"title"  => '<label %s>%s</label>',
			"anno"   => '<p class="help-block">%s</p>',
			"output" => '<div class="label">%s %s %s</div>',
			"text"   => '<input type="text" %s>',
			"checkbox"=>'<label><input type="checkbox" %s>%s</label>',
			"select" => array(
				'wrapper' =>'<select %s>%s</select>',
				'repeat'  =>'<option %s>%s</option>'
			),
			"radio"  => array(
				'wrapper' =>'%s',
				'repeat'  =>'<label><input type="radio" %s>%s</label>'
			),
			"submit" => '<button type="submit">Submit</button>'
		),
		'bootstrap3__v' => array(
			"text"   => '<input type="text" class="form-control" %s>',
			"checkbox"=>'<div class="checkbox"><label><input type="checkbox" %s>%s</label></div>',
			"select" => array(
				'wrapper' =>'<select class="form-control" %s>%s</select>',
				'repeat'  =>'<option %s>%s</option>'
			),
			"radio"  => array(
				'wrapper' =>'%s',
				'repeat'  =>'<div class="radio"><label><input type="radio" %s>%s</label></div>'
			),
			"output" => '<div class="form-group"> %s %s %s</div>',
			"submit" => '<button type="submit" class="btn btn-default">Submit</button>'
		),
		'bootstrap3__h' => array(
			"text"   => '<input type="text" class="form-control" %s>',
			"checkbox"=>'<div class="checkbox"><label><input type="checkbox" %s>%s</label></div>',
			"select" => array(
				'wrapper' =>'<select class="form-control" %s>%s</select>',
				'repeat'  =>'<option %s>%s</option>'
			),
			"radio"  => array(
				'wrapper' =>'%s',
				'repeat'  =>'<label class="radio-inline"><input type="radio" %s>%s</label>'
			),
			"title"  => '<label class="col-sm-3 control-label" %s>%s</label>',
			"output" => '<div class="form-group">%s<div class="col-sm-9">%s %s</div></div>',
			"submit" => '<div class="form-group"><div class="col-sm-offset-3 col-sm-9"><button type="submit" class="btn btn-default">Submit</button></div></div>'
		),
	);
	public static function create($style)
	{
		$style = ($style) ? strtolower($style) : "default";

		$default = self::$style_pool['default'];
		$ret = array();
		switch ($style) {
			case "bootstrap3__v":
				$ret = self::$style_pool['bootstrap3__v'];
				break;
			case "bootstrap3__h":
				$ret = self::$style_pool['bootstrap3__h'];
				break;
			default:
				break;
		}
		return array_merge($default,$ret);
	}
}
