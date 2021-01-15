<?php

namespace App\Models;

use stdClass;
use System\Model;

class ImageModel extends Model
{
	protected $table = "images";

	/**
	 * Get all users stored in users table
	 * 
	 * @return array
	 */
	public function getAll(): array
	{
		return $this->fetchAll($this->table);
	}

	/**
	 * get user by a given id
	 * @param int $id
	 * 
	 * @return stdClass
	 */
	public function getById($id): stdClass
	{
		return $this->where("id = ?", $id)->fetch($this->table);
	}

	/**
	 * Get all images taken by a user
	 */
	public function getByUserID($userId)
	{
		return $this->where("user_id = ?", $userId)->fetchAll($this->table);
	}
	
	/**
	 * Add a user while registring
	 * @param string $imageName
	 * @param string $userId
	 * 
	 * @return void
	 */
	public function addImage($imageName, $userId): void
	{
		$this->query("INSERT INTO `{$this->table}` SET `image_name` = ?,  `user_id` = ?, `created_at` = NOW()", $imageName, $userId);
	}

	/**
	 * Delete Selected image
	 */
	public function deleteImage($imageId, $userId)
	{
		$stmt = $this->query("DELETE FROM `{$this->table}` WHERE `id` = ? and `user_id` = ?", $imageId, $userId);
		return $stmt->rowCount();
	}

	/**
	 * Get All images
	 */
	public function imagesPerPage($start, $per_page){
		$stmt = $this->query("SELECT images.id, username, image_name,
			COUNT(comments.image_id) as commentsCount

			FROM images
			/* to get posted images username */
			INNER JOIN users
			ON images.user_id = users.id

			LEFT join comments
			ON images.id = comments.image_id

			group by images.id
			order by images.id DESC
			LIMIT {$start}, {$per_page}
		")->fetchAll();
		return $stmt;
	}



	/**
	 * get totale like for an image
	 */
	public function getLikesCount($imageID){
		return $this->query("SELECT COUNT(likes.id) as likes from likes where likes.image_id = ? ", $imageID)->fetch();
	}

	/**
	 * get totale like for an image
	 */
	public function getCommentsCount($imageID){
		return $this->query("SELECT COUNT(comments.id) as commentsCount from comments where comments.image_id = ? ", $imageID)->fetch();
	}

	/**
	 * set likes count on Fetched data
	 */
	public function setLikesCount($images){
		foreach ($images as $image){
			$image->likes = $this->getLikesCount($image->id)->likes;
		}
		return $images;
	}
	/**
	 * Get total images 
	 */
	public function getTotal(){
		$this->select("*")->fetchAll($this->table);
		return  $this->rows();
	}

	/**
	 * get all liked images
	 */
	public function getLikedImages($userID){
		return $this->query("SELECT likes.image_id
		from likes
		where likes.user_id = {$userID}
		
		order by likes.id DESC")->fetchAll();
	}

	/**
	 * Set liked Status
	 */
	public function setLikedStatus($likeImages = [], $images){
		foreach ($images as $image){
			// pre($likeImages);
			$image->liked = false;
			for ($i = 0; $i < count($likeImages); $i++)
			{
				if ($likeImages[$i]->image_id == $image->id){
					$image->liked = true;
					continue ;
				}
			} 
		}
		return $images;
	}

	/**
	 * Get all Comments of 
	 */
	public function getCommentsByImgID($imageID){
		return $this->query("SELECT comments.id as id, users.username, comment 
		from comments

		inner join users
		on users.id = comments.user_id

		where comments.image_id = {$imageID}
		")->fetchAll();
	}

	/**
	 * set Comments to images
	 */
	public function setComments($images){
		foreach ($images as $image){
			if ($image->commentsCount != 0){
				$image->comments = $this->getCommentsByImgID($image->id);
			}else{
				$image->comments = false;
			}
		}
		return $images;
	}

	/**
	 * get image id by name
	 */
	public function getImageID($imageName){
		$image = $this->query("SELECT id FROM `{$this->table}` WHERE `image_name` = ?", $imageName)->fetch();
		return $image->id;
	}

	/**
	 * set the like 
	 */
	public function setLike($user_id, $image_id)
	{
		$imageExists = $this->query("SELECT id FROM images WHERE images.id = {$image_id}")->fetch();
		$likeExists = $this->query("SELECT id FROM likes WHERE likes.user_id = {$user_id} and likes.image_id = {$image_id}")->fetch();
		if ($imageExists and !$likeExists){
			$stmt = $this->query("INSERT INTO likes SET `user_id` = ?, `image_id` = ?", $user_id, $image_id);
			return true;
		} elseif ($imageExists and $likeExists) {
			$stmt = $this->query("DELETE FROM likes WHERE likes.user_id = ? AND likes.image_id = ?", $user_id, $image_id);
			return false;
		}
		return null;
	}

	/**
	 * notify picture owner
	 */
	public function notify($imageID){
		$stmt = $this->query("SELECT users.notification_on as notify_status , users.username, users.email
		FROM images

		INNER JOIN users
		ON images.user_id = users.id
		WHERE images.id = ?
		",$imageID)->fetch();
		if ($stmt->notify_status == 1){
			$this->load->model("User")->sendNotification($stmt->email, $stmt->username);
		}
	}
}
