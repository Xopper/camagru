<?php

namespace System;

use Closure;
use System\Config\Setup;

class Application
{
	private $container = array();

	private static $instance;

	private function __construct(File $file)
	{
		$this->share('file', $file);
		$this->registerClasses();
		$this->loadHelpers();
	}

	/**
	 * Singletons should not be cloneable.
	 */
	private function __clone()
	{
	}

	public static function getInstance(File $file = null)
	{
		if (is_null(static::$instance)) {
			static::$instance = new static($file);
		}
		return static::$instance;
	}
	/**
	 * run the App
	 * 
	 * @return void
	 */
	public function run()
	{
		require __DIR__ . "/Config/database.php";
		
		$this->session->start();

		$upsFile = __DIR__ . "/../../public/ups";
		$useRpics = __DIR__ . "/../../public/userpics";

		if (!is_dir($upsFile)){
			mkdir($upsFile);
			chmod($upsFile, 0777); 
		}

		if (!is_dir($useRpics)){
			mkdir($useRpics);
			chmod($useRpics, 0777); 
		} 

		new Setup($DB_NAME);
		
		$this->request->prepareUrl();
		$this->file->call('App/index.php');
		list($controller, $method, $args) = $this->route->getProperRoute();
		$this->load->action($controller, $method, $args);
	}

	/**
	 * Load all called classes using spl autload function
	 * @return void
	 */
	private function registerClasses()
	{
		spl_autoload_register([$this, 'load']);
	}
	/**
	 * hepler function used to load classes
	 * 
	 * @return void
	 */
	public function load($class)
	{
		if (strpos($class, "App") === 0) {
			$file = $class . ".php";
		} else {
			$file = $this->file->toVendor($class . ".php");
		}
		if ($this->file->exists($file)) {
			$this->file->call($file);
		}
	}

	public function loadHelpers()
	{
		$this->file->call($this->file->toVendor("helpers.php"));
	}

	public function share($key, $value)
	{
		if ($value instanceof Closure){
			$value = call_user_func($value, $this);
		}
		$this->container[$key] = $value;
	}

	private function coreClasses()
	{
		return [
			'request'		=> '\\System\\Http\\Request',
			'response'		=> '\\System\\Http\\Response',
			'view'			=> '\\System\\View\\ViewFactory',
			'route'			=> '\\System\\Route',
			'session'		=> '\\System\\Session',
			'cookie'		=> '\\System\\Cookie',
			'load'			=> '\\System\\Loader',
			'html'			=> '\\System\\Html',
			'url'			=> '\\System\\Url',
			'db'			=> '\\System\\DatabaseHandler',
			'validate'		=> '\\System\\Validation\\Validator',
			'csrf'			=> '\\System\\CSRFToken',
		];
	}

	private function isSharing($key)
	{
		return isset($this->container[$key]);
	}

	private function isCoreAlias($alias)
	{
		return array_key_exists($alias, $this->coreClasses());
	}

	/**
	 * Create new instance of the given alias
	 * 
	 * @param string $alias
	 * @return object
	 */
	private function createNewCoreObject($alias)
	{
		$coreClasses = $this->coreClasses();
		$object = $coreClasses[$alias];
		return new $object($this);
	}

	/**
	 * We gonna use it other Classes [.e.g Controller Class] 
	 * so thats why we didn't use it directely
	 * in __get() Magic Methode .. it works very well we tried it before 
	 */
	public function get($key)
	{
		if (!$this->isSharing($key)) {
			if ($this->isCoreAlias($key)) {
				$this->share($key, $this->createNewCoreObject($key));
			} else {
				die("<b>" . $key . "</b> Key not found in App container");
			}
		}
		return $this->container[$key];
	}

	/**
	 * Generate a new object
	 */
	public function __get($key)
	{
		return ($this->get($key));
	}
}
