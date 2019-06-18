<?php
/*
Template Name: Delete (hidden)
*/

return;

global $wpdb;

$sql = "SELECT keyword FROM mp3.blacklist WHERE enabled=1";
$res = $wpdb->get_results($sql);
foreach($res as $b)
{
	$sql2 = "DELETE FROM mp3.ytentry WHERE title LIKE '%".$b->keyword."%'";
	$wpdb->query($sql2);
	echo "DELETED $b->keyword<br/>";
}

echo "DONE";

?>