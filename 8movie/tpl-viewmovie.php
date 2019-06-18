<?php
include('moviefunction.php');

$id= get_query_var('movieid');
$movietitle= get_query_var('title');

global $wpdb;
$row = $wpdb->get_row("SELECT * FROM 8movie.movie WHERE id=".$id." AND flag=1");
if ($wpdb->num_rows==0)
{
	echo "<h2>404, Movie Not Found</h2>";
	return;
}

$theurl = "http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

$rating = $row->imdb_rating;
if ($rating == 0)
	$rating = rand(30,70)/100;

?>

<?php get_header(); ?>

<div id='movieshowdown'>

	<div style='background-color: #fff; padding: 5px 15px'>
		<h2 style='margin: 0px'><?php echo $row->title; ?></h2>
		<div><?=rating($rating,$row->rt_audience_score)?></div>
		<!--<p><a href='http://www.imdb.com<?=$row->imdb_url?>' target='_blank'>IMDB Link</a></p>-->
	</div>
	<div class='moviepromo'>
		<a href='<?=get_bloginfo('url')."/watch/".$row->id."/"?>' rel='noindex' target='_blank'>
		
<!--
	<?php if (strlen($row->clip_img1)>0) { ?>
		<div class='player' style='background-image:url(<?=clip($row->clip_img1)?>);background-size:100%'>
			<img src="<?=get_bloginfo('template_directory')?>/img/playbutton.png" alt="Play this movie">
		</div>
	<?php }	else { ?>
		<div class='player2'>
			<img src="<?=get_bloginfo('template_directory')?>/img/playnow.gif" alt="Play this movie">
		</div>
	<?php } ?>
-->
		<div class='player2'>
			<img src="<?=get_bloginfo('template_directory')?>/img/playnow.gif" alt="Play this movie" style="height:100%">
		</div>
		</a>
	</div>

</div>

<div id="page">

<div class='social-button-container'>
	<iframe src="http://www.facebook.com/plugins/like.php?href=<?=urlencode($theurl)?>&amp;layout=button_count&amp;show_faces=true&amp;width=90&amp;action=like&amp;font=segoe+ui&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:21px;" allowTransparency="true"></iframe>&nbsp;&nbsp;
	<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
</div>	

<div class='movieinfo'>
	<img src='<?=poster($row->poster_img)?>'>
	<p><strong>Storyline: </strong><?=$row->imdb_storyline?></p>
	<p style="font-size:13px"><?=parse(random_welcome($row->id,$row->title),$row)?></p>
	<div style='clear:both'></div>
</div>

<div class='movietrailer'>
	<div>
		<h3 style='padding: 10px 20px 5px;font-size: 20px'><?=noyear($row->title)?> Trailer</h3>
		<center><?=trailer($row->trailer_url)?></center>
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
	<strong>Watch Online in HD Now</strong>
	<br/>Total Views: <?=rand(500,5000)?>
	<br/>Views Today: <?=rand(10,50)?>
	<br/><br/><a href='<?=get_bloginfo('url')."/watch/".$row->id."/"?>' rel='noindex' target='_blank'><img src='<?=get_bloginfo('template_directory')?>/img/watchnow.gif' style='width: 250px'></a>
</div>
<div style='clear:both'></div>
</div>

</div>

<div class='moviesocial'>
<h3 style='font-size:20px'>Other Streaming Links</h3>

	<table class='movielinks'>
		<tr><th>Link</th><th>Viewer Rating</th><th>Source</th></tr>
		<?php
		$provider = array('Megavideo', 'Putlocker', 'VideoBB', 'Videoweed', 'Movshare', 'Sockshare', 'Novamov',
			'Filebox','Vidxden','Nosvideo','Videoslasher','Nowvideo','Vidbux');

		foreach ($provider as $p)
			echo "<tr><td>Watch Online!</td><td>n/a</td><td>".$p."</td></tr>";
		?>
	</table>

</div>

</div> <!-- end page -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>