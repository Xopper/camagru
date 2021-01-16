<?php

namespace System\Http;

class Request
{
	private $url;

	private $baseUrl;

	/**
	 * the uploaded file container
	 * 
	 * @var array
	 */
	private $files = [];

	public function prepareUrl()
	{

		$scriptName =  dirname($this->server('SCRIPT_NAME'));
		$requstUri = $this->server('REQUEST_URI');
		if (strpos($requstUri, '?') !== FALSE) :
			list($requstUri, $queryStr) = explode('?', $requstUri);
		endif;
		if ($requstUri !== '/') :
			$this->url = rtrim(preg_replace('#^' . $scriptName . '$#', '', $requstUri), "/");
		else :
			$this->url = $requstUri;
		endif;
		$this->baseUrl = $this->server('REQUEST_SCHEME') . '://' . $this->server('HTTP_HOST') . $scriptName;
	}
	/**
	 * Get the given value from the golbal variable
	 * 
	 */
	public function server($key, $default = null)
	{
		return array_get($_SERVER, $key, $default);
	}
	public function get($key, $default = null)
	{
		return array_get($_GET, $key, $default);
	}
	public function post($key, $default = null, $flag = false)
	{
		if (!$flag){
			if (is_array($_POST[$key])){
				$_POST[$key] = "";
			}
		}
		return array_get($_POST, $key, $default);
	}
	public function method()
	{
		return $this->server('REQUEST_METHOD');
	}

	/**
	 * Get the uploaded file object for the given input
	 * 
	 * @param string $input
	 * @return \System\Http\Upload 
	 */
	public function file($input)
	{
		if (!isset($this->files[$input])){
			$uploadedFile = new Upload($input);
			$this->files[$input] = $uploadedFile;			
		}
		return $this->files[$input];
	}

	public function baseUrl()
	{
		return $this->baseUrl;
	}
	public function url()
	{
		return $this->url;
	}
}
