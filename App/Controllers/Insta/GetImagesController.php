<?php

namespace App\Controllers\Insta;

use System\Controller;

class GetImagesController extends Controller 
{
	public function submit()
	{
		$imageModel = $this->load->model("Image");
		$data = $imageModel->getByUserId($this->session->get('auth')->id);
		$json = $data;
		echo json_encode($json);
	}
}