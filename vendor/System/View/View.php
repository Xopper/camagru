<?php

namespace System\View;

use System\File;

class View implements ViewInterface
{
	private $file;
	private $data = array();
	private $viewPath;
	private $output;

	public function __construct(File $file, $viewPath, array $data)
	{
		$this->file = $file;
		$this->preparePath($viewPath);
		$this->data = $data;
	}
	/**
	 * {@inheritDoc}
	 */
	public function getOutput() // try to understund ob_family
	{
		if (is_null($this->output)) {
			ob_start();
			extract($this->data);
			require_once $this->viewPath;
			$this->output = ob_get_clean();
		}
		return $this->output;
	}
	/**
	 * {@inheritDoc}
	 */
	public function __toString()
	{
		return $this->getOutput();
	}
	private function preparePath($viewPath)
	{
		$relativeViewPath = "App/Views/" . $viewPath . ".php";
		$this->viewPath = $this->file->to($relativeViewPath);
		if (!$this->viewFileExists($relativeViewPath)) {
			die("<b>" . $viewPath . "</b> Does not exist");
		}
	}
	private function viewFileExists($viewPath)
	{
		return $this->file->exists($viewPath);
	}
}
