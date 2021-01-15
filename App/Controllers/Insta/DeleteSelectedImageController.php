<?php

namespace App\Controllers\Insta;

use System\Controller;

class DeleteSelectedImageController extends Controller 
{
	public function submit()
	{
		if ($this->request->server('HTTP_REFERER') == $this->url->link("instagru")){
			$imageId = $this->request->post('imageID');
			$userId = $this->session->get('auth')->id;
			$imageModel = $this->load->model("Image");
			$json['count'] = $imageModel->deleteImage($imageId, $userId); 
			$json['data'] =$imageModel->getByUserId($userId);
			if($json['count'] == 0){
				$this->session->set("flash", ['danger' => "Selected image isn't found"]);
				$json['redirect'] = "/instagru";
			}
		} else {
			$json = "ok this is a bad request";
		}
		echo json_encode($json);
	}
}