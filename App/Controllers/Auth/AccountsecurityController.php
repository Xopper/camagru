<?php

namespace App\Controllers\Auth;

use System\Controller;

class AccountsecurityController extends Controller
{
	public function index()
	{
		/**
		 * Reconnect From Cookies
		 */
		$userModel = $this->load->model("User");
		$userModel->reconnectFromCookie();

		if (!$this->session->has('auth')) {
			$this->session->set('flash', ["danger" => "You're not allowed to explore this page."]);
			$this->url->redirect("/");
		} else {
			$token = $this->csrf->generateToken();
			$data = [
				'token' => $token,
			];
			$this->html->setTitle("Security area | Camagru");
			$view = $this->view->render("Auth/AccountsecurityView", $data);
			echo $this->CommonLayout->render($view);
		}
	}
	public function submit()
	{
		$from_schema = ['_Npassword', '_conf-Npass', '_Opassword'];
		$errors = $this->validate->isValid($this->rules());
		$CSRFflag = $this->csrf->checkToken();
		if (empty($errors) && $CSRFflag) {
			$user = $this->session->get('auth');
			if (password_verify($this->request->post('Opassword'), $user->password)) {
				$newPassword = password_hash($this->request->post('Npassword'), PASSWORD_BCRYPT);
				$userModel = $this->load->model('User');
				$userModel->updatePass($user->id, $newPassword);
				$userModel->updateSess(); // update auth Session

				$this->session->set('flash', ['success' => 'Password has been changer successfully.']);
				$json = json_encode([
					'ok' => true,
					'csrf' => $CSRFflag,
					'redirect' => '/account/security',
				]);
				echo $json;
			} else {
				$this->session->set('flash', ['danger' => 'Invalid password.']);
				$json = json_encode([
					'ok' => true,
					'csrf' => $CSRFflag,
					'redirect' => '/account',
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
			'Npassword'		=> 'required|min:8|validPassword',
			'conf-Npass'	=> 'required|min:8|validPassword|same:Npassword',
			'Opassword'		=> 'required|min:8|validPassword',
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function messages(): array
	{
		return [
			'Npassword.required'		=> 'Please fill out this field.',
			'Npassword.min'				=> 'Password must be at least 8 characters.',
			'Npassword.validPassword'	=> 'Use [lower-Upper] case, special chars and numbers.',
			
			'conf-Npass.required'		=> 'Please fill out this field.',
			'conf-Npass.min'			=> 'Password must be at least 8 characters.',
			'conf-Npass.same'			=> 'Please confirm your password correctly.',
			'conf-Npass.validPassword'	=> 'Use [lower-Upper] case, special chars and numbers.',
			
			'Opassword.required'		=> 'Please fill out this field.',
			'Opassword.min'				=> 'Password must be at least 8 characters.',
			'Opassword.validPassword'	=> 'Invalid password.',
		];
	}
}
