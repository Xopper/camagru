<?php


namespace System;

class Url
{
	private $app;

	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	public function link($to)
	{
		return $this->app->request->baseUrl() . trim($to, "/");
	}
	public function redirect($to)
	{
		header("Location:" . $this->link($to));
		exit();
	}
}
