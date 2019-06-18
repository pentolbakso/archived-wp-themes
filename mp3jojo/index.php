<?php
global $the_description;
$the_description = get_bloginfo('title')." is a free mp3/mp4 search engine for you. No signup required!";
?>

<?php get_header(); ?>

<div class='span12'>

<?php include "searchbox.php"; ?>


<div id="social" style='max-width:400px;margin:20px auto'>
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<!-- AddThis Button END -->
</div>


<?php
global $wpdb;
$row = $wpdb->get_row("SELECT * FROM mp3.apicache WHERE apiname='lastfm.topartist'");
$json = $row->rawdata;

$arr = json_decode($json,TRUE);

$tot = count($arr['artists']['artist']);

echo "<div id='artists'><ul class='unstyled'>";
for ($i=0; $i<$tot; $i++)
{
	$artist = $arr['artists']['artist'][$i]['name'];
	$thumb = $arr['artists']['artist'][$i]['image'][1]['#text'];	//medium

	echo "<li><a href='".searchurl($artist)."' title='Songs by $artist' alt='$artist'><img src='$thumb'/></a></li>";

}
echo "<ul><div style='clear:both'></div></div>";
?>
</div>

<div class='span4'>
	<h3>Recent Searches</h3>
	<ul class='inline'>
		<?php
		$res = $wpdb->get_results("SELECT keyword FROM mp3.searchkw WHERE formsearch=1 ORDER BY added DESC LIMIT 25");
		foreach($res as $r)
		{
			echo "<li><a href='".searchurl($r->keyword)."'>".$r->keyword."</a></li>";
		}
		?>
	</ul>
</div>

<div class='span4'>
	<h3>Recent Downloads</h3>
	<ul class='inline'>
		<?php
		
		$res = $wpdb->get_results("SELECT keyword FROM mp3.searchenginekw ORDER BY added DESC LIMIT 25");
		foreach($res as $r)
		{
			//echo "<li><a href='".searchurl($r->keyword)."'>".$r->keyword."</a></li>";
			echo "<li>".$r->keyword."</li>";
		}
		
		?>
	</ul>
</div>

<div class='span4'>
	<h3>Hot Songs</h3>
	<ul class='inline'>
	<?php

	$row = $wpdb->get_row("SELECT * FROM mp3.apicache WHERE apiname='lastfm.gettoptracks'");
	$json = $row->rawdata;

	$arr = json_decode($json,TRUE);
	$tot = count($arr['tracks']['track']);
	for ($i=0; $i<10; $i++)
	{
		$song = $arr['tracks']['track'][$i]['name'];
		$artist = $arr['tracks']['track'][$i]['artist']['name'];

		$kw = $song." ".$artist;
		echo "<li><a href='".searchurl($kw)."'>$song by $artist</a></li>";
	}
	echo "<li><a href='".get_bloginfo('url')."/latest/'>more</a>";
	?>	
	</ul>

</div>

<?php get_footer(); ?>