<?php
/* 
Template Name: Redirect
*/

$go= get_query_var('go');
$camera_id= get_query_var('cameraid');
global $wpdb;

$sql = "SELECT url FROM camerafollow.items_schedule WHERE merchant='amazon_us' AND item_shortname='".$camera_id."'";
$row = $wpdb->get_row($sql);
if ($wpdb->num_rows==0)
{
	echo "<h1>Ooops, we have problem</h1>";
	return;
}

//http://www.amazon.com/dp/B003ZYF3LO/?tag=beckism-20
$path = parse_url($row->url, PHP_URL_PATH);
$info = explode("/",$path);
$ASIN = $info[3];
$AFFID = "camrumor-20";

if ($go=='compare')
{
	$url = "http://www.amazon.com/gp/offer-listing/".$ASIN."/?tag=".$AFFID;
}
else if ($go=='review')
{
	$url = "http://www.amazon.com/product-reviews/".$ASIN."/?tag=".$AFFID;
}
else
{
	$url = "http://www.amazon.com/dp/".$ASIN."/?tag=".$AFFID;
}

header("Location: ".$url);

?>