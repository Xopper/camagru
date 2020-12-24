<?php

namespace App\Controllers;

use \System\Controller;

class testController extends Controller 
{
	public function index(){
		$view = $this->view->render('test/testView');
		echo $this->CommonLayout->render($view);
	}

	public function submit()
	{
		echo "Hello Bagssi <br />";
		$file = $this->request->file('image');
		$file->moveTo($this->file->to("public/imgs"));
	} 
}