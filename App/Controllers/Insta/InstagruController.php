<?php


namespace App\Controllers\Insta;
use \System\Controller;

class InstagruController extends Controller 
{
	public function index(){
		/**
		 * Reconnect from Cookies
		*/
		$userModel = $this->load->model("User");
		$userModel->reconnectFromCookie();

		if (!$this->session->has('auth')) {
			$this->session->set('flash', ["danger" => "You're not allowed to explore this page."]);
			$this->url->redirect("/login");
		} else {
			$this->html->setTitle('Snapping area | Camagru');
			$token = $this->csrf->generateToken();
			$data = [
				'token' => $token,
				'auth'	=> $this->session->get("auth"),
			];
			$view = $this->view->render("insta/instaView", $data);
			echo $this->CommonLayout->render($view);
		}
	}

	public function submit(){
		$files = $this->request->file('file'); // capturing 
		if ($files->isImages()){
			$upFiles = $files->moveTo($this->file->to("public/ups"), true);
			/**
			 * send : names, urls[realPaths], 
			 */
			$uploaded = [
				'names' => $files->getNamesOnly(),
				'realPaths' => $upFiles,
			];
			$json = [
				'ok' => true,
				'infos' => $uploaded,
			];
		}else{
			$this->session->set("flash", ["danger" => "You may upload a valid images :/"]);
			$json = [
				'ok' => false,
				'redirect' => '/instagru',
			];
		}
		echo json_encode($json);
	}
}