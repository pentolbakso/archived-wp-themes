<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?=get_bloginfo("name")?></title>
<meta name="description" content="<?=get_bloginfo('description')?>">
<?php wp_head(); ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" type="image/x-icon" href="<?=get_bloginfo('template_directory')?>/favicon.ico">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Sniglet" rel="stylesheet" type="text/css">
</head>

<body>
<section id="header"><div class='container'>
<nav class="navbar navbar-default" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="<?=get_bloginfo('url')?>">TK Anak Ceria</a>
  </div>
 <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav navbar-right">
      <li><a href='#'><i class="fa fa-home"></i></a></li>
      <li><a href='#features'>Program</a></li>
      <li><a href='#activity'>Aktifitas</a></li>
      <li><a href='#' data-toggle="modal" data-target="#signupModal">Pendaftaran</a></li>
      <li><a href='#address'>Lokasi</a></li>
      <li><a href='#address'>Kontak Kami</a></li>
    </ul>
  </div><!-- /.navbar-collapse -->
</nav>
</div></section>