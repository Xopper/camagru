<?php

namespace System;

class Route
{

	private $app;

	private $notFound;

	public $routes = array();
	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	public function add($url, $action, $requestMethod = 'GET')
	{
		// $url = !empty($this->app->request->scriptName()) ? $this->app->request->scriptName() . $url : $url;

		$route = [
			'url'		=> $url,
			'pattern'	=> $this->getGeneratedPattern($url),
			'action'	=> $this->getAction($action),
			'method'	=> strtoupper($requestMethod),
		];
		$this->routes[] = $route;

		// pre($this->routes);
		// die();
	}
	private function getGeneratedPattern($url)
	{
		$pattern = "#^";

		$pattern .= str_replace(
			[':text', ':id', ':token'],
			['([a-zA-Z0-9-]+)', '(\d+)', '([a-zA-Z0-9]{60})'],
			$url
		);

		$pattern .= "$#";
		return $pattern;
	}
	private function getAction($action)
	{
		$action = str_replace('/', '\\', $action); // to fit with namespace
		return (strpos($action, '@') !== FALSE) ? $action : $action . "@index";
	}
	public function notFound($url)
	{
		$this->notFound = $url;
	}
	private function isMatching($pattern, $method)
	{
		return preg_match($pattern, $this->app->request->url()) && ($this->app->request->method() === $method);
	}
	public function getProperRoute()
	{
		foreach ($this->routes as $route) {
			if ($this->isMatching($route['pattern'], $route['method'])) {
				//pre($this->routes);
				$arguments = $this->getArgumentsFrom($route['pattern']);
				list($controller, $action) = explode('@', $route['action']);
				return array($controller, $action, $arguments);
			}
		}
		return $this->app->url->redirect($this->notFound);
	}
	private function getArgumentsFrom($pattern)
	{
		preg_match($pattern, $this->app->request->url(), $matches);
		array_shift($matches);
		return $matches;
	}
}
