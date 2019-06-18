<?php
include_once "myfunctions.php";

$cam1 = get_query_var("cam1");
$cam2 = get_query_var("cam2");
$theurl = "http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

$NOINDEX = "";
$HEADLINE = "Compare Camera";
if ($cam1 != "" && $cam2 != "")
{
	//need better solution
	$cam1 = ucwords(str_replace("-"," ",$cam1));
	$cam2 = ucwords(str_replace("-"," ",$cam2));
	$HEADLINE = $cam1."&nbsp;vs&nbsp;".$cam2;

	$slug = versus_slug($cam1,$cam2);
	$row = $wpdb->get_row("SELECT pagetitle,pagedesc,pagekeyword FROM compare.summary WHERE slug='".$slug."'");

	if ($row)
	{
		$PAGETITLE = $row->pagetitle." | ".get_bloginfo("name");;
		$PAGEDESC = $row->pagedesc;
		$PAGEKEYWORD = $row->pagekeyword;
	}
	else
	{
		$PAGETITLE = $cam1." vs ".$cam2." : Which one ?";
		$PAGEDESC = "Learn which one is better. ".$cam1." or ".$cam2." ? Specs, Reviews, Sample Images/Videos, Accessories, and Price History";
	}

	//need better solution
	$cam1 = ucwords(str_replace("-"," ",$cam1));
	$cam2 = ucwords(str_replace("-"," ",$cam2));
	$HEADLINE = $cam1."&nbsp;vs&nbsp;".$cam2;

}
else if (get_query_var("s1")!="" && get_query_var("s2")!="")
{
	$PAGETITLE = "Search ".$s1." vs ".$s2." | ".get_bloginfo("name");;
	$PAGEDESC = "Search results on ".$s1." vs ".$s2;
	$PAGEKEYWORD = $s1." vs ".$s2.",compare camera,".$s1.",".$s2.",search result";
}
else
{
	if (is_archive())
	{
		$cats = get_the_category();
		$PAGETITLE = $cats[0]->cat_name." | ".get_bloginfo("name");
	}
	else if (is_home())
	{
		$PAGETITLE = get_bloginfo("name")." - ".get_bloginfo("description");
		$PAGEDESC = "Compare two cameras : key specs / feature, reviews, sample images/videos, and price history";
		$PAGEKEYWORD = "compare camera,camera comparison, price history, sample images, camera versus";
	}
	else
		$PAGETITLE = get_the_title()." | ".get_bloginfo("name");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<title><?=$PAGETITLE?></title>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php if (strlen($PAGEDESC)>0) { ?><meta name="description" content="<?=$PAGEDESC?>">
<?php } ?>
<?php if (strlen($PAGEKEYWORD)>0) { ?><meta name="keywords" content="<?=$PAGEKEYWORD?>"><?php } ?>
<?=$NOINDEX?>

<link href="<?=get_bloginfo('template_directory')?>/favicon.ico" rel="shortcut icon" type="image/x-icon" />
<link href='http://fonts.googleapis.com/css?family=Arbutus+Slab' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
<link href="//netdna.bootstrapcdn.com/bootswatch/3.0.0/united/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link href="<?=get_bloginfo('template_directory')?>/js/feedbackBadge.css" rel="stylesheet">


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="<?=get_bloginfo('template_directory')?>/flot/jquery.flot.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<script src="<?=get_bloginfo('template_directory')?>/js/typeahead.min.js"></script>
<script src="<?=get_bloginfo('template_directory')?>/js/jquery.feedbackBadge.min.js"></script>
<?php wp_head(); ?>
</head>

<body style='padding-top:50px'>

<div class='navbar navbar-fixed-top one-edge-shadow'>
	<div class='navbar-inner' style='border-bottom:none'>
		<div style='text-align:center;'>
			<h1 style='line-height: 20px'><?=$HEADLINE?></h1>
		</div>
		<div class='social-button-container'>
			<iframe src="http://www.facebook.com/plugins/like.php?href=<?=urlencode($theurl)?>&amp;layout=button_count&amp;show_faces=true&amp;width=90&amp;action=like&amp;font=segoe+ui&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:21px;" allowTransparency="true"></iframe>&nbsp;&nbsp;
			<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="comparecamera">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
			<g:plusone size="medium"></g:plusone>
			<su:badge layout="1" location="<?=$theurl?>"></su:badge>
		</div>	
	</div>
</div>

