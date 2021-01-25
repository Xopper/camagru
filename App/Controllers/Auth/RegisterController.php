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
		$from_schema = ['_fName', '_lName', '_username', '_email', '_password', '_conf-pass'];
		$errors = $this->validate->isValid($this->rules());
		$CSRFflag = $this->csrf->checkToken();
		if (empty($errors) && $CSRFflag) {
			$token = rand_token(60);
			$userModel = $this->load->model("User");
			$userModel->addUser(
				$this->request->post('fName'),
				$this->request->post('lName'),
				$this->request->post('username'),
				$this->request->post('password'),
				$this->request->post('email'),
				$token
			);
			$userId = $this->db->lastId();
			$userModel->sendConfMail($this->request->post('email'), $this->request->post('username'), $userId, $token);
			$this->session->set('flash', ['success' => 'Check your inbox mail.']);
			$json = json_encode([
				'ok' => true,
				'redirect' => url("/"),
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
	protected function rules(): array
	{
		return [
			'fName'		=> 'required|validName|min:3|max:10',
			'lName'		=> 'required|validName|min:3|max:10',
			'username'	=> 'required|alphanum|min:3|max:12|unique:users',
			'email'		=> 'required|max:63|email|unique:users',
			'password'	=> 'required|min:8|validPassword',
			'conf-pass'	=> 'required|min:8|validPassword|same:password',
		];
	}

	/** 
	 * @inheritDoc
	 */
	protected function messages(): array
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

			'fName.max'					=> 'Fisrt name must be less than or equal to 10 characters.',
			'lName.max'					=> 'Last name must be less than or equal to 10 characters.',
			'username.max'				=> 'Username must be less than or equal to 12 characters.',
			'email.max'					=> 'A valid email must be less than or equal to 63 characters.',

			'fName.min'					=> 'Fisrt name must be at least 3 characters.',
			'lName.min'					=> 'Last name must be at least 3 characters.',
			'username.min'				=> 'Username must be at least 3 characters.',
			'password.min'				=> 'Password must be at least 8 characters.',
			'conf-pass.min'				=> 'Password must be at least 8 characters.',

			'email.email'				=> 'Please insert a valid email.',
			'username.alphanum'			=> 'Use alphanumeric chars.',
			'password.validPassword'	=> 'Use [lower-Upper] case, special chars and numbers.',

			'conf-pass.validPassword'	=> 'Use [lower-Upper] case, special chars and numbers.',
			'conf-pass.same'			=> 'Please confirm your password correctly.',
		];
	}
}
