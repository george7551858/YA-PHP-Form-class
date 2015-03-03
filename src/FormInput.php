<?php

class FormInput
{
	public $name;
	public $value;
	protected $curd_handler;
	protected $html_handler;
	public function __construct($name, $file_path ,$curd_handler=NULL,$html_handler=NULL)
	{
		$this->name = $name;
		$this->curd_handler = ($curd_handler) ? $curd_handler : new BaseCURDHandler;
		$this->html_handler = ($html_handler) ? $html_handler : new BaseHTMLHandler;

		$this->value = $this->curd_handler->init($file_path);
	}

	public function load_post()
	{
		$tmp_value = $this->curd_handler->get_post_value($this->name);
		if( is_null($tmp_value) ) return;
		$this->value = $tmp_value;
	}
	public function save($value=NULL)
	{
		if( is_null($value) ) $value = $this->value;
		$this->curd_handler->update($value);
	}
	public function html(&$params,$style)
	{
		if( ! isset($params['id']) ) $params['id'] = $this->name;
		return $this->html_handler->output($params,$style,$this->name,$this->value);
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
	public function html(&$params,$style)
	{
		if( ! isset($params['id']) ) $params['id'] = $this->name;
		return $this->html_handler->output($params,$style,$this->name,$this->value,$this->option_values);
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
	public function init($storage="")
	{
		if(!$storage) return;
		$this->storage = $storage;
		if ( !file_exists($this->storage) ) $this->create();
		return $this->read();
	}
	public function create($storage=NULL)
	{
		if (!$storage) $storage = $this->storage;
		mkdir(dirname($storage), 0755, true);
		touch($storage);
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
		$chk_bool = ($value === $this->option_values[1]) ? 1 : 0;
		return $chk_bool;
	}
}


class BaseHTMLHandler
{
	function escape_special_chars($string)
	{
		if (!is_array($string)) {
			$string = htmlspecialchars($string, ENT_COMPAT, 'UTF-8', false);
		}

		return $string;
	}

	function gen_input_attr($type,$params,$name,$value,$option_values="")
	{
		$attr = array();
		$attr["name"] = $name;
		
		if ($type === "checkbox") {
			$attr["value"] = $option_values[1];
		}
		else if ($type === "text") {
			$attr["value"] = $value;
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
	function output($params,$style,$name,$value)
	{
		$format = $style['text'];
		$attr = $this->gen_input_attr("text",$params,$name,$value);
		$html = sprintf($format, $attr);
		return $html;
	}
}

class CheckboxHTMLHandler extends BaseHTMLHandler
{
	function output($params,$style,$name,$value,$option_values)
	{
		$format = $style['checkbox'];

		$attr = $this->gen_input_attr("checkbox",$params,$name,$value,$option_values);
		if( $value === 1 ) $attr.= " checked";

		$label = $this->escape_special_chars(@$params['label']);
		$html = sprintf($format, $attr, $label);
		return $html;
	}
}

class RadioHTMLHandler extends BaseHTMLHandler
{
	function output($params,$style,$name,$value,$option_values)
	{
		$format = $style['radio'];

		$options = $option_values;
		$option_labels = explode(';', @$params["label"]);
		if( count($option_labels) === count($option_values)) {
			$options = array_combine($option_labels,$option_values);
		}

		$html = '';
		$name = $this->escape_special_chars($name);
		foreach ($options as $label => $_val) {
			$str = '';
			$str.= ' name="'.$name.'"';
			$str.= ' value="'.$this->escape_special_chars($_val).'"';
			if( $value == $_val) $str.= " checked";
			$label = $this->escape_special_chars($label);
			$html.= sprintf($format["repeat"], $str, $label);
		}

		$html = sprintf($format["wrapper"], $html);

		return $html;
	}
}

class SelectHTMLHandler extends BaseHTMLHandler
{
	function output($params,$style,$name,$value,$option_values)
	{
		$format = $style['select'];

		$options = $option_values;
		$option_labels = explode(';', @$params["label"]);
		if( count($option_labels) === count($option_values)) {
			$options = array_combine($option_labels,$option_values);
		}

		$html = "";
		foreach ($options as $label => $_val) {
			$str = ' value="'.$this->escape_special_chars($_val).'"';
			if( $value == $_val) $str.= " selected";
			$label = $this->escape_special_chars($label);
			$html.= sprintf($format["repeat"], $str, $label);
		}

		$attr = $this->gen_input_attr("select",$params,$name,$value,$option_values);
		$html = sprintf($format["wrapper"], $attr, $html);

		return $html;
	}
}
