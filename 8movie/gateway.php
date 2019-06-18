<?php
include_once('moviefunction.php');
get_location($country,$city);
?>

<html>
<head>
<title>Gateway</title>
<meta name='robots' content='noindex,nofollow'/>
<link rel="stylesheet" href="style.css" type="text/css" media="screen" />

<style type='text/css'>
#gateway {
	color: #000;
	font-family: "Verdana";
	font-size: 14px;
	background-color: #eef9ff;
	padding:10px;
}
#gateway h2 {
	margin: 0;
	padding: 3px 0;
	color: #fff;
	font-size: 16px;
	background-color: #287098;
	
}
#gateway p {
	#width: 300px;
}
.cta{
	padding: 5px;
	background-color: #26892b;
	color: #fff;
	font-size: 18px;
	border-radius: 5px;
}
.cta a {
	color: #fff;
	font-weight: bold;
}
</style>

</head>
<body>
<div id='gateway'>

<?php if($country=='us' || $country=='fr' || $country=='uk' || $country=='ca' || $country=='be') { ?>
	<!-- US -->
<!--
	<h2>Error 500 : Codec Timeout</h2>
	<p>Description: Problem connecting to stream server.<br/>
	Please comeback and try again later.</p>
	
	<p>Sorry about that, we'll fix it ASAP!<br/>
	Here's a <a href='http://peerfly.com/click.php?img=0&id=3552&pf=33964&subid=&subid2=&subid3='>FREE Theater Ticket</a> for you for being awesome!!</p>
-->
        <h2>Error 100 : User not found</h2>
        <p>Some movies in our library are only licensed to 
		be streamed to certain countries.</p>
		<p>
		To see whether or not you're eligible to <strong>STREAM</strong>
		a movie, you must create an account</p>
		<p><center><span class='cta'><a href='http://www.affbuzzads.com/affiliate/index?ref=76660' target='_blank'>Create FREE Account Now</a></span></center></p>

<?php } else { ?>
	<!-- REST OF THE WORLD -->	
	<h2>Error 500 : Codec Timeout</h2>
	<p>Description: Problem connecting to stream server.</p>
	<p>
	looking for mirror server ...<br/>
	found file mirror on Graboid ...<br/>
	<!--<a href='<?//=get_bloginfo('template_directory')?>/get_graboid.php'>click here</a> to download the player.-->
	<a href='http://8movie.net/wp-content/themes/wwd/get_graboid.php?ref=player'>click here</a> to download the player
	</p>

<?php } ?>
</div>


</body>
</html>