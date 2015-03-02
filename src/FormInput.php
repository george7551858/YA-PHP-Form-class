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
		$tmp_value = $this->curd_handler->get_post_value($this->name);
		if( is_null($tmp_value) ) return;
		$this->value = $tmp_value;
		$this->curd_handler->update($this->value);
	}
	public function html(&$params)
	{
		if( ! isset($params['id']) ) $params['id'] = $this->name;
		return $this->html_handler->output($params,$this->name,$this->value);
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
	public function html(&$params)
	{
		if( ! isset($params['id']) ) $params['id'] = $this->name;
		return $this->html_handler->output($params,$this->name,$this->value,$this->option_values);
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
		if (isset($_POST[$name])) {
			return $_POST[$name];
		}
		return NULL;
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
		$value = parent::get_post_value($name);
		if (is_null($value)) return NULL;
		$chk_bool = ($value === $this->option_values[1]) ? 1 : 0;
		return $chk_bool;
	}
}


class BaseHTMLHandler
{
	function escape_special_chars($string)
	{
		if (!is_array($string)) {
			$string = htmlspecialchars($string, ENT_COMPAT, Smarty::$_CHARSET, false);
		}

		return $string;
	}

	function gen_input_attr($type,$params,$name,$value,$option_values="")
	{
		$attr = array();
		$attr["type"] = $type;
		$attr["name"] = $name;
		$attr["value"] = $value;
		if ($type === "checkbox") {
			$attr["value"] = $option_values[1];
		}

		foreach ($params as $_key => $_val) {
			switch ($_key) {
				case "title":
				case "anno":
				case "label":
				case "value":
				case "checked":
				case "selected":
					break;
				default:
					$attr[$_key] = $_val;
					break;
			}
		}

		$ret = "";
		foreach ($attr as $_key => $_val) {
			$_key = $this->escape_special_chars($_key);
			$_val = $this->escape_special_chars($_val);
			$ret .=" $_key=\"$_val\"";
		}
		return $ret;
	}
	function output($params,$name,$value)
	{
		$html = '<input class="form-control"';
		$html.= $this->gen_input_attr("text",$params,$name,$value);
		$html.= '>';
		return $html;
	}
}

class CheckboxHTMLHandler extends BaseHTMLHandler
{
	function output($params,$name,$value,$option_values)
	{
		$html = '<input ';
		$html.= $this->gen_input_attr("checkbox",$params,$name,$value,$option_values);
		if( $value === 1 ) $html.= " checked";
		$html.= '>';

		if( !empty($params['label']) ) {
			$html = '<div class="checkbox"><label>'. $html .' '. $params['label'] . '</label></div>';
		}
		return $html;
	}
}

class RadioHTMLHandler extends BaseHTMLHandler
{
	function output($params,$name,$value,$option_values)
	{
		$option_labels = explode(';', $params["label"]);
		$options = array_combine($option_labels,$option_values);

		$html = "";
		foreach ($options as $label => $_val) {
			$html.= "<label class=\"radio-inline\">";
			$html.= "<input type=\"radio\" name=\"$name\" value=\"$_val\"";
			if( $value == $_val) $html.= " checked";
			$html.= ">$label";
			$html.= "</label>";
		}
		return $html;
	}
}

class SelectHTMLHandler extends BaseHTMLHandler
{
	function output($params,$name,$value,$option_values)
	{
		$option_labels = explode(';', $params["label"]);
		$options = array_combine($option_labels,$option_values);

		$html = '<select class="form-control"';
		$html.= $this->gen_input_attr("select",$params,$name,$value);
		$html.= '>';

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
