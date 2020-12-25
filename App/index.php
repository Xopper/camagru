<?php

namespace App;

// White list routes
use \System\Application;

$app = Application::getInstance();

$app->route->add('/', 'Home');

// blog post Route exemple
// /posts/my-first-title/121;
$app->route->add('/submit', 'Home@submit', 'post');
$app->route->add('/posts/:text/:id', 'Posts\\Post@index', 'post');
$app->route->add('/posts/:text/:id', 'Posts/Post', 'put');
$app->route->add('/posts/:text/:id', 'Posts/Post', 'GET');


$app->route->add('/register', 'Auth/Register');
$app->route->add('/register', 'Auth/Register@submit', 'POST');

$app->route->add('/login', 'Auth/Login');
$app->route->add('/login', 'Auth/Login@submit', 'POST');

$app->route->add('/account', 'Auth/Account');
$app->route->add('/account', 'Auth/Account@submit', 'POST');

$app->route->add('/account/security', 'Auth/Accountsecurity');
$app->route->add('/account/security', 'Auth/Accountsecurity@submit', 'POST');

$app->route->add('/logout', 'Auth/Logout');

$app->route->add('/confirm/:id/:token', 'Auth/Confirm');

$app->route->add('/forgetpass', 'Auth/Forgetpass');
$app->route->add('/forgetpass', 'Auth/Forgetpass@submit', 'POST');

$app->route->add('/instagru', 'Insta/Instagru');
$app->route->add('/instagru', 'Insta/Instagru@submit', 'POST');

$app->route->add('/resetpass/:id/:token', 'Auth/Reset');
$app->route->add('/resetpass/:id/:token', 'Auth/Reset@submit', 'POST');

$app->route->add('/test', 'Test');
$app->route->add('/test', 'Test@submit', "POST");

$app->share('CommonLayout', function ($app) {
	return $app->load->controller('Common/Layout');
});

$app->route->add('/404', 'notFound');
$app->route->notFound('/404');
