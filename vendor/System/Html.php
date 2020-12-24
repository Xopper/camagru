<?php

namespace System;

class Html
{

	private $app;
	private $title;
	private $description;
	private $keywords;

	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}
	public function getTitle()
	{
		return $this->title;
	}
	public function setDescription($description)
	{
		$this->description = $description;
	}
	public function getDescription()
	{
		return $this->description;
	}
	public function setkeywords($keywords)
	{
		$this->keywords = $keywords;
	}
	public function getkeywords()
	{
		return $this->keywords;
	}
}
