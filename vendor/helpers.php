<?php

use System\Application;

if (!function_exists('pre')) {
	function pre($var)
	{
		echo "<pre>";
		print_r($var);
		echo "</pre>";
	}
}
if (!function_exists('array_get')) {
	/**
	 * Get the value from the given array if isset
	 * otherwise get the default value
	 * 
	 * @param array $array
	 * @param string|int $key
	 * @param mixed $default
	 * @return mixed
	 */
	function array_get($array, $key, $default = null)
	{
		return isset($array[$key]) ? $array[$key] : $default;
	}
}
if (!function_exists('_e')) {
	/**
	 * Escape HTML and JS special characters
	 * 
	 * @param string $value
	 * @return string
	 */
	function _e($value)
	{
		return htmlspecialchars($value);
	}
}
if (!function_exists('assets')) {
	/**
	 * get the full link of assets
	 * 
	 * @param string $value
	 * @return string
	 */
	function assets($value)
	{
		$app = Application::getInstance();
		return $app->url->link($value);
	}
}

if (!function_exists('url')) {
	/**
	 * get full link of page
	 * 
	 * @param string $value
	 * @return string
	 */
	function url($value)
	{
		$app = Application::getInstance();
		return $app->url->link($value);
	}
}

if (!function_exists('redirectTo')) {
	/**
	 * get full link of page
	 * 
	 * @param string $value
	 * @return string
	 */
	function redirectTo($to)
	{
		$app = Application::getInstance();
		return $app->url->redirect($to);
	}
}


if (!function_exists('rand_token')) {
	/**
	 * Generate a random token with specific length
	 * 
	 * @param int $length length of the genarated token
	 * @return string
	 */
	function rand_token($length)
	{
		$alpha = "aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ0123456789";
		return substr(str_shuffle(str_repeat($alpha, $length)), 0, $length);
	}
}


if (!function_exists('passed')) {
	/**
	 * get the passed fields
	 * 
	 * @param array $form_schema
	 * @param array $errors
	 * @return array
	 */
	function passed($form_schema, $errors)
	{
		$passed = [];
		foreach ($form_schema as $key) {
			if (!array_key_exists($key, $errors)) {
				$passed[] = $key;
			}
		}
		return $passed;
	}
}
