<?php
include "../../../wp-load.php";
//include "myfunctions.php";

$camera = trim($_POST['camera']);
$url = trim($_POST['url']);
$comment = trim($_POST['comment']);
$param = trim($_POST['param']);
$value = trim($_POST['value']);

$camera = strip_tags($camera);
$comment = strip_tags($comment);
$value = strip_tags($value);

global $wpdb;
if( function_exists('mysql_set_charset') )	mysql_set_charset('utf8');
else $wpdb->query("SET NAMES 'utf8'");

$sql = "INSERT INTO compare.usertips (camera,param,url,comment,value,added) 
	VALUES ('$camera','".mysql_real_escape_string($param)."','".mysql_real_escape_string($url)."','".mysql_real_escape_string($comment)."','".mysql_real_escape_string($value)."',NOW())";
$res = $wpdb->query($sql);
if ($res)
	echo "<span class='text-success'>Submit Success! Thank you for your participation. I'll check the data ASAP.</span>";
else
	echo "<span class='text-error'>Oops, submit failed. ".mysql_error()."</span>";

?>
