<?php

namespace App\Controllers\Common;

use System\Controller;
use System\View\ViewInterface;

class LayoutController extends Controller 
{
	public function header(){
		$title = $this->html->getTitle();
		$data = [
			'auth'	=> $this->session->get("auth"),
			'flash'	=> $this->session->pull('flash'),
			'title' => $title,
		];
		return $this->view->render('Common/Header', $data);
	}
	public function footer(){
		return $this->view->render('Common/Footer');
	}
	public function render(ViewInterface $view){
		// pre($this);
		// die();
		$data['content'] = $view;
		$data['header'] = $this->header();
		$data['footer'] = $this->footer();
		return $this->view->render('Common/Layout', $data);
	}
}