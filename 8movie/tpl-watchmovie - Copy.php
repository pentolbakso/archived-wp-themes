<?php
include('moviefunction.php');

$id= get_query_var('watchid');
$movietitle= get_query_var('title');

global $wpdb;
$row = $wpdb->get_row("SELECT * FROM 8movie.movie WHERE id=".$id." AND flag=1");
if ($wpdb->num_rows==0)
{
	echo "<h2>404, Movie Not Found</h2>";
	return;
}


$introfile = array('centuryfoxhd.mp4','columbiahd.mp4','disneyhd.mp4','dolbyhd.mp4','dreamworkhd.mp4','mgmcolor.mp4',
	'miramaxhd.mp4','oldcountdownhd.mp4','paramounthd.mp4','tristarhd.mp4','universalhd.mp4','warnerbroshd.mp4');
shuffle($introfile);
$file = $introfile[0];
$runtime = $row->rt_runtime;
if (strlen($runtime)==0) $runtime = rand(70,120);
$runtime = $runtime * 60;
$timeout = rand(9,10);

/*
$file = "oldcountdownhd.mp4";
$runtime = $row->rt_runtime;
if (strlen($runtime)==0) $runtime = rand(70,120);
$runtime = $runtime * 60;
$timeout = rand(11,13);
*/?>

<?php get_header(); ?>

<div class='blackscreen'>
<h2><?=$row->title?></h2>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="<?=get_bloginfo('template_directory')?>/fancybox/jquery.fancybox-1.3.4.js"></script>
<script type="text/javascript" src="<?=get_bloginfo('template_directory')?>/fancybox/fancybox-settings.js"></script>

<script type='text/javascript' src='<?=get_bloginfo('url')?>/jwplayer/jwplayer.js'></script>
<center><div id='mediaspace'>Loading...</div></center>
<a class='gateway' href='<?=get_bloginfo('template_directory')?>/gateway.php'></a>
 
<script type='text/javascript'>
  var done = 0;
  jwplayer('mediaspace').setup({
    'flashplayer': '<?=get_bloginfo('url')?>/jwplayer/player.swf',
    'file': '<?=get_bloginfo('url')?>/jwplayer/<?=$file?>',
    'skin': '<?=get_bloginfo('url')?>/jwplayer/newtubedark.zip',    
	'image': '<?=clip($row->clip_img1)?>',
    'autostart': 'true',
    'controlbar': 'bottom',
	'stretching': 'exactfit',
	'duration': '<?=$runtime?>',
    'width': '800',
    'height': '450'
  });
  jwplayer('mediaspace').onTime(function(evt) {
	pos=evt.position;
	if(pos><?=$timeout?> && done==0) {
		done=1;
		setTimeout(function() {	fireGateway();} ,10000);
		}
  }); 
  function fireGateway() {
	//jwplayer().pause();
	$(".gateway").trigger('click');
  };
</script>

</div>
<?php get_footer(); ?>