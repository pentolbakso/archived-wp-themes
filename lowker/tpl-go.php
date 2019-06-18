<?php
/*
Template Name: Go
*/
$id = get_query_var("goid");
global $wpdb;

$row = $wpdb->get_results("SELECT url FROM job.jobs WHERE id=".$id." LIMIT 1");
$url = $row[0]->url;

header("Location: ".$url);
?>