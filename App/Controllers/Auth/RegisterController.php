<?php

namespace App\Controllers\Auth;

use System\Controller;

class RegisterController extends Controller
{
	/**
	 * Manage GET requset
	 */
	public function index()
	{
		/**
		 * Reconnect from Cookies
		 */
		$userModel = $this->load->model("User");
		$userModel->reconnectFromCookie();

		if (!$this->session->has('auth')) {
			$this->html->setTitle("Register area | Camagru");
			$token = $this->csrf->generateToken();
			$data = ['token' => $token];
			$view = $this->view->render("Auth/RegisterView", $data);
			echo $this->CommonLayout->render($view);
		} else {
			$this->session->set('flash', ["danger" => "You're already logged in."]);
			$this->url->redirect("/");
		}
	}

	/**
	 * Manage POST requset
	 */
	public function submit()
	{
		$from_schema = ['_fName','_lName','_username','_email','_password','_conf-pass'];
		$errors = $this->validate->isValid($this->rules());
		$CSRFflag = $this->csrf->checkToken();
		if (empty($errors) && $CSRFflag) {
			$token = rand_token(60);
			$users = $this->load->model("User");
			$users->addUser(
				$this->request->post('fName'),
				$this->request->post('lName'),
				$this->request->post('username'),
				$this->request->post('password'),
				$this->request->post('email'),
				$token
			);
			$userId = $this->db->lastId();
			mail(
				$this->request->post('email'),
				'Confirm your registration',
				"Hey {$this->request->post('username')},\n\n Gratz for passing this step \n\n you can verify your account now click here <a href='" . url("/confirm/{$userId}/{$token}") . "'>Verify</a>");
			$this->session->set('flash', ['success' => 'Check your inbox mail.']);
			$json = json_encode([
				'ok' => true,
				'redirect' => '/',
				'csrf' => $CSRFflag,
			]);
			$this->csrf->unsetCSRFToken();
			echo $json;
		} else {
			echo $this->manageErrors($CSRFflag, $from_schema, $errors, $this->messages());
		}
	}

	/**
	 * @inheritDoc
	 */
	protected function rules():array
	{
		return [
			'fName'		=> 'required|validName|max:10',
			'lName'		=> 'required|validName|max:10',
			'username'	=> 'required|alphanum|max:12|unique:users',
			'email'		=> 'required|max:63|email|unique:users',
			'password'	=> 'required|max:32|validPassword',
			'conf-pass'	=> 'required|validPassword|same:password',
		];
	}

	/** 
	 * @inheritDoc
	*/
	protected function messages():array
	{
		return [
			'fName.required'			=> 'Please fill out first name field.',
			'lName.required'			=> 'Please fill out last name field.',
			'username.required'			=> 'Please fill out username field.',
			'email.required'			=> 'Please fill out email field.',
			'password.required'			=> 'Please fill out password field.',
			'conf-pass.required'		=> 'Please fill out password confirmation field.',

			'fName.validName'			=> 'Please insert a valid name.',
			'lName.validName'			=> 'Please insert a valid name.',

			'fName.max'					=> 'Fisrt name must be less than 10 characters.',
			'lName.max'					=> 'Last name must be less than 10 characters.',
			'username.max'				=> 'Username must be less than 12 characters.',
			'email.max'					=> 'A valid email must be less than 63 characters.',
			'password.max'				=> 'Password must be less than 32 characters.',

			'email.email'				=> 'Please insert a valid email.',
			'username.alphanum'			=> 'Use alphanumeric chars.',
			'password.validPassword'	=> 'Use [lower-Upper] case, special chars and numbers.',

			'conf-pass.validPassword'	=> 'Use [lower-Upper] case, special chars and numbers.',
			'conf-pass.same'			=> 'Please confirm your password correctly.',
		];
	}
}
