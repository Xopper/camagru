<?php


namespace App\Controllers\Gallery;
use \System\Controller;

class GalleryController extends Controller 
{
	public function index($pageID = null){
		/**
		 * Reconnect from Cookies
		*/
		$imageModel = $this->load->model("Image");
		$userModel = $this->load->model("User");
		$userModel->reconnectFromCookie();

		$page = isset($pageID) ? $pageID : 1;
		$perPage = 5; // LIMIT
		$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;
		$totalImages = $imageModel->getTotal();
		$totalPages = ceil($totalImages / $perPage);

		if (($page > $totalPages and $totalPages > 0) || $page <= 0){
			$this->url->redirect("/gallery");
		}

		$fetchedImages = $imageModel->imagesPerPage($start, $perPage);
		// var_dump($fetchedImages); die;
		$fetchedImages = $imageModel->setLikesCount($fetchedImages);
		// print_r($this->session->get("auth"));
		if ($this->session->has('auth')){
			$likedImages = $imageModel->getLikedImages($this->session->get('auth')->id);
			$fetchedImages = $imageModel->setLikedStatus($likedImages, $fetchedImages);
		}
		$fetchedImages = $imageModel->setComments($fetchedImages); 

		$this->html->setTitle('Gallery area | Camagru');
		$data = [
			'auth'	=> $this->session->get("auth"),
			'images' => $fetchedImages,
			'totalPages' => $totalPages,
			'curPage' => $page
		];
		$view = $this->view->render("gallery/galleryView", $data);
		echo $this->CommonLayout->render($view);
	}
}