<?php


namespace System;

class Cookie
{
	private $app;

	public function __construct(Application $app)
	{
		$this->app = $app;
	}
	/**
	 * set cookie
	 * @param string $key
	 * @param mixed $value
	 * @param int $time in hours // set how many hours do you want ta save this cookie
	 * 
	 * @return void
	 */
	public function set($key, $value, $time): void
	{
		setcookie($key, $value, time() + $time * 3600, "", "", "", true);
	}
	/**
	 * get the cookie key's value
	 */
	public function get($key, $default = null)
	{
		return array_get($_COOKIE, $key, $default);
	}
	public function has($key): bool
	{
		return array_key_exists($key, $_COOKIE);
	}
	public function remove($key): void
	{
		setcookie($key, null, time() - 3600 * 24 * 30, "", "", "", true);
		unset($_COOKIE[$key]);
	}
	public function getAll(): array
	{
		if (isset($_COOKIE))
			return $_COOKIE;
		return array();
	}
	public function destroy(): void
	{
		$allCookies = $this->getAll();
		foreach (array_keys($allCookies) as $key) {
			$this->remove($key);
		}
		unset($_COOKIE);
	}
}
