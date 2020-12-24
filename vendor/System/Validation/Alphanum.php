<?php

namespace System\Validation;

use System\Application;

class Alphanum implements ValidationInterface
{
	private $app;
	private $name;
	private $param;
	public function __construct(Application $app, $name, $param = null)
	{
		$this->app = $app;
		$this->name = $name;
		$this->param = $param;
	}
	public function validate()
	{
		$re = '/^\w+$/m';
		if (!preg_match($re, $this->app->request->post($this->name))) {
			$className = str_replace(__NAMESPACE__ . '\\', '', __CLASS__);
			$class = lcfirst($className);
			if ($this->param == 'silent') {
				return $class . "|" . ucfirst($this->name) . " is not valid field.";
			}
			return $class . "|" . ucfirst($this->name) . " Use only Alpha numeric and _ characters.";
		}
		return "";
	}
}
