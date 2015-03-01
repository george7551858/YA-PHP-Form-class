<?php

class FormInput
{
	public $name;
	public $value;
	// public $file_path;
	private $curd_handler;
	// private $html_handler;
	public function __construct($name, $file_path ,$curd_handler=NULL)
	{
		$this->name = $name;
		$this->curd_handler = ($curd_handler) ? $curd_handler : new BaseCURDHandler($file_path);
		// $this->html_handler = ($html_handler) ? $html_handler : new BaseHTMLHandler;

		$this->value = $this->curd_handler->read();
	}

	public function save()
	{
		$this->value = $this->curd_handler->get_post_value($this->name);
		$this->curd_handler->update($this->value);
	}
}


class FormInput_Options extends FormInput
{
	public $option_values = array();
	public function __construct($name, $file_path, $option_values,$curd_handler=NULL)
	{
		$this->option_values = $option_values;

		parent::__construct($name, $file_path ,$curd_handler);
	}
}

class FormInput_Check extends FormInput_Options
{
	public function __construct($name, $file_path, $option_values,$curd_handler=NULL)
	{
		$curd_handler = ($curd_handler) ? $curd_handler : new CheckboxCURDHandler($file_path);
		$curd_handler->option_values = $option_values;
		parent::__construct($name, $file_path, $option_values,$curd_handler);
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
