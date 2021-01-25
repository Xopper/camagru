<?php

namespace App;

use \System\Application;

$app = Application::getInstance();

$app->route->add('/', 'Home');

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

$app->route->add('/saveimg', 'Insta/SaveImg@submit', 'POST');
$app->route->add('/getUserimages', 'Insta/GetImages@submit', 'POST');
$app->route->add('/deleteselectedimage', 'Insta/deleteSelectedImage@submit', 'POST');

$app->route->add('/gallery', 'Gallery/Gallery');
$app->route->add('/gallery/page-:id', 'Gallery/Gallery');

$app->route->add('/deletecomment', 'Gallery/DeleteComment@delete', 'POST');
$app->route->add('/setlike', 'Gallery/SetLike@set', 'POST');
$app->route->add('/sendcomment', 'Gallery/SendComment@send', 'POST');

$app->route->add('/resetpass/:id/:token', 'Auth/Reset');
$app->route->add('/resetpass/:id/:token', 'Auth/Reset@submit', 'POST');


$app->share('CommonLayout', function ($app) {
	return $app->load->controller('Common/Layout');
});

$app->route->add('/404', 'notFound');
$app->route->notFound('/404');

// pre($app->route->routes);
// die();
