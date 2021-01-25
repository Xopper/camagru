<?php


namespace App\Controllers\Auth;

use System\Controller;

class LoginController extends Controller
{
	public function index()
	{
		/**
		 * Reconnect from Cookies
		 */
		$userModel = $this->load->model("User");
		$userModel->reconnectFromCookie();

		if (!$this->session->has('auth')) {
			$this->html->setTitle('Access area | Camagru');
			$token = $this->csrf->generateToken();
			$data = ['token' => $token];
			$view = $this->view->render("Auth/LoginView", $data);
			echo $this->CommonLayout->render($view);
		} else {
			/**
			 * Try to redirect the logged user to the HOME page
			 */
			$this->session->set('flash', ["danger" => "You're already logged in."]);
			$this->url->redirect("/");
		}
	}
	public function submit()
	{
		$from_schema = ['_username', '_password'];
		$errors = $this->validate->isValid($this->rules());
		$CSRFflag = $this->csrf->checkToken();
		if (empty($errors) && $CSRFflag) {
			$userModel = $this->load->model("User");
			$user = $userModel->validateLog($this->request->post("username"), $this->request->post("password"));
			if ($user) {
				if ($this->request->post("remember")) {
					$token = rand_token(255);
					$userModel->setCookieOnDB($user->id, $token);
					$random = $user->id . "==" . $token . sha1($user->id . "Future Is Loading");
					$this->cookie->set("remember", $random, 24 * 7); // a week
					$user = $userModel->getById($user->id); // refresh user infos
				}
				$this->session->set('auth', $user);
				$this->session->set('flash', ['success' => 'Welcome back.']);
				$json = json_encode([
					'ok' => true,
					'redirect' => url('/'),
					'csrf' => $CSRFflag,
				]);
				$this->csrf->unsetCSRFToken();
				echo $json;
			} else {
				$json = json_encode([
					'ok' => false,
					'errors' => [
						'_username' => 'Bad combination of username/password.',
						'_password' => 'Bad combination of username/password.',
					],
					'passed' => '',
					'csrf' => $CSRFflag,
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
			'username'	=> 'required|alphanum',
			'password'	=> 'required|validPassword',
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function messages(): array
	{
		return [
			'username.required'			=> 'Please fill out username field.',
			'password.required'			=> 'Please fill out password field.',
			'username.alphanum' 		=> 'invalid username.',
			'password.validPassword'	=> 'Invalid password.',
		];
	}
}
