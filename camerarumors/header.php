<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<?php
if (($terms = get_query_var('cameraid')) != '')
{
	global $wpdb;
	$sql = "SELECT camera_name FROM camerafollow.camera WHERE camera_id='".$terms."'";
	$cam = $wpdb->get_row($sql);
	echo "<title>".$cam->camera_name." Price History in US</title>";
}
else
{
	echo "<title>";
	if ( is_single()==false ) {  } wp_title('|',true,'right'); bloginfo('name'); 
	echo "</title>";
}
?>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link href='http://fonts.googleapis.com/css?family=Monda' rel='stylesheet' type='text/css'>
<link href="<?=get_bloginfo('template_directory')?>/favicon.ico" rel="shortcut icon" type="image/x-icon" />
<?php wp_head(); ?>
<script type="text/javascript">
<!--
function sidebarchange() 
{
	var leftSideBar = document.getElementById("sidebar").offsetHeight;
	var centerContentSection = document.getElementById("main").offsetHeight;

	if(leftSideBar < centerContentSection) 
	{
		newHeight = centerContentSection + "px";
	}
	else
	{
		newHeight = leftSideBar + "px";
	}
	document.getElementById("sidebar").style.height = newHeight;
}
-->
</script>

<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>

</head>

<body onload="sidebarchange()">
<div id="wrapper">

<div id="header">

	<div id="navbar">
	<ul>
		<li style='background-color:#3c3c3c;padding: 0px 15px;'><h1><a href='<?php bloginfo('url'); ?>'><?php bloginfo('name'); ?></a></h1></li>
		<li style='background-color:#4c4c4c'><a href='<?=get_bloginfo('url')?>/news'>Rumors and News</a></li>
		<li style='background-color:#5c5c5c'><a href='<?=get_bloginfo('url')."/photo-tips"?>'>Photo Tips</a></li>
		<li style='background-color:#6c6c6c'><a href='<?=get_bloginfo('url')."/prices"?>'>Price Trends</a></li>
		<li style='background-color:#8c8c8c'><a href='<?=get_bloginfo('url')."/buying-guide"?>'>Buying Guide</a></li>
	</ul>
	<div style='clear:both'></div>
	</div>
	
	<div id="featured">
	</div>

</div>	<!-- end header-->
