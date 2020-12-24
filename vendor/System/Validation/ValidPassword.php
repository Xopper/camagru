<?php

namespace System\Validation;

use System\Application;

class ValidPassword implements ValidationInterface
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
		$re = '/(?=.{8,32})(?=.*[A-Z])(?=.*[a-z])(?=.*\d).*$/m';
		if (!preg_match($re, $this->app->request->post($this->name))) {
			$className = str_replace(__NAMESPACE__ . '\\', '', __CLASS__);
			$class = lcfirst($className);
			if ($this->param == 'silent') {
				return $class . "|" . ucfirst($this->name) .  " is not valid field.";
			}
			return $class . "|" . ucfirst($this->name) . " field is is not a valid password.";
		}
		return "";
	}
}
