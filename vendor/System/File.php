<?php

namespace System;


class File
{
	const DS = DIRECTORY_SEPARATOR;

	public $root;

	public function __construct($root)
	{
		$this->root = $root;
	}
	public function exists($file)
	{
		return file_exists($this->to($file));
	}

	public function call($file)
	{
		return require $this->to($file);
	}

	/**
	 * Get the absolute path in vendor folder
	 */
	public function toVendor($path)
	{
		return "vendor" . static::DS . $path;
	}

	/**
	 * Get the absolute path of the given path variable
	 * 
	 * @var string $path
	 * @return
	 */

	public function to($path)
	{
		return ($this->root . static::DS . str_replace(['/', '\\'], static::DS, $path));
	}
}
