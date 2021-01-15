<?php

namespace App\Controllers\Insta;

use System\Controller;

class SaveImgController extends Controller 
{
	public function submit()
	{
		if ($this->request->post('canvas'))
		{
			$this->request->post('canvas'); // from formData.append();
			$this->request->post('sticker'); // from formData.append();

			// $json = $this->session->get('auth');  => check if there is auth in the session
			
			
			$sticker = basename($this->request->post('sticker'));
			$stickerPath = $this->file->to('public/stickers') . "/" . $sticker;
	
			// check if the sticker is in the stickers folder
			if (is_file($stickerPath)){
				$json = "hada kayen";
				$canvas = str_replace(['data:image/png;base64,', ' '], ['', '+'], $this->request->post('canvas'));
				$canvasContent = base64_decode($canvas);
				list($oldWidth, $oldHeight) = getimagesize($stickerPath);

				/**
				 * Canvas Resource
				 */
				$canvasRes = imagecreatefromstring($canvasContent);

				/**
				 * Sticker Resource
				 */
				$aspectRatio = $oldWidth / $oldHeight;
				$newHeight = 100;
				$newWidth = $newHeight * $aspectRatio;

				$stickerRes = imagecreatefromstring(file_get_contents($stickerPath));
				// imagecopyresized($stickerRes, $stickerRes, 0, 0, 0, 0, $newWidth, $newHeight, $oldWidth, $oldHeight);

				/**
				 * @link https://eikhart.com/blog/aspect-ratio-calculator
				 */

				// aspectRatio = ( oldWidth / oldHeight )
				// newHeight = ( newWidth / aspectRatio )
				// newWidth = ( newHeight * aspectRatio )

				/**
				 * try to see this @link https://www.geeksforgeeks.org/php-imagecopyresampled-function/
				 */
				imagecopyresampled($canvasRes, $stickerRes, 37, 50, 0, 0, $newWidth, $newHeight, $oldWidth , $oldHeight);
				$finalName = $this->file->to('public/userpics') . "/" . sha1(mt_rand()) . "__" . sha1(mt_rand()) . ".png";
				imagepng($canvasRes, $finalName, 0);
				$imageModel = $this->load->model("Image");
				$imageModel->addImage(basename($finalName), $this->session->get('auth')->id);
				$data = $imageModel->getByUserId($this->session->get('auth')->id);
				$json = $data;
			} else {
				$json = "nn had sticker makayench";
			}
		} elseif ($this->request->post('image')) {
			$img = basename($this->request->post('image'));
			$imgPath = $this->file->to('public/ups') . "/" . $img;

			$sticker = basename($this->request->post('sticker'));
			$stickerPath = $this->file->to('public/stickers') . "/" . $sticker;
			if (is_file($imgPath) && is_file($stickerPath))
			{
				list($oldWidth, $oldHeight) = getimagesize($stickerPath);
				$aspectRatio = $oldWidth / $oldHeight;
				$newHeight = 100;
				$newWidth = $newHeight * $aspectRatio;
				$imgRes = imagecreatefromstring(file_get_contents($imgPath));
				$stickerRes = imagecreatefromstring(file_get_contents($stickerPath));
				imagecopyresampled($imgRes, $stickerRes, 37, 50, 0, 0, $newWidth, $newHeight, $oldWidth , $oldHeight);
				$finalName = $this->file->to('public/userpics') . "/" . sha1(mt_rand()) . "__" . sha1(mt_rand()) . ".png";
				imagepng($imgRes, $finalName, 0);
				$imageModel = $this->load->model("Image");
				$imageModel->addImage(basename($finalName), $this->session->get('auth')->id);
				$data = $imageModel->getByUserId($this->session->get('auth')->id);
				$json = $data;
			}else {
				$json = is_file($stickerPath) . " -- " . is_file($imgPath);
			}
		}
		// $json = $_POST;
		// $json = base64_decode($canvas);
		echo json_encode($json);
	}
}