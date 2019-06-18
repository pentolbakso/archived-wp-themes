<?php
/*
Template Name: Random Movie
*/
include('moviefunction.php');
global $wpdb;
$row = $wpdb->get_row("SELECT id,title FROM 8movie.movie WHERE flag=1 AND id > FLOOR(RAND()*(SELECT MAX(id) FROM 8movie.movie)) LIMIT 1");
if ($row)
{
	$url = lp($row->id,$row->title);
	header("Location: ".$url);
	//echo $url;
}
?>