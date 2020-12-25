<?php


namespace System\Validation;

use System\Application;

class Email implements ValidationInterface
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
		$re = '/^([a-z._0-9-]+)@([a-z0-9]+[.]?)*([a-z0-9])(\.[a-z]{2,4})$/mi';
		if (!preg_match($re, $this->app->request->post($this->name), $matche)) {
			$className = str_replace(__NAMESPACE__ . '\\', '', __CLASS__);
			$class = lcfirst($className);
			return $class . "|" . ucfirst($this->name) . " is not valid.";
		}
		return "";
	}
}
