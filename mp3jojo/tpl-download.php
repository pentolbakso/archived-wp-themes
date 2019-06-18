<?php
include_once('myfunctions.php');

$type = strtolower(get_query_var('type'));
$id = get_query_var('did');

global $wpdb;
$row = $wpdb->get_row("SELECT * FROM mp3.ytentry WHERE youtubeid='".$id."'");

$yurl = "http://www.youtube.com/watch?v=".$id;

$url = "http://www.music-clips.net/v/".urlencode(urlencode($yurl))."/".$type."/";	//ndableg wisss
//echo $url;
//http://www.music-clips.net/v/http%253A%252F%252Fwww.youtube.com%252Fwatch%252Fv%252FuuZE_IRwLNI/mp3/
//http://www.music-clips.net/v/http%253A%252F%252Fwww.youtube.com%252Fwatch%252Fv%253FuuZE_IRwLNI/mp3/
//http://www.music-clips.net/v/http%253A%252F%252Fwww.youtube.com%252Fwatch%253Fv%253DMYOmjQO_UMw/mp3/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name='robots' content='noindex,nofollow'/>
<title>Download As <?=strtoupper($type)?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="<?=get_bloginfo('template_directory')?>/bootstrap/css/cosmo-bootstrap.min.css" type="text/css"/>
<link rel="stylesheet" href="<?=get_bloginfo('template_directory')?>/bootstrap/css/bootstrap-responsive.css" type="text/css"/>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

<style>
</style>
</head>

<body>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=103597163066273";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>	

<div class="container">
<div class="row">
	<div class="span6 offset3">
		<div class='well' style='margin-top: 50px'>
			<h2>Just a little bit more.. </h2>
			<div class='well'>
				<p>While waiting, join our Facebook Fan Page. Share and let the world knows your music interests.</p>
				<div class="fb-like-box" data-href="https://www.facebook.com/Mp3jojo" data-width="292" data-show-faces="false" data-stream="false" data-show-border="false" data-header="true"></div>
			</div>
			<p id='progress'>connecting .. <img src='<?=get_bloginfo('template_directory')?>/loader.gif'/></p>
		</div>
		<div>
			<p style='text-align:center'>
			<!--Copy and paste the code below into the location on your website where the ad will appear.-->
			<script type='text/javascript'>
			var adParams = {a: '11920181', size: '468x60',serverdomain: 'ads.adk2.com'   };
			</script>
			<script type='text/javascript' src='http://cdn.adk2.com/adstract/scripts/smart/smart.js'></script>
			</p>
		</div>
	</div>
</div>
</div>

<!-- Script -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<!--<script src="<?=get_bloginfo('template_directory')?>/bootstrap/js/jquery.min.js"></script>-->
<!--<script src="<?=get_bloginfo('template_directory')?>/bootstrap/js/bootstrap.min.js"></script>-->
<!--<script src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.1.1/js/bootstrap.min.js"></script>-->

<script type="text/javascript">

setTimeout(function() {
  //window.location.href = "<?=$url?>";
  var data = "Download ready -> <a href='<?=$url?>'><u>Link</u></a>";
  $('#progress').html(data);

}, 5000);

</script>

</body>

</html>