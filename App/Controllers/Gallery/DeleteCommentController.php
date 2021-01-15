<?php

namespace App\Controllers\Gallery;

use System\Controller;

class DeleteCommentController extends Controller 
{
	public function delete()
	{
		$commentID = (int)$this->request->post('commentID');
		$userId = $this->session->get('auth')->id;
		$commentModel = $this->load->model("Comment");
		// $json['count'] = $commentModel->deleteComment($commentID, $userId); 
		$json['image_id'] = $commentModel->getImageID($commentID, $userId)->image_id; 
		$json['count'] = $commentModel->deleteComment($commentID, $userId);
		
		if($json['count'] == 0){
			$json['ok'] = false; 
			$this->session->set("flash", ['danger' => "Failed to delete Comment"]);
			$json['redirect'] = "/gallery";
		} else {
			$imageModel = $this->load->model("Image");
			$json['commentsCount'] = $imageModel->getCommentsCount($json['image_id'])->commentsCount;
			// $json['com-ID'] = $commentID;
			// $json['user-ID'] = $userId;
			$json['ok'] = true;
		}
		echo json_encode($json);
	}
}