<?php


namespace System\Validation;

use System\Application;

class Required implements ValidationInterface
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
		$re = '/^(\s)+$/m';
		$fieldVal = $this->app->request->post($this->name);
		if (strlen($fieldVal) === 0 OR preg_match($re, $fieldVal)) {
			$className = str_replace(__NAMESPACE__ . '\\', '', __CLASS__);
			$class = lcfirst($className);
			return $class . "|" . ucfirst($this->name) . " is required field.";
		}
		return "";
	}
}
