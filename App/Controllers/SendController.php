<?php

namespace App\Controllers;

use \System\Controller;

class SendController extends Controller 
{
	public function index(){
		// $to      = 'ca3ymix@gmail.com';
		$to      = 'dfb488ebec@firemailbox.club';
		$subject = 'the subject';
		$message = "<h1>hello</h1>";
		// $headers = 'From: webmaster@example.com' . "\r\n" .
		// 	'Reply-To: webmaster@example.com' . "\r\n" .
		// 	'X-Mailer: PHP/' . phpversion();
		
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=iso-8859-1';
		
		// Additional headers
		// $headers[] = 'To: Mary <mary@example.com>, Kelly <kelly@example.com>';
		// $headers[] = 'From: Birthday Reminder <birthday@example.com>';
		// $headers[] = 'Cc: birthdayarchive@example.com';
		// $headers[] = 'Bcc: birthdaycheck@example.com';

		$flag = mail($to, $subject, $message, implode("\r\n", $headers));

		if ($flag){
			echo "done very well";
		}else{
			echo "hbet";
		}
	}

	public function submit()
	{
	} 
}