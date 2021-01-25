<?php

namespace System\Http;

class Request
{
	/**
	 * Script name
	 * 
	 * @var string
	 */
	private $scriptName;

	/**
	 * Url
	 * 
	 * @var string
	 */
	private $url;

	/**
	 * Base url
	 * 
	 * @var string
	 */
	private $baseUrl;

	/**
	 * the uploaded file container
	 * 
	 * @var array
	 */
	private $files = [];

	/**
	 * Parse the request url
	 */
	public function prepareUrl()
	{
		$this->scriptName = rtrim(dirname($this->server('SCRIPT_NAME')), "/");
		$requstUri = $this->server('REQUEST_URI');

		if (strpos($requstUri, '?') !== FALSE) :
			list($requstUri, $queryStr) = explode('?', $requstUri);
		endif;

		$this->url = rtrim(preg_replace('#^' . $this->scriptName . '#', '', $requstUri), "/");

		if ($this->url == "") :
			$this->url = "/";
		endif;

		$this->baseUrl = $this->server('REQUEST_SCHEME') . '://' . $this->server('HTTP_HOST') . $this->scriptName;
	}

	/**
	 * Get the given value from the golbal variable
	 * 
	 */
	public function server($key, $default = null)
	{
		return array_get($_SERVER, $key, $default);
	}

	/**
	 * Get the given value from the Get super variable
	 * 
	 * @param mixed $key
	 * @param mixed $default
	 * 
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		return array_get($_GET, $key, $default);
	}

	/**
	 * the given value from the POST super variable
	 * 
	 * @param mixed $key
	 * @param mixed $default
	 * @param bool $flag
	 * 
	 * @return mixed
	 */
	public function post($key, $default = null, $flag = false)
	{
		if (!$flag) {
			if (is_array($_POST[$key])) {
				$_POST[$key] = "";
			}
		}
		return array_get($_POST, $key, $default);
	}

	/**
	 * Get the request Method
	 * 
	 * @return string
	 */
	public function method()
	{
		return $this->server('REQUEST_METHOD');
	}

	/**
	 * Get the uploaded file object for the given input
	 * 
	 * @param string $input
	 * 
	 * @return \System\Http\Upload 
	 */
	public function file($input)
	{
		if (!isset($this->files[$input])) {
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
