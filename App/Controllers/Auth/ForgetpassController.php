<?php

namespace App\Controllers\Auth;

use System\Controller;

class ForgetpassController extends Controller
{
	/**
	 * Manage GET requset
	 */
	public function index()
	{
		/**
		 * Reconnect From Cookies
		 */
		$userModel = $this->load->model("User");
		$userModel->reconnectFromCookie();

		if ($this->session->has('auth')) {
			$this->session->set("flash", ['danger' => "You're already logged in."]);
			$this->url->redirect("/");
		} else {
			$token = $this->csrf->generateToken();
			$this->html->setTitle("Forget password area | Camagru");
			$data = ['token' => $token];
			$view = $this->view->render("/Auth/ForgetpassView", $data);
			echo $this->CommonLayout->render($view);
		}
	}

	/**
	 * Manage POST requset
	 */
	public function submit()
	{
		$from_schema = ['_email'];
		$errors = $this->validate->isValid($this->rules());
		$CSRFflag = $this->csrf->checkToken();
		if (empty($errors) && $CSRFflag) {
			$userModel = $this->load->model("User");
			$user = $userModel->emailExists($this->request->post("email"));
			if ($user) {
				$token = rand_token(60);
				$userModel->forgetPass($user->id, $token);

				$userModel->sendResetMail($this->request->post('email'), $user->username, $user->id, $token);

				// mail(
				// 	$this->request->post('email'),
				// 	'Reset Password',
				// 	"Hey {$user->username},\n\n to reset your password click here <a href='" . url("/resetpass/{$user->id}/{$token}") . "'>Reset.</a>");


				$this->session->set("flash", ["success" => "Rest instructions sent to your email."]);
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
						'_email'	=> 'This email does not exist.',
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
			'email' => 'required|email',
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function messages(): array
	{
		return [
			'email.required'	=> 'Please fill out this field.',
			'email.email'		=> 'Please insert a valid email.',
		];
	}
}
