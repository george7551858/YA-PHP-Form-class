<?php
define('CHECKBOX_YES', 'yes');


class FormInput
{
	public $name;
	public $value;
	public $type;
	public $option_values;

	protected $curd_handler;

	public function __construct($name, $file_path, $options=NULL,BaseCURDHandler $curd_handler=NULL)
	{
		$this->name = $name;
		$this->option_values = $options;
		$this->curd_handler = ($curd_handler) ? $curd_handler : new BaseCURDHandler;
		
		$this->type = strtolower(substr(get_class($this), 10));
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
}

class FormInput_Text extends FormInput
{
}

class FormInput_Checkbox extends FormInput
{
	public function __construct($name, $file_path, $options,CheckboxCURDHandler $curd_handler=NULL)
	{
		$curd_handler = ($curd_handler) ? $curd_handler : new CheckboxCURDHandler;
		$curd_handler->option_values = $options;
		parent::__construct($name, $file_path, $options,$curd_handler);
	}
}

class FormInput_Radio extends FormInput
{
}

class FormInput_Select extends FormInput
{
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
	public function get_post_value($name)
	{
		$value = parent::get_post_value($name);
		$chk_bool = ($value === CHECKBOX_YES) ? 1 : 0;

		return $this->option_values[$chk_bool];
	}
}



