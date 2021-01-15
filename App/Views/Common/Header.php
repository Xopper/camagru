<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title><?= $title ?></title>
		<link rel="apple-touch-icon" sizes="180x180" href="<?= assets("public/imgs/apple-touch-icon.png") ?>">
		<link rel="icon" type="image/png" sizes="32x32" href="<?= assets("public/imgs/favicon-32x32.png") ?>">
		<link rel="icon" type="image/png" sizes="16x16" href="<?= assets("public/imgs/favicon-16x16.png") ?>">
		<link rel="manifest" href="">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="theme-color" content="#ffffff">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="<?= assets("public/css/crispo.css") ?>" />
	</head>
	<body class="preload">
		<div class="container">
			<div class="nav-container">
			<nav class="columns">
					<!-- LOGO -->
					<div class="column no-flex logo">
						<a href="#"
						><img
							class="logo"
							src="<?= assets("public/imgs/video-calling-app.svg") ?>"
							alt="logo"
					/></a>
					</div>
					<!-- Nav Links -->
					<ul id="nav__links" class="column nav__links">
						<li><a href="/">Home</a></li>
						<li><a href="/gallery">Gallery</a></li>
						<?php if (isset($auth)) : ?>
							<li><a href="/instagru">Instagru</a></li>
						<?php endif; ?>
						<?php if (!isset($auth)) : ?>
							<li class="none-primary"><a href="/login">Login</a></li>
							<li class="none-primary"><a href="/register">Register</a></li>
						<?php else : ?>
							<li class="none-primary"><a href="/account">Account</a></li>
							<li class="none-primary"><a href="/logout">Logout</a></li>
						<?php endif; ?>
					</ul>
					<!-- auth links -->
					<div class="column no-flex auth">
						<?php if (!isset($auth)) : ?>
							<a class="ctf" href="/login"><button class="btn btn-not-primary">Login</button></a>
							<a class="ctf" href="/register"><button class="btn btn-primary">Register</button></a>
						<?php else : ?>
							<p class="logged">
								Hi, &#128075;
							<a class="username" href="/account"><?= $auth->firstName . " " . $auth->lastName ?></a>
							</p>
							<a class="ctf" href="/logout"><button class="btn">Logout</button></a>
						<?php endif; ?>
					</div>
					<!-- Burger links -->
					<div id="burger" class="column no-flex burger">
						<div class="line1"></div>
						<div class="line2"></div>
						<div class="line3"></div>
					</div>
				</nav>
			</div>
				<main>			
				<!-- flash msgs -->
					<? if(isset($flash)): ?>
					<? foreach($flash as $type => $msg): ?>
						<div class="flash is-<?=$type?>"><?= $msg ?></div>
					<? endforeach;?>
					<? endif;?>