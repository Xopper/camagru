<?php

namespace System\View;

use System\Application;

class ViewFactory
{
	private $app;

	public function __construct(Application $app)
	{
		$this->app = $app;
	}
	/**
	 * Render the given view path with the passed variables 
	 * and generate new viewInterface object
	 * 
	 * @param string $viewPath
	 * @param array $data
	 * @return \System\View\ViewInterface
	 */
	public function render($viewPath, array $data = array())
	{
		return new View($this->app->file, $viewPath, $data);
	}
}
