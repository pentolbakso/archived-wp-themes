<?php
if (session_id() == "") session_start();

if (!isset($_SESSION["is_mobile"])) {
  require_once 'Mobile-Detect-2.7.8/Mobile_Detect.php';
  $detect = new Mobile_Detect;
  if ($detect->isMobile()) $_SESSION["is_mobile"]=1;
  else $_SESSION["is_mobile"]=0;
}

if (!isset($_SESSION["user_id"]) && isset($_COOKIE["kuekering"])) {

  //cek db ada ga session tsb
  global $wpdb;
  $sql = $wpdb->prepare("SELECT * FROM lontong.users WHERE loginsession='%s'",$_COOKIE["kuekering"]);
  $row = $wpdb->get_row($sql);
  if (!$row) {
    //kok ga ada?? delete cookie boongan neh
    unset($_COOKIE['kuekering']);
    setcookie('kuekering', '', time() - 3600);
  } else {

    //adaaa ..login automatis ah
    if (strlen($row->avatar)==0)
      $avatarimg = get_bloginfo("template_directory")."/anon.jpg";
    else if (stripos($row->avatar, "facebook.com")!==FALSE)
      $avatarimg = $row->avatar;
    else
      $avatarimg = get_bloginfo("url")."/avatar/".$row->avatar;

    $wpdb->query("UPDATE lontong.users SET lastlogin=NOW() WHERE id=$row->id");

    $_SESSION["user_id"] = $row->id;
    $_SESSION["user_photo"] = $avatarimg;
    $_SESSION["user_display"] = $row->displayname;
  }

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<?php
//include_once('myfunctions.php');
global $the_title,$the_meta;

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
?>

<?php wp_head(); ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?=$the_meta?>

<link rel="shortcut icon" type="image/x-icon" href="<?=get_bloginfo('template_directory')?>/lontongnet.ico">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootswatch/3.0.3/cosmo/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?=get_bloginfo('template_directory')?>/basic.css" type="text/css" media="screen" />
<link href="https://fonts.googleapis.com/css?family=Droid+Sans" rel="stylesheet" type="text/css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
</head>

<body>

<div class='container'>

<div class='row' style='margin-top:20px'><div class='col-md-12'>
<nav class="navbar navbar-default" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="<?=get_bloginfo('url')?>">LontongNET</a>
  </div>
 <!-- Collect the nav links, forms, and other content for toggling -->
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
      <li class="dropdown <?=(($_SESSION["channel"]!="all")?'active':'')?>"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-caret-down"></i> <?=(($_SESSION["channel"]!="all")?ucfirst($_SESSION["channel"]):'Channel')?></a>
        <ul class="dropdown-menu">
<?php
$res = $wpdb->get_results("SELECT * FROM lontong.channel");
echo "<li><a href='".get_bloginfo("url")."/?channel=all'>All</a></li>";
foreach($res as $row)
  echo "<li><a href='".get_bloginfo("url")."/?channel=".$row->slug."'>$row->name</a></li>";
?>
        </ul>
      </li>
      <li <?=(($_SESSION["mode"]=="hot")?"class='active'":"")?>><a href="<?=get_bloginfo('url')?>"><i class="fa fa-fire"></i> Hot</a></li>
      <li <?=(($_SESSION["mode"]=="new")?"class='active'":"")?>><a href="<?=get_bloginfo('url')?>/?new">New</a></li>
      <li <?=(($_SESSION["mode"]=="random")?"class='active'":"")?>><a href="<?=get_bloginfo('url')?>/?random">Random</a></li>
      <li <?=(($_SESSION["mode"]=="top")?"class='active'":"")?>><a href="<?=get_bloginfo('url')?>/?top">Top</a></li>
    </ul>  
    <ul class="nav navbar-nav navbar-right">
      <!--<li><a href="#" data-toggle="modal" data-target="#uploadModal"><span class="fa fa-upload"></span> Upload</a></li>-->
      <li><a href="<?=get_bloginfo('url')?>/upload/" title="Upload gambar atau video"><i class="fa fa-upload"></i> &nbsp;</a></li>
      <?php
        if (isset($_SESSION["user_id"])) {
          $photo = "<img src='".$_SESSION["user_photo"]."' style='width:30px;height:30px' />";
      ?>
      <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle toprightuser" data-toggle="dropdown"><?=$photo?> <?=$_SESSION["user_display"]?></a>
        <ul class="dropdown-menu">
            <li><a href="<?=get_bloginfo('url')?>/u/<?=$_SESSION['user_id']?>/">Profile</a></li>
            <li><a href="<?=get_bloginfo('url')?>/upload/">Upload</a></li>
            <li><a href="<?=get_bloginfo('url')?>/logout/">Logout</a></li>
        </ul>
      </li>
      <?php
      } else {
      ?>
      <li><a href="<?=get_bloginfo('url')?>/login/"><i class="fa fa-lock"></i> Login</a></li>
      <?php
    }
    ?>
    </ul>
  </div><!-- /.navbar-collapse -->
</nav>
</div></div>

<div class='row'><div id='main'>