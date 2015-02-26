<?php

class FormInput
{
	public $name;
	public $value;
	public $file_path;
	public $option_values = array();
	public function __construct($name, $file_path)
	{
		$this->name = $name;
		$this->set_file_path($file_path);
	}
	public function set_file_path($path)
	{
		//TODO: check
		$this->file_path = $path;

		if ( file_exists($path) )
		{
			$this->get_file_value();
		}
		else
		{
			$this->write_file();
		}
	}

	public function write_file()
	{
		file_put_contents($this->file_path, $this->value);
	}
	public function get_file_value()
	{
		$this->value = file_get_contents($this->file_path);
		return $this->value;
	}
	public function get_post_value()
	{
		//TODO: check
		$this->value = $_POST[$this->name];
		return $this->value;
	}
}
