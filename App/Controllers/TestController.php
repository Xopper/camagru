<?php

namespace App\Controllers;

use \System\Controller;

class TestController extends Controller 
{
	public function index(){
		$view = $this->view->render('test/testView');
		echo $view;
		// echo $this->CommonLayout->render($view);
	}

	public function submit()
	{
		// echo "Hello Bagssi <br />";
		$files = $this->request->file('file'); // capturing 
		if ($files->isImages()){
			$upFiles = $files->moveTo($this->file->to("public/ups"), true);
			/**
			 * send : names, urls[paths], 
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
		// print_r($_FILES);
		// $file = $this->request->file('file');
		// $file->checkErrors();
	} 
}