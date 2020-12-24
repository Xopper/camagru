<?php

namespace App\Controllers;

use System\Controller;

class HomeController extends Controller
{
	public function index()
	{

		// pre($this->load->model("User")->getById(1));
		// die();

		if (!$this->session->has('auth')) {
			/**
			 * try to reconncet from Cookie
			 */
			$userModel = $this->load->model("User");
			$userModel->reconnectFromCookie();
		}
		$this->html->setTitle("Home area | Camagru");
		// $token = $this->session->pull("token");
		$view = $this->view->render("Home/HomeView");
		echo $this->CommonLayout->render($view);



		// pre(new db($this->app));
		// $this->db->query("INSERT INTO users SET username = ? , usermail = ?", "Ahaloua", "ali@about.ma");
		// echo $this->db->data("username", "Ahaloua")
		// ->data("usermail", "ads@about.ma")
		// ->insert("users")->lastId();
		// echo $this->db->lastId();
		// $this->db->data("username", "idio")->data("usermail", "dst@1337.ma")->insert("users");

		// $result = $this->db->select("*")->orderBy("id")->fetchAll("users");
		// $result = $this->db->select("*")->from("users")->where("id > ? AND id < ?", 9, 21)->orderBy("id")->fetchAll();
		// pre($result);
		// $this->db->where("username = ?", "Ahaloua")->delete("users");
		// pre($this->db->where("id > ?", 22)->fetchAll("users"));
		// echo $this->db->rows() . "<br>";
		// pre($this->db->fetchAll("users"));
		// echo $this->db->rows() . "<br>";
		// pre($this->load->model("User")->getById(1));
		// pre($user->getAll());

		// echo $this->url->link("/home/imgs.png");
		// echo assets("/images/dog.png");
		// $this->url->redirect("/404");
	}
}
