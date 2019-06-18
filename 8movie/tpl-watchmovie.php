<?php
include_once('moviefunction.php');

$id =get_query_var('watchid');
global $wpdb;
$row = $wpdb->get_row("SELECT title,imdb_rating,rt_runtime,imdb_storyline,trailer_url,poster_img FROM 8movie.movie WHERE id=".$id);

$introfile = array('centuryfoxhd.mp4','columbiahd.mp4','disneyhd.mp4','dreamworkhd.mp4','mgmcolor.mp4',
	'miramaxhd.mp4','paramounthd.mp4','tristarhd.mp4','universalhd.mp4','warnerbroshd.mp4');
shuffle($introfile);
$file = get_bloginfo('url')."/jwplayer/".$introfile[0];

//$file = "dolbyhd.mp4";
$durasi = ($row->runtime>30)?$row->runtime:rand(80,120);
$runtime = $durasi*60;
$timeout = 10;

get_location($country,$city);
if($country=='us' || $country=='fr' || $country=='uk' || $country=='ca' || $country=='be' || $country=='br' || $country=='de' || $country=='be' || $country=='nl' || $country=='es' || $country=='ar' || $country=='za' || $country=='au' || $country=='it') 
{
	$htmlcontent = '<a href="http://www.affbuzzads.com/affiliate/index?ref=76682" target="_blank"><img src="'.get_bloginfo('template_directory').'/img/login.png" width="420" height="300" /></a>';
}
else
{
	$htmlcontent = '<a href="'.get_bloginfo('url').'/go-graboid/1/"><img src="'.get_bloginfo('template_directory').'/img/graboid.png" width="420" height="300" /></a>';
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name='robots' content='noindex,nofollow'/>
<title>Watch <?=$row->title?></title>
<script type="text/javascript" src="<?=get_bloginfo('template_directory')?>/flowplayer/flowplayer-3.2.11.min.js"></script>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link href='http://fonts.googleapis.com/css?family=Kreon' rel='stylesheet' type='text/css'>
<style>
#player-wrapper { margin: 30px auto; text-align:center;}
#player { 
	margin: 0px auto;
	width: 800px;
	padding: 5px 3px;
	border-radius: 5px;
	-moz-border-radius: 5px;

	background: rgb(69,72,77); /* Old browsers */
	/* IE9 SVG, needs conditional override of 'filter' to 'none' */
	background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iIzQ1NDg0ZCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiMwMDAwMDAiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
	background: -moz-linear-gradient(top,  rgba(69,72,77,1) 0%, rgba(0,0,0,1) 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(69,72,77,1)), color-stop(100%,rgba(0,0,0,1))); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top,  rgba(69,72,77,1) 0%,rgba(0,0,0,1) 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top,  rgba(69,72,77,1) 0%,rgba(0,0,0,1) 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top,  rgba(69,72,77,1) 0%,rgba(0,0,0,1) 100%); /* IE10+ */
	background: linear-gradient(to bottom,  rgba(69,72,77,1) 0%,rgba(0,0,0,1) 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#45484d', endColorstr='#000000',GradientType=0 ); /* IE6-8 */
}
#player h3 { margin: 2px 0; color: #fff;}
#info { margin: 10px auto 0px; text-align: left; color: #555; width: 800px; letter-spacing: 1px; font-size: 14px;}
#info p { font-size: 12px; }
#poster { float: left;}
#label-hd { margin-left: 10px; padding: 0px 5px; background-color: #0000bf; color: #fff; font-style: italic; border-radius: 5px; -moz-border-radius: 5px;}
#label-720p { margin-left: 10px; padding: 0px 5px; background-color: #60bf03; color: #fff; font-style: italic; border-radius: 5px; -moz-border-radius: 5px;}
#label-durasi { margin-left: 10px; padding: 0px 5px; background-color: #7f003f; color: #fff; font-style: italic; border-radius: 5px; -moz-border-radius: 5px;}
</style>
<!--[if gte IE 9]>
  <style type="text/css">
    .gradient {
       filter: none;
    }
  </style>
<![endif]-->
</head>

<body class='gradient'>
	
<div id='player-wrapper'>
	<div id='player'>
		<a href="<?=$file?>" id="theplayer"></a> 
		<script>
			flowplayer("theplayer", {
				src: "<?=get_bloginfo('template_directory')?>/flowplayer/flowplayer-3.2.15.swf",
				width: "800px",
				height: "450px",
			}, {
				clip: { 
					duration: <?=$runtime?>, 
					onCuepoint: [<?=$timeout*1000?>, function() {
						this.pause();
						this.getPlugin('mycontent').animate({'opacity': 1});
						}],
					//Will not allow player to resume on a time larger than 4 seconds
					onBeforeResume: function() {
						if (this.getStatus().time >= <?=$timeout?>)	return false;
						else return true;
					},
					//Deny seeking
					onBeforeSeek: function(target) {
						return false;
					}
				},
				plugins: {
					mycontent: {
						url: "<?=get_bloginfo('template_directory')?>/flowplayer/flowplayer.content-3.2.8.swf",
						//Location of display:
						height: 320,
						padding: 4,
						width: 450,
						top: 60,
						opacity: 0,
						backgroundColor: 'transparent',

						html: '<?=$htmlcontent?>',
						//html: '<p>Hello World?</p><p><a href="http://www.google.com">Download Codec</a></p>',
						opacity: 0
					}
				}
			});
		</script>
	</div>	
	<div id='info'>
		<strong><?=$row->title?></strong><span id='label-hd'>HD</span><span id='label-720p'>720p</span><span id='label-durasi'><?=$durasi?> min</span>
		<p>Synopsis: <?=substr($row->imdb_storyline,1,400)?> ...</p>
		<p><a href='http://www.affbuzzads.com/affiliate/index?ref=76682'>Tired of searching movies? Watch UNLIMITED movies now !!
		FREE TRIAL valid until <?=date('F j, Y')?></a> (use coupon code: <?=strtoupper(substr(md5($row->title),1,6))?>)</p>
	</div>
</div>
	
</body>
</html>