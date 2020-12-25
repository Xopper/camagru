<?php

namespace System\Validation;

use System\Application;

class Same implements ValidationInterface
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
		$fieldVal = $this->app->request->post($this->name); // i.e $_POST['vEmail']
		$sameAs = $this->param;
		$sameLike = $this->app->request->post($this->param);
		if ($fieldVal !== $sameLike) {
			$className = str_replace(__NAMESPACE__ . '\\', '', __CLASS__);
			$class = lcfirst($className);
			return $class . "|" . ucfirst($this->name) . " field doesn't match {$sameAs}.";
		}
		return "";
	}
}
