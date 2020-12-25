<?php

namespace System;

class Loader
{
	private $app;
	private $controllers = array();
	private $models = array();

	public function __construct(Application $app)
	{
		$this->app = $app;
	}
	public function action($controller, $method, array $args)
	{
		$obj = $this->controller($controller);
		call_user_func_array([$obj, $method], $args);
	}
	/**
	 * Loading Controllers
	 */
	public function controller($controller)
	{
		$controller = $this->setControllerName($controller);
		if (!$this->hasController($controller)) {
			$this->addController($controller);
		}
		return $this->getController($controller);
	}
	private function hasController($controller)
	{
		return array_key_exists($controller, $this->controllers);
	}
	private function addController($controller): void
	{
		$object = new $controller($this->app);
		$this->controllers[$controller] = $object;
	}
	private function getController($controller)
	{
		return $this->controllers[$controller];
	}
	private function setControllerName($controller): string
	{
		$controller .= "Controller";
		$controller = "App\\Controllers\\" . $controller;
		return str_replace("/", "\\", $controller);
	}
	/**
	 * Loading Models
	 */
	public function model($model)
	{
		$model = $this->setModelName($model);
		if (!$this->hasModel($model)) {
			$this->addModel($model);
		}
		return $this->getModel($model);
	}
	private function hasModel($model): bool
	{
		return array_key_exists($model, $this->models);
	}
	private function addModel($model): void
	{
		$object = new $model($this->app);
		$this->models[$model] = $object;
	}
	private function getModel($model)
	{
		return $this->models[$model];
	}
	private function setModelName($model): string
	{
		$model .= "Model";
		$model = "App\\Models\\" . $model;
		return str_replace("/", "\\", $model);
	}
}
