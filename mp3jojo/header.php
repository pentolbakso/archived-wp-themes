<?php header('Content-Type: text/html;charset=utf-8'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<?php
include_once('myfunctions.php');
global $the_title,$the_noindex,$the_canonical,$the_description;

if (strlen($the_title)==0) {
	if (is_archive()) 	{
		$cats = get_the_category();
		$PAGETITLE = $cats[0]->cat_name." | ".get_bloginfo("name");
	} else if (is_home()) {
		$PAGETITLE = get_bloginfo("name")." - ".get_bloginfo("description");
	}
	else $PAGETITLE = get_the_title()." | ".get_bloginfo("name");

?>
<title><?=$PAGETITLE?></title>
<?php
}
else {
echo "<title>".$the_title." | ".get_bloginfo("name")."</title>"; 
}

if ($the_description!="")
echo "
<meta name='description' content='$the_description'>";
?>

<?=$the_noindex?>
<?=$the_canonical?>
<?php wp_head(); ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="shortcut icon" type="image/x-icon" href="<?=get_bloginfo('template_directory')?>/favicon.ico">
<link href='https://fonts.googleapis.com/css?family=Cousine' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?=get_bloginfo('template_directory')?>/bootstrap/css/cosmo-bootstrap.min.css" type="text/css"/>
<link rel="stylesheet" href="<?=get_bloginfo('template_directory')?>/bootstrap/css/bootstrap-responsive.css" type="text/css"/>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<!--<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.1.1/css/bootstrap-combined.min.css" rel="stylesheet">-->
<!--<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">-->
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

<div class='row'>
	<div class='span12'>	
		<!--<h1><a href='<?=get_bloginfo('url')?>'><?=get_bloginfo('title')?></a></h1>-->
		<a href='<?=get_bloginfo('url')?>'><img src='<?=get_bloginfo('template_directory')?>/logo.jpg' alt='<?=get_bloginfo('title')?>'/></a>
	</div>
</div>

<div class='row'>