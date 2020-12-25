<?php

namespace System\Validation;

use System\Application;

class ValidName implements ValidationInterface
{
	private $app;
	private $name;
	public function __construct(Application $app, $name, $param = null)
	{
		$this->app = $app;
		$this->name = $name;
	}
	public function validate()
	{
		$re = '/^([a-zA-Z ])+$/m';
		if (!preg_match($re, $this->app->request->post($this->name))) {
			$className = str_replace(__NAMESPACE__ . '\\', '', __CLASS__);
			$class = lcfirst($className);
			return $class . "|" . ucfirst($this->name) . " field is not a valid name.";
		}
		return "";
	}
}
