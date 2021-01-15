<?php

namespace App\Controllers\Auth;

use System\Controller;

class ResetController extends Controller
{
	public function index($id, $token)
	{
		/**
		 * Reconnect from Cookies
		 */
		$userModel = $this->load->model("User");
		$userModel->reconnectFromCookie();

		if ($this->session->has('auth')) {
			$this->session->set('flash', ["danger" => "you can reset your password while you're logged in .. try to logout first."]);
			$this->url->redirect("/");
		} else {
			$userModel = $this->load->model("User");
			$flag = $userModel->verify($id, "reset_token", $token);
			if ($flag === true) {
				$CSRFtoken = $this->csrf->generateToken();
				$data = [
					'user_id' => $id,
					'token' => $CSRFtoken,
				];
				$this->session->set("userID", $id);
				$this->html->setTitle("Reset account area | Camagru");
				$view = $this->view->render("Auth/ResetpassView", $data);
				echo $this->CommonLayout->render($view);
			} else {
				$this->session->set('flash', ["danger" => "You're tying to reset an account with wrong URL."]);
				$this->url->redirect("/");
			}
		}
	}
	public function submit()
	{
		$from_schema = ['_Npassword', '_conf-Npass'];
		$errors = $this->validate->isValid($this->rules());
		$CSRFflag = $this->csrf->checkToken();
		if (empty($errors && $CSRFflag)) {
			$userModel = $this->load->model("User");
			$password = password_hash($this->request->post("Npassword"), PASSWORD_BCRYPT);
			$userModel->updatePass($this->session->get("userID"), $password);
			$userModel->unsetToken($this->session->get("userID"));

			$this->session->set('flash', ['success' => 'Password has been reset successfully.']);
			$this->session->set('auth', $userModel->getById($this->session->pull("userID")));

			$json = json_encode([
				'ok' => true,
				'csrf' => $CSRFflag,
				'redirect' => '/account',
			]);
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
			'Npassword'		=> 'required|min:8|validPassword',
			'conf-Npass'	=> 'required|min:8|validPassword|same:Npassword',
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function messages():array
	{
		return [
			'Npassword.required'		=> 'Please fill out this field.',
			'Npassword.min'				=> 'Password must be at least 8 characters.',
			'Npassword.validPassword'	=> 'Use [lower-Upper] case, special chars and numbers.',

			'conf-Npass.required'		=> 'Please fill out this field.',
			'conf-Npass.min'			=> 'Password must be at least 8 characters.',
			'conf-Npass.validPassword'	=> 'Use [lower-Upper] case, special chars and numbers.',
			'conf-Npass.same'			=> 'Please confirm your password correctly.',
		];
	}

}
