<?php

namespace System\Validation;

use \System\Application;

class Validator
{
	private $app;
	public function __construct(Application $app)
	{
		$this->app = $app;
	}
	/**
	 * @param array $request the given inputs and their rules to follow
	 * @return array
	 */
	public function isValid($request)
	{
		$errors = array();
		foreach ($request as $key => $value) {
			$rules = explode("|", $value);
			foreach ($rules as $rule) {
				if (strpos($rule, ":")) {
					$properRule = explode(":", $rule);
					list($properRule, $param) = $properRule;
					$class = trim(Validator::class, "Validator") . ucwords($properRule);
					$error = (new ValidationStrategy(new $class($this->app, $key, $param)))->validate();
				} else {
					$properRule = $rule;
					$class = trim(Validator::class, "Validator") . ucwords($properRule);
					$error = (new ValidationStrategy(new $class($this->app, $key)))->validate();
				}
				if ($error) {
					$errors["_" . $key] = $error;
					break;
				}
			}
		}
		return $errors;
	}
}
