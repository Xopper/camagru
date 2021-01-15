<?php


namespace System\Validation;

use System\Application;

class Min implements ValidationInterface
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
		$fieldVal = $this->app->request->post($this->name);
		if (strlen($fieldVal) < $this->param) {
			$className = str_replace(__NAMESPACE__ . '\\', '', __CLASS__);
			$class = lcfirst($className);
			return $class . "|" . ucfirst($this->name) . " must be at least {$this->param} characters.";
		}
		return "";
	}
}
