<?php

namespace App\Controllers\Auth;

use System\Controller;

class ConfirmController extends Controller
{
	public function index($id, $token)
	{
		if ($this->session->has('auth')) {
			/**
			 * Try to redirect the user to the HOME page
			 */
			$this->session->set('flash', ["danger" => "you're Alredy logged in .. try to logout first"]);
			$this->url->redirect("/");
		} else {
			/**
			 * try to reconncet from Cookie
			 */
			$userModel = $this->load->model("User");
			$userModel->reconnectFromCookie();

			$flag = $userModel->verify($id, "confirmation_token", $token);
			if ($flag === true) {
				$userModel->confirmUser($id);
				/**
				 * //TODO
				 * save ID's infos in $_SESSION and redirect the user to home page
				 * save that msg flush to $_SESSION and print it into Home page
				 *  => whatever the $flag is true or not
				 */
				// echo "ok your account has been verified";
				$this->session->set('flash', ['success' => 'ok your account has been verified']);
				$this->url->redirect("/");
			} else {
				/**
				 * we will come back to this later TODO
				 */
				echo "hhhh dazet ";
			}
		}
	}
}
