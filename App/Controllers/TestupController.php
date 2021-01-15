<?php

namespace App\Controllers;

use \System\Controller;

class TestupController extends Controller 
{
	public function index(){
		$view = $this->view->render('test/testupView');
		echo $view;
		// echo $this->CommonLayout->render($view);
	}

	public function submit()
	{
		// echo "Hello Bagssi <br />";
		$file = $this->request->file('image');
		// $file->moveTo($this->file->to("public/imgs"));
		// $json = json_encode($_FILES);
		// echo $json;
		// pre($_FILES);
		// echo $_FILES['image']['name']; 
	} 
}