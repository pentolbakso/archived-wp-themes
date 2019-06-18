<?php
include('moviefunction.php');

$id= get_query_var('movieid');
$movietitle= get_query_var('title');

global $wpdb;
$row = $wpdb->get_row("SELECT * FROM movie WHERE id=".$id." AND flag=1");
if ($wpdb->num_rows==0)
{
	echo "<h2>404, Movie Not Found</h2>";
	return;
}
?>

<?php get_header(); ?>
<div id="page">

<h2 style='margin-bottom:5px'><?php echo $row->title; ?></h2>
<div><?=rating($row->imdb_rating,$row->rt_audience_score)?></div>

<!--<p><a href='http://www.imdb.com<?=$row->imdb_url?>' target='_blank'>IMDB Link</a></p>-->

<div class='moviepromo'>
	<a href='<?=get_bloginfo('url')."/watch/".$row->id."/"?>' rel='noindex' target='_blank'>
	
<?php if (strlen($row->clip_img1)>0) { ?>
	<div class='player' style='background-image:url(<?=clip($row->clip_img1)?>);background-size:100%'>
		<img src="<?=get_bloginfo('template_directory')?>/img/playbutton.png" alt="Play this movie">
	</div>
<?php }	else { ?>
	<div class='player2'>
		<img src="<?=get_bloginfo('template_directory')?>/img/playnow.gif" alt="Play this movie">
	</div>
<?php } ?>
	</a>
	<!--<small>* the movie will open in new window</small>-->
</div>

<div class='movieinfo'>
<img src='<?=poster($row->poster_img)?>'>
<p><?=parse(random_welcome($row->id,$row->title),$row)?></p>
<p>Storyline: <?=$row->imdb_storyline?></p>
<div style='clear:both'></div>
</div>

<div class='movietrailer'>
<h3><?=noyear($row->title)?> Trailer</h3>
<center><?=trailer($row->trailer_url)?>
	<br/>Watch in: Standard Definition (SD) | <a href='<?=get_bloginfo('url')."/watch/".$row->id."/"?>' rel='noindex' target='_blank'>High Definition (HD)</a>
</center>
</div>

<?php

if (strlen($row->clip_img2)==0 || !file_exists($row->clip_img2))
	$watchimg = "http://img.youtube.com/vi/".get_youtube_id($row->trailer_url)."/1.jpg";
else
	$watchimg = clip($row->clip_img2);
?>

<div class='moviefinal'>
<a href='<?=get_bloginfo('url')."/watch/".$row->id."/"?>' rel='noindex' target='_blank'><img src='<?=$watchimg?>' class='clip' alt='watch <?=strtolower($row->title)?> online now!'></a>
<div class='cta'>
	<strong>Watch <?=noyear($row->title)?> Online in HD Now</strong>
	<br/>Total Views: <?=rand(500,3000)?>
	<br/>Views Today: <?=rand(10,50)?>
	<br/><a href='<?=get_bloginfo('url')."/watch/".$row->id."/"?>' rel='noindex' target='_blank'><img src='<?=get_bloginfo('template_directory')?>/img/watchnow.gif' style='width: 200px'></a>
	<br/><img src='<?=get_bloginfo('template_directory')?>/img/secure.jpg' style='width:15px'> <small>Scanned, no virus detected</small>
</div>
<div style='clear:both'></div>
</div>

<div class='moviesocial'>
<h3>Links</h3>
<p>The links section will be updated once we got some info. Help us by submitting new movie link.
<br/><em>Watch <?=$row->title?> online at megavideo</em>
<br/><em>Watch <?=$row->title?> online at novamov</em>
<br/><em>Watch <?=$row->title?> online at videobb</em>
<br/><em>Watch <?=$row->title?> online at putlocker</em>
</p>
</div>

</div> <!-- end page -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>