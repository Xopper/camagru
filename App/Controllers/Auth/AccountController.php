<?php


namespace App\Controllers\Auth;

use System\Controller;

class AccountController extends Controller
{
	public function index()
	{
		/**
		 * Reconnect from Cookies
		 */
		$userModel = $this->load->model("User");
		$userModel->reconnectFromCookie();

		if (!$this->session->has('auth')) {
			$this->session->set('flash', ["danger" => "You're not allowed to explore this page."]);
			$this->url->redirect("/");
		} else {
			$this->html->setTitle('Account settings area | Camagru');
			$token = $this->csrf->generateToken();
			$data = [
				'token' => $token,
				'auth'	=> $this->session->get("auth"),
			];
			$view = $this->view->render("Auth/AccountView", $data);
			echo $this->CommonLayout->render($view);
		}
	}
	public function submit()
	{
		$from_schema = ['_fName', '_lName', '_username', '_email', '_password'];
		$errors = $this->validate->isValid($this->rules());
		$CSRFflag = $this->csrf->checkToken();
		if (empty($errors) && $CSRFflag) {
			$user = $this->session->get('auth');
			if (password_verify($this->request->post("password"), $user->password)) {
				$userModel = $this->load->model("User");
				$notif = $this->request->post("notification_on") ? 1 : 0;
				$userModel->updateUser(
					$user->id,
					$this->request->post("fName"),
					$this->request->post("lName"),
					$this->request->post("username"),
					$this->request->post("password"),
					$this->request->post("email"),
					$notif
				);
				$userModel->updateSess();
				$this->session->set('flash', ["success" => "Your account infos has been updated successfully."]);
				$json = json_encode([
					'ok' => true,
					'csrf' => $CSRFflag,
					'redirect' => url('/account'),
				]);
				echo $json;
			} else {
				$this->session->set('flash', ['danger' => 'Invalid password.']);
				$json = json_encode([
					'ok' => true,
					'csrf' => $CSRFflag,
					'redirect' => url('/account'),
				]);
				echo $json;
			}
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
			'fName'			=> 'required|validName|min:3|max:10',
			'lName'			=> 'required|validName|min:3|max:10',
			'username'		=> 'required|alphanum|min:3|max:12|unique:users',
			'email'			=> 'required|max:63|email|unique:users',
			'password'		=> 'required|min:8|validPassword',
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

			'fName.validName'			=> 'Please insert a valid name.',
			'lName.validName'			=> 'Please insert a valid name.',

			'fName.max'					=> 'Fisrt name must be less than or equal to 10 characters.',
			'lName.max'					=> 'Last name must be less than or equal to 10 characters.',
			'username.max'				=> 'Username must be less than or equal to 12 characters.',
			'email.max'					=> 'A valid email must be less than or equal to 63 characters.',

			'fName.min'					=> 'Fisrt name must be at least 3 characters.',
			'lName.min'					=> 'Last name must be at least 3 characters.',
			'username.min'				=> 'Username must be at least 3 characters.',
			'password.min'				=> 'Username must be at least 8 characters.',

			'email.email'				=> 'Please insert a valid email.',
			'username.alphanum'			=> 'Use alphanumeric chars.',
			'password.validPassword'	=> 'Invalid password.',
		];
	}
}
