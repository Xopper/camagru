<?php

namespace App\Controllers;

use System\Controller;

class notFoundController extends Controller
{
	public function index()
	{
		// http_response_code(404);
		$view = $this->view->render("Errors/notFound");
		echo $view;
	}
}
