<?php
require_once 'FormInput.php';

class BaseHTMLHandler
{
	function escape_special_chars($string)
	{
		if (!is_array($string)) {
			$string = htmlspecialchars($string, ENT_COMPAT, 'UTF-8', false);
		}

		return $string;
	}

	function gen_input_attr($params,$name,$option_values="")
	{
		$attr = array();
		$attr["name"] = $name;

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
	function output()
	{
	}
}

class TextHTMLHandler extends BaseHTMLHandler
{
	function output($params,$format,FormInput $fi)
	{
		$name  = $fi->name;
		$value = $fi->value;

		$attr = $this->gen_input_attr($params,$name);
		$attr.= ' value="'. $this->escape_special_chars($value) .'"';

		$html = sprintf($format, $attr);
		return $html;
	}
}

class CheckboxHTMLHandler extends BaseHTMLHandler
{
	function output($params,$format,FormInput $fi)
	{
		$name  = $fi->name;
		$value = $fi->value;
		$option_values = $fi->properties['option_elements'];

		$attr = $this->gen_input_attr($params,$name,$option_values);
		$attr.= ' value="'. $this->escape_special_chars(CHECKBOX_YES) .'"';

		if( $value === $option_values[1] ) $attr.= " checked";

		$label = $this->escape_special_chars(@$params['label']);
		$html = sprintf($format, $attr, $label);
		return $html;
	}
}

class RadioHTMLHandler extends BaseHTMLHandler
{
	function output($params,$format,FormInput $fi)
	{
		$name  = $fi->name;
		$value = $fi->value;
		$option_values = $fi->properties['option_elements'];

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
	function output($params,$format,FormInput $fi)
	{
		$name  = $fi->name;
		$value = $fi->value;
		$option_values = $fi->properties['option_elements'];

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

		$attr = $this->gen_input_attr($params,$name,$option_values);
		$html = sprintf($format["wrapper"], $attr, $html);

		return $html;
	}
}