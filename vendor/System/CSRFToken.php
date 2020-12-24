<?php


namespace System;

class CSRFToken
{
	private $app;

	/**
	 * Constructor
	 * 
	 * @param \System\Application $app
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	/**
	 * Generate a CSRF token and stor it into session
	 * 
	 * @return string
	 */
	public function generateToken()
	{
		$token = rand_token(32);
		$this->app->session->set("token", $token);
		return $token;
	}

	/**
	 * check if token passed in the from match that saved in session
	 * 
	 * @return bool
	 */
	public function checkToken(): bool
	{
		$sessToken = $this->app->session->get("token");
		$formToken = $this->app->request->post("token");

		if ($sessToken === $formToken)
			return true;
		return false;
	}

	/**
	 * unset CSRF token afetr checking it
	 * 
	 * @return void
	 */
	public function unsetCSRFToken(): void
	{
		$this->app->session->remove("token");
	}
}
