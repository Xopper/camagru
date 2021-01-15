<?php

namespace App\Controllers\Gallery;

use System\Controller;

class SetLikeController extends Controller 
{
	public function set()
	{
		if (!$this->session->has('auth')){
			$json['ok'] = null; 
		}else {
			$imageSrc = $this->request->post('imageSrc');
			$imageSrc = basename($imageSrc);
			$imageModel = $this->load->model("Image");
	
			$json['imageID'] = $imageModel->getImageID($imageSrc);
	
			$userId = $this->session->get('auth')->id;
	
			if(!$json['imageID']){
				$json['ok'] = false; 
				$this->session->set("flash", ['danger' => "Failed to like photo"]);
				$json['redirect'] = "/gallery";
			} else {
				$json['likeReturn'] = $imageModel->setLike($userId, $json['imageID']);
				if ($json['likeReturn'] == true) {
					$json['likes_count'] = $imageModel->getLikesCount($json['imageID'])->likes;
					$json['ok'] = true;
					$json['liked_status'] = true;
				} elseif ($json['likeReturn'] == false) {
					$json['likes_count'] = $imageModel->getLikesCount($json['imageID'])->likes;
					$json['ok'] = true;
					$json['liked_status'] = false;
				}else{
					$json['ok'] = false; 
					$this->session->set("flash", ['danger' => "Failed to like/unlike photo"]);
					$json['redirect'] = "/gallery";
				}
			}
		}

		echo json_encode($json);
	}
}