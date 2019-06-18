<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<?php
include_once('moviefunction.php');

if (($terms = get_query_var('year')) != '')
{
	echo "<title>Watch ".$terms."s Movies Online | 8movie.net</title>";
}
else if (($terms = get_query_var('genre')) != '')
{
	echo "<title>Watch ".$terms." Movies Online | 8movie.net</title>";
}
else if (($terms = get_query_var('q')) != '')
{
	echo "<title>Watch ".ucwords(str_replace("-"," ",$terms))." Movies Online | 8movie.net</title>";
}
else if (($terms = get_query_var('watchid')) != '')
{
	echo "<title>Streaming now .. </title>";
	$noindex = "<meta name='robots' content='noindex,nofollow'/>";
}
else if (($terms = get_query_var('movieid')) != '')
{
	global $wpdb;
	$row = $wpdb->get_row("SELECT title FROM 8movie.movie WHERE id=".$terms);
	if ($row)
	{
		echo "<title>Watch Movie ".$row->title." Online Megavideo | 8movie.net</title>";
		$canonical = "<link rel='canonical' url='".lp($terms,$row->title)."'>";
	}
}
else
{
	?>
	<title><?php if ( is_single() ) { ?> | Blog Archive <?php } ?> <?php wp_title('|',true,'right'); ?><?php bloginfo('name'); ?> </title>
	<?php
}
?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link href='http://fonts.googleapis.com/css?family=Orienta' rel='stylesheet' type='text/css'>
<!--<link href="<?=get_bloginfo('template_directory')?>/fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet"  type="text/css" media="screen" />-->
<?=$noindex?>
<?=$canonical?>

<?php wp_head(); ?>
</head>


<body>
<div id="header">
	<h1><a href='<?php bloginfo('url'); ?>'><?php bloginfo('name'); ?></a><span class='beta'>gamma</span></h1>
</div>

<div id="wrapper">

<div id="navbar">
<ul>
	<li><a href='<?=get_bloginfo('url')?>'>Home</a></li>
	<li><a href='<?=get_bloginfo('url')."/browse/"?>'>Browse</a></li>
	<li><a href='<?=get_bloginfo('url')."/what-movie-should-i-watch-tonight/"?>'>Wmsiwt</a></li>
	<li><a href='<?=get_bloginfo('url')."/random/"?>'>Random</a></li>
</ul>
<div style='clear:both'></div>
</div>