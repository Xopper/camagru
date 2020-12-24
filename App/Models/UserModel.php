<?php

namespace App\Models;

use stdClass;
use System\Model;

class UserModel extends Model
{
	protected $table = "users";

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
	 * Add a user while registring
	 * @param string $username
	 * @param string $password
	 * @param string $email
	 * @param string $token
	 * 
	 * @return void
	 */
	public function addUser($fName, $lName, $username, $password, $email, $token): void
	{
		$password = password_hash($password, PASSWORD_BCRYPT);
		$this->data("username", $username)
			->data("firstName", $fName)
			->data("lastName", $lName)
			->data("email", $email)
			->data("password", $password)
			->data("confirmation_token", $token)
			->data("notification_on", true)
			->insert($this->table);
	}

	/**
	 * Verify User with a token was send to his email
	 * check if the token entred by the user match that's in db
	 * @param int $id 
	 * @param string $field Field name to verify on Database i.e. reset_token or confirmation_token
	 * @param string $token
	 * 
	 * @return bool
	 */
	public function verify($id, $field, $token)
	{
		/**
		 * optional we gonna set time constraint with 30 min
		 * only for rest_token
		 */
		if ($field == "reset_token") {
			$user = $this->select("{$field}")->where("id = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)", $id)->fetch($this->table);
		} else {
			$user = $this->select("{$field}")->where("id = ?", $id)->fetch($this->table);
		}
		if ($user->{$field} == $token) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Update database records after Verify() the user token
	 * @param int $id
	 * 
	 * @return void
	 */
	public function confirmUser($id)
	{
		$this->query("UPDATE users SET `confirmation_token` = NULL , `confirmed_at` = NOW() WHERE id = ?", $id);
	}

	/**
	 * check input data sent through login form with that stored in database
	 * @param string $username
	 * @param string $password
	 * 
	 * @return mixed
	 */
	public function validateLog($username, $password)
	{
		$user = $this->select("*")->where("username = ? AND confirmed_at IS NOT NULL", $username)->fetch("users");
		if ($user) {
			if (password_verify($password, $user->password)) {
				return $user;
			}
		} else {
			return FALSE;
		}
	}
	public function updatePass($user_id, $newPassword): void
	{
		$this->data('password', $newPassword)->where("id = ?", $user_id)->update($this->table);
	}
	public function updateUser($user_id, $fName, $lName, $username, $password, $email, $notification): void
	{
		// TODO
		$password = password_hash($password, PASSWORD_BCRYPT);
		$this->data("firstName", $fName)
			->data("lastName", $lName)
			->data("username", $username)
			->data("password", $password)
			->data("email", $email)
			->data("notification_on", $notification)
			->where("id = ?", $user_id)
			->update($this->table);
	}
	public function updateSess(): void
	{
		$user = $this->app->session->pull("auth");
		$this->app->session->set("auth", $this->getById($user->id));
	}
	public function emailExists($email)
	{
		$user = $this->select("*")->where("email = ? AND confirmed_at IS NOT NULL", $email)->fetch($this->table);
		$rowCount = $this->rows();
		if ($rowCount) {
			return $user;
		}
		return FALSE;
	}
	public function forgetPass($id, $token)
	{
		$this->query("UPDATE users SET `reset_token` = ? , `reset_at` = NOW() WHERE id = ?", $token, $id);
	}
	public function unsetToken($id)
	{
		$this->query("UPDATE users SET `reset_token` = NULL , `reset_at` = NULL WHERE id = ?", $id);
	}
	public function rememberUser($id)
	{
		$user = $this->select("*")->where("id = ? AND remember_token IS NOT NULL", $id)->fetch($this->table);
		if ($this->rows() != 0) {
			return $user;
		}
		return FALSE;
	}
	public function setCookieOnDB($id, $cookie)
	{
		$this->data("remember_token", $cookie)->where("id = ?", $id)->update("users");
	}
	public function unsetCookieOnDB($id)
	{
		$this->query("UPDATE users SET `remember_token` = NULL WHERE id = ?", $id);
	}
	public function reconnectFromCookie()
	{
		if ($this->cookie->has("remember")) {
			$remember_token = $this->cookie->get("remember");
			$token = explode("==", $remember_token);
			$userID = $token[0];
			if (is_numeric($userID)) {
				$user = $this->rememberUser($userID);
			}
			if ($user) {
				$expected = $user->id . "==" . $user->remember_token . sha1($user->id . "Future Is Loading");
				if ($expected == $remember_token) {
					$this->session->set("auth", $user);
					// $this->url->redirect("/");
				} else {
					$this->cookie->remove("remember");
					$this->unsetCookieOnDB($user->id);
					$this->url->redirect("/");
				}
			} else {
				$this->cookie->romove("remember");
				$this->url->redirect("/");
			}
		}
	}
}
