<?php

namespace System\View;

use System\Application;

interface ViewInterface
{
	/**
	 * Get the ouput from view html file with its data
	 */
	public function getOutput();
	/**
	 * nothing till now to write
	 */
	public function __toString();
}
