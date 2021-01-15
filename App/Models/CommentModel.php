<?php

namespace App\Models;

use stdClass;
use System\Model;

class CommentModel extends Model
{
	protected $table = "comments";

	/**
	 * Delete a Comment
	 */
	public function deleteComment($commentID, $userId){
		// $comments = $this->query("SELECT image_id FROM `{$this->table}` WHERE `id` = ? and `user_id` = ?", $commentID, $userId)->fetch();
		$stmt = $this->query("DELETE FROM `{$this->table}` WHERE `id` = ? and `user_id` = ?", $commentID, $userId);
		return $stmt->rowCount();
	}

	/**
	 * Get image id
	 */
	public function getImageID($commentID, $userId){
		$comments = $this->query("SELECT image_id FROM `{$this->table}` WHERE `id` = ? and `user_id` = ?", $commentID, $userId)->fetch();
		return $comments;
	}

	/**
	 * add comment
	 */
	public function addComment($userID, $imageID, $comment){
		$stmt = $this->query("INSERT INTO `{$this->table}` SET `user_id` = ? , `image_id` = ? , `comment` = ?, `created_at` = NOW()", $userID, $imageID, $comment);
		return $stmt->rowCount();
	}

}