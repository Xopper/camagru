<?php

namespace App\Controllers;

use System\Controller;

class notFoundController extends Controller
{
	public function index()
	{
		$view = $this->view->render("Errors/notFound");
		echo $this->CommonLayout->render($view);
	}
}
