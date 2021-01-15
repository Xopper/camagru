<?php

namespace App\Controllers\Auth;

use System\Controller;

class LogoutController extends Controller
{
	public function index()
	{
		if ($this->session->has('auth')) {
			$user = $this->session->get("auth");
			$userModel = $this->load->model("User");
			$userModel->unsetCookieOnDB($user->id);
			$this->session->remove('auth');
			$this->cookie->remove("remember");
			$this->session->set('flash', ['success' => "You are now logged out!"]);
		}
		$this->url->redirect("/");
	}
}
