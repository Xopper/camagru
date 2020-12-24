<?php


// die(phpinfo());
require __DIR__ . "/vendor/System/Application.php";
require __DIR__ . "/vendor/System/File.php";


// die(phpinfo());

//echo __DIR__ . "/vendor/System/Appliation.php";


use System\Application;
use System\File;
use System\Test;
use App\Controllers\Users\Users;


$file = new File(__DIR__);
$app = Application::getInstance($file);

$app->run();

