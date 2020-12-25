<?php

namespace System\Validation;

use System\Application;

class Unique implements ValidationInterface
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
	public function validate(): string
	{
		$dbTable = $this->param;
		$fieldVal = $this->app->request->post($this->name);

		// pre($this->app->session->get('auth'));
		// $auth = $this->app->session->get('auth');
		// die($fieldVal);
		// die($auth->{$this->name});
		if ($this->app->session->has('auth')) { // if he doesn't change anything
			$auth = $this->app->session->get('auth');
			// die($auth->{$this->name} === $fieldVal);
			if ($auth->{$this->name} == $fieldVal) {
				return "";
			}
		}
		// die();
		$this->app->db->select("*")->where("{$this->name} = ?", $fieldVal)->fetchAll($dbTable);
		$rowCount = $this->app->db->rows();
		if ($rowCount !== 0) {
			$className = str_replace(__NAMESPACE__ . '\\', '', __CLASS__);
			$class = lcfirst($className);
			return $class . "|" . ucfirst($this->name) . " field already exists!";
		}
		return "";
	}
}
