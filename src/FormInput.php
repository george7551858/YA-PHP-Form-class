<?php

require_once 'HTMLHandler.php';

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

class FormInput_Text extends FormInput
{
	public function __construct($name, $file_path,$curd_handler=NULL,$html_handler=NULL)
	{
		$html_handler = ($html_handler) ? $html_handler : new TextHTMLHandler;
		parent::__construct($name, $file_path,$curd_handler,$html_handler);
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

class FormInput_Checkbox extends FormInput_Options
{
	public function __construct($name, $file_path, $option_values,$curd_handler=NULL,$html_handler=NULL)
	{
		$curd_handler = ($curd_handler) ? $curd_handler : new CheckboxCURDHandler;
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
			return trim($_POST[$name]);
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



