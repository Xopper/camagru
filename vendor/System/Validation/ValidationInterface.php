<?php

namespace System\Validation;

use System\Application;

interface ValidationInterface
{
	public function __construct(Application $app, $name, $param = null);
	public function validate();
}
