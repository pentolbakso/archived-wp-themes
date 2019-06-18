<?php
/*
Template Name: Logout
*/
session_start();
session_destroy();

unset($_COOKIE['kue_basah']);
setcookie('kue_basah', '', time() - 3600);

$config = dirname(__FILE__) . '/hybridauth/config.php';
require_once("hybridauth/Hybrid/Auth.php" );
try {

	$hybridauth = new Hybrid_Auth($config);
	$hybridauth->logoutAllProviders();

} catch (Exception $e) {

	echo "Error Logout. Code: ".$e->getCode();
	return;
}

header("Location: ".get_bloginfo('url'));
?>