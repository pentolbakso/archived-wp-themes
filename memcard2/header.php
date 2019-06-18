<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<?php
global $the_title,$the_noindex,$the_canonical,$the_description;

if (strlen($the_title)==0)
{
	if (is_archive())
	{
		$cats = get_the_category();
		$PAGETITLE = $cats[0]->cat_name." | ".get_bloginfo("name");
	}
	else if (is_home())
	{
		$PAGETITLE = get_bloginfo("name")." - ".get_bloginfo("description");
	}
	else
		$PAGETITLE = get_the_title()." | ".get_bloginfo("name");

	?>
	<title><?=$PAGETITLE?></title>
	<?php
}
else
{
	echo "<title>".$the_title." | ".get_bloginfo("name")."</title>";
}
?>
<?=$the_description?>
<?=$the_noindex?>
<?=$the_canonical?>
<?php wp_head(); ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">


<link href='http://fonts.googleapis.com/css?family=Monda' rel='stylesheet' type='text/css'>
<link href="http://fonts.googleapis.com/css?family=Bree+Serif" rel="stylesheet" type="text/css">
<link rel="shortcut icon" type="image/x-icon" href="<?=get_bloginfo('template_directory')?>/favicon.ico">
<link rel="stylesheet" href="<?=get_bloginfo('template_directory')?>/bootstrap/css/cosmo-bootstrap.min.css" type="text/css"/>
<link rel="stylesheet" href="<?=get_bloginfo('template_directory')?>/bootstrap/css/bootstrap-responsive.css" type="text/css"/>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<!--<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.1.1/css/bootstrap-combined.min.css" rel="stylesheet">-->
<!--<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">-->
<!-- Script -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<!--<script src="<?=get_bloginfo('template_directory')?>/bootstrap/js/jquery.min.js"></script>-->
<script src="<?=get_bloginfo('template_directory')?>/bootstrap/js/bootstrap.min.js"></script>
<!--<script src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.1.1/js/bootstrap.min.js"></script>-->
</head>

<body>

<!-- FB stuff -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=103597163066273";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- END FB stuff -->

<div class="container" id="main">

<div class="container">
	<div class='row'>
		<div class='span5'>
			<a href='<?=get_bloginfo('url')?>/'><h1 style='letter-spacing:-2px;border-bottom: 5px solid #000;padding-bottom: 8px'>Best Memory Card</h1></a>
		</div>
		<div class='span7' style='padding-top:10px'>
			<div class="navbar">
			  <div class="navbar-inner">
			  	<div class='container'>
			    <ul class="nav">
			      <li><a href="<?=get_bloginfo('url')?>/"><i class='icon icon-home icon-white'></i> Home</a></li>
			      <li><a href="<?=get_bloginfo('url')?>/finder/"><i class='icon icon-file icon-white'></i> Card Finder</a></li>
			      <li><a href="<?=get_bloginfo('url')?>/learn/"><i class='icon icon-check icon-white'></i> Learn</a></li>
			    </ul>
				</div>
			  </div>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class='row'>
		<div class='span12'>