<?php


$golb = [];

function setToGlob($key, $value)
{
	$glob[$key] = $value;
}

// $errors[$field['name']]['errors'][] = $error;

// ['auth' => ['done' => 'YEEES']];
setToGlob('auth', ['done' => 'YEEES']);


echo "<pre>";
var_dump($glob);
echo "</pre>";
