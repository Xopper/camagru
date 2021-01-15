<?php

namespace App\Controllers\Gallery;

use System\Controller;

class SendCommentController extends Controller 
{
	public function send()
	{
		if (!$this->session->has('auth')){
			$json['ok'] = null; 
		}else {
			$errors = $this->validate->isValid($this->rules());
			if ($errors) {
				$this->session->set('flash', ["danger" => "Invalid comment"]);
				$json['ok'] = false;
				$json['redirect'] = "/gallery";
			} else {
				// $json['imageID'] = (int)$this->request->post('imageID');
				$json['imageID'] = (int)$this->request->post('imageID');
				if ($json['imageID'] > 0){
					$userID = $this->session->get("auth")->id;
					$commentModel = $this->load->model('Comment');
					$flag = $commentModel->addComment($userID, $json['imageID'], $this->request->post('comment'));
					if ($flag){
						$imageModel = $this->load->model('Image');
						$imageModel->notify($json['imageID']);
						$json['ok'] = true;
						$json['redirect'] = "/gallery";
						$this->session->set('flash', ["success" => "comment added successfully"]);
					} else {
						$this->session->set('flash', ["danger" => "Failled to add comment"]);
						$json['ok'] = false;
						$json['redirect'] = "/gallery";
					}
				} else {
					$this->session->set('flash', ["danger" => "not found image :/"]);
					$json['ok'] = false;
					$json['redirect'] = "/gallery";
				}
	
			}
		}

		echo json_encode($json);
	}

	/**
	 * @inheritDoc
	 */
	protected function rules(): array
	{
		return [
			'comment'	=> 'required|max:140',
		];
	}
}
