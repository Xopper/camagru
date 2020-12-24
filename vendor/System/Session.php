<?php


namespace System;

class Session
{
	/**
	 * not mendatory 7it maghadich ne7tajo les classes diyal App
	 * private $app;
	 *
	 * public function __construct(Application $app)
	 * {
	 *		$this->app = $app;
	 * }
	 */


	/**
	 * start a session
	 * 
	 * @return viod
	 */
	public function start()
	{
		ini_set("session.use_only_cookies", "1");
		if (session_id() == '')
			session_start();
	}

	/**
	 * Set the given key and value in the session super variable
	 * 
	 * @param string $key
	 * @param mixed $value
	 *
	 * @return void
	 */
	public function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}
	/**
	 * Get value from the given key in the session super variable
	 * 
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		return array_get($_SESSION, $key, $default);
	}

	/**
	 * Check if the given key exists in the session super variable
	 * 
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function has($key)
	{
		return (isset($_SESSION[$key]));
	}
	/**
	 * Remove the given key from the session super variable
	 * 
	 * @param string $key
	 *
	 * @return void
	 */
	public function remove($key)
	{
		unset($_SESSION[$key]);
	}

	/**
	 * get the value and remove it
	 */

	public function pull($key)
	{
		$value = $this->get($key);
		$this->remove($key);
		return $value;
	}

	/**
	 * Get keys stored in the session super variable
	 *
	 * @return array
	 */
	public function getAll()
	{
		if (isset($_SESSION))
			return $_SESSION;
		return array();
	}
	/**
	 * Destroy the session
	 * 
	 * @return void
	 */
	public function destroy()
	{
		session_destroy();
		unset($_SESSION);
	}
}
