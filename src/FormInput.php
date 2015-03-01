<?php

class FormInput
{
	public $name;
	public $value;
	// public $file_path;
	protected $curd_handler;
	protected $html_handler;
	public function __construct($name, $file_path ,$curd_handler=NULL,$html_handler=NULL)
	{
		$this->name = $name;
		$this->curd_handler = ($curd_handler) ? $curd_handler : new BaseCURDHandler($file_path);
		$this->html_handler = ($html_handler) ? $html_handler : new BaseHTMLHandler;

		$this->value = $this->curd_handler->read();
	}

	public function save()
	{
		$this->value = $this->curd_handler->get_post_value($this->name);
		$this->curd_handler->update($this->value);
	}
	public function html($params,$input_attr)
	{
		return $this->html_handler->output($params,$input_attr,$this->value);
	}
}


class FormInput_Options extends FormInput
{
	public $option_values = array();
	public function __construct($name, $file_path, $option_values,$curd_handler=NULL,$html_handler=NULL)
	{
		$this->option_values = $option_values;

		parent::__construct($name, $file_path ,$curd_handler,$html_handler);
	}
	public function html($params,$input_attr)
	{
		return $this->html_handler->output($params,$input_attr,$this->value,$this->option_values);
	}
}

class FormInput_Check extends FormInput_Options
{
	public function __construct($name, $file_path, $option_values,$curd_handler=NULL,$html_handler=NULL)
	{
		$curd_handler = ($curd_handler) ? $curd_handler : new CheckboxCURDHandler($file_path);
		$curd_handler->option_values = $option_values;
		$html_handler = ($html_handler) ? $html_handler : new CheckboxHTMLHandler;
		parent::__construct($name, $file_path, $option_values,$curd_handler,$html_handler);
	}
}

class FormInput_Radio extends FormInput_Options
{
	public function __construct($name, $file_path, $option_values,$curd_handler=NULL,$html_handler=NULL)
	{
		$html_handler = ($html_handler) ? $html_handler : new RadioHTMLHandler;
		parent::__construct($name, $file_path, $option_values,$curd_handler,$html_handler);
	}
}

class FormInput_Select extends FormInput_Options
{
	public function __construct($name, $file_path, $option_values,$curd_handler=NULL,$html_handler=NULL)
	{
		$html_handler = ($html_handler) ? $html_handler : new SelectHTMLHandler;
		parent::__construct($name, $file_path, $option_values,$curd_handler,$html_handler);
	}
}

class BaseCURDHandler
{
	public $storage;
	public function __construct($storage)
	{
		$this->storage = $storage;
		if ( !file_exists($storage) ) $this->cread();
	}
	public function create()
	{
		touch($this->storage);
	}
	public function update($value)
	{
		file_put_contents($this->storage, $value);
	}
	public function read()
	{
		return file_get_contents($this->storage);
	}
	public function get_post_value($name)
	{
		//TODO: check
		return @$_POST[$name];
	}
}


class CheckboxCURDHandler extends BaseCURDHandler
{
	public $option_values;
	public function update($chk_bool)
	{
		$value = $this->option_values[$chk_bool];
		file_put_contents($this->storage, $value);
	}
	public function read()
	{
		$value = file_get_contents($this->storage);
		$chk_bool = ($value === $this->option_values[1]) ? 1 : 0;
		return $chk_bool;
	}
	public function get_post_value($name)
	{
		//TODO: check
		$value = @$_POST[$name];
		$chk_bool = ($value === $this->option_values[1]) ? 1 : 0;
		return $chk_bool;
	}
}


class BaseHTMLHandler
{
	function output($input_attr)
	{
		$html = '<input class="form-control"';
		foreach ($input_attr as $_key => $_val) {
			$html .=" $_key=\"$_val\"";
		}
		$html .='>';
		return $html;
	}
}

class CheckboxHTMLHandler extends BaseHTMLHandler
{
	function output($params,$input_attr,$value)
	{
		$html = '<input ';
		foreach ($input_attr as $_key => $_val) {
			$html .=" $_key=\"$_val\"";
		}
		if( $value === 1 ) $html.= " checked";
		$html .='>';

		if( !empty($params['label']) ) {
			$html = '<div class="checkbox"><label>'. $html .' '. $params['label'] . '</label></div>';
		}
		return $html;
	}
}

class RadioHTMLHandler extends BaseHTMLHandler
{
	function output($params,$input_attr,$value,$option_values)
	{
		$option_labels = explode(';', $params["label"]);
		$options = array_combine($option_labels,$option_values);

		$html = "";
		foreach ($options as $label => $_val) {
			$html.= "<label class=\"radio-inline\">";
			$html.= "<input type=\"radio\" name=\"".$input_attr["name"]."\" value=\"$value\"";
			if( $value == $_val) $html.= " checked";
			$html.= ">$label";
			$html.= "</label>";
		}
		return $html;
	}
}

class SelectHTMLHandler extends BaseHTMLHandler
{
	function output($params,$input_attr,$value,$option_values)
	{
		$option_labels = explode(';', $params["label"]);
		$options = array_combine($option_labels,$option_values);

		$html = '<select class="form-control"';
		foreach ($input_attr as $_key => $_val) {
			$html .=" $_key=\"$_val\"";
		}
		$html .='>';

		foreach ($options as $label => $_val) {
			$html.= "<option value=\"$_val\"";
			if( $value == $_val) $html.= " selected";
			$html.= ">$label";
			$html.= "</option>";
		}
		$html.= "</select>";
		return $html;
	}
}