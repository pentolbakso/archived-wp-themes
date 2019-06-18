<?php
include "../../../wp-load.php";
include "myfunctions.php";

$com = trim($_POST['comment']);
$name = trim($_POST['name']);
$id = trim($_POST['id']);
if (strlen($com)==0)
{
	echo "<span class='text-error'>Comment should not be empty!</span>";
	return;
}
if ($_POST['iamhuman']!='yes')
{
	echo "<span class='text-error'>Comment failed. Please check the box to prove you're a human :)</span>";
	return;
}

$com = strip_tags($com);
$name = strip_tags($name);

global $wpdb;
if( function_exists('mysql_set_charset') )	mysql_set_charset('utf8');
else $wpdb->query("SET NAMES 'utf8'");

$sql = "INSERT INTO mp3.comments (youtubeid,name,comment,added) VALUES ('$id','".mysql_real_escape_string($name)."','".mysql_real_escape_string($com)."',NOW())";
$res = $wpdb->query($sql);

echo "<span class='text-success'>Comment success! If you want to see the comment, please refresh the browser</span>";

?>
