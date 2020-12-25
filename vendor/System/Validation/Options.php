<?php

namespace System\Validation;

use \System\Application as app;
use \System\DatabaseHandler as db;

class Options implements ValidationInterface
{
	protected $name;
	protected $value;
	protected $options;

	public function __construct($name, $value, $options = array())
	{
		$this->name = $name;
		$this->value = $value;
		$this->options = $options;
	}
	public function validate()
	{
		if (isset($this->options['matches'])) {
			list($fieldName, $toMatch) = $this->options['matches'];
			if ($toMatch != $this->value)
				$errors[] = "{$this->name} field doesn't match {$fieldName}";
		}
		if (isset($this->options['minLen']) and isset($this->options['maxLen'])) {
			if (($this->options['minLen'] > strlen($this->value)) or $this->options['maxLen'] < strlen($this->value))
				$errors[] = "{$this->name} field must be between {$this->options['minLen']} and {$this->options['maxLen']} characters";
		}
		// if (isset($this->options['unique'])) {
		// 	$db = new db();
		// }
		// pre($errors);
		// die();
		return $errors;
	}
}

/**
 * extarct options :
 * options [
 * 	"matches"	=> [pass , hashed_pass],
 * 	"minLen"	=> 6,
 * 	"maxLen"	=> 63,
 * ];
 * 
 */
