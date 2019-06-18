<?php
/*
Template Name: Newest
*/
global $the_title,$the_description;
$the_title = "[MP3] Latest MP3s - Updated Daily";
?>

<?php get_header(); ?>

<div class='span12'>
<?php include "searchbox.php"; ?>
</div>

<div id="social" style='max-width:300px;margin:0px auto'>
	<!-- AddThis Button BEGIN -->
	<div class="addthis_toolbox addthis_default_style">
	<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
	<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
	<a class="addthis_counter addthis_pill_style"></a>
	</div>
	<!-- AddThis Button END -->
</div>

<div class='span6'>
	<h3>New Releases</h3>
	<ul class='unstyled'>
	<?php

	$row = $wpdb->get_row("SELECT * FROM mp3.apicache WHERE apiname='apple.newreleases'");
	$json = $row->rawdata;
	//echo $json;
	$arr = json_decode($json,TRUE);
	//print_r($arr);
	for ($i=0; $i<count($arr); $i++)
	{
		$kw = $arr[$i][0];
		echo "<li><a href='".searchurl($kw)."'>".$kw."</a></li>";
	}
	?>	
	</ul>

</div>

<div class='span6'>
	<h3>Hot Songs</h3>
	<ul class='unstyled'>
	<?php

	$row = $wpdb->get_row("SELECT * FROM mp3.apicache WHERE apiname='lastfm.gettoptracks'");
	$json = $row->rawdata;

	$arr = json_decode($json,TRUE);
	$tot = count($arr['tracks']['track']);
	for ($i=0; $i<$tot; $i++)
	{
		$song = $arr['tracks']['track'][$i]['name'];
		$artist = $arr['tracks']['track'][$i]['artist']['name'];

		$kw = $song." ".$artist;
		echo "<li><a href='".searchurl($kw)."'>$song by $artist</a></li>";
	}
	?>	
	</ul>

</div>

<?php get_footer(); ?>