<?php
$id = get_query_var('yid');

global $wpdb;
$sql = "SELECT * FROM mp3.ytentry WHERE youtubeid='".$id."'";
$row = $wpdb->get_row($sql);
$duration = ((int)($row->length/60))." minutes and ".($row->length%60)." seconds";

if (strlen($row->title)==0)
{
	//ga ada datanya / blacklist ?
	wp_redirect(get_bloginfo('url')."/not-found/");
	return;
}
else if (strlen($row->title) != strlen(utf8_decode($row->title)))
{
	wp_redirect(get_bloginfo('url')."/not-found/");
	return;
}


global $the_title,$the_description;
$the_title = "Download Free $row->title as MP3, 3GP, WMV or MP4 format";
$the_description = "Click to begin downloading ".sanitize_title($row->title).".mp3 ! Choose appropriate file format and download to your mobile phone.";
?>

<?php get_header(); ?>

<div class='span8'>

<?php include "searchbox.php"; ?>

<h2>Download <?=$row->title?></h2>

<p>Listen or download <?=$row->title?> free in MP3, MP4, 3GP, WMV, OGG high quality format (128 or 320kbps depends on the stream).
<?=$row->content?>. The song / video run for around <?=$duration?>.
Click on links on the bottom of this page to begin the download.
</p>

<div style='margin: 20px 0;background-color:#000'><center><img src="http://i.ytimg.com/vi/<?=$row->youtubeid?>/hqdefault.jpg"/></center></div>

<p class='mediainfo' style='text-align:center'><i class='icon icon-music'></i>Download As: 
	<a href='<?=get_bloginfo('url')?>/dl/mp3/<?=$id?>/' target='_blank' class='btn btn-download'>MP3</a>
	<a href='<?=get_bloginfo('url')?>/dl/mp4/<?=$id?>/' target='_blank' class='btn btn-download'>MP4</a>
	<a href='<?=get_bloginfo('url')?>/dl/3gp/<?=$id?>/' target='_blank' class='btn btn-download'>3GP</a>
	<a href='<?=get_bloginfo('url')?>/dl/wmv/<?=$id?>/' target='_blank' class='btn btn-download'>WMV</a>
	or <a href='#playthemusic' class='btn btn-play' data-toggle='modal' yid='<?=$id?>'><i class='icon icon-play'></i> Play</a>
</p>

<div id="social" style='max-width:300px;margin:0px auto'>
	<!-- AddThis Button BEGIN -->
	<div class="addthis_toolbox addthis_default_style">
	<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
	<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
	<a class="addthis_counter addthis_pill_style"></a>
	</div>
	<!-- AddThis Button END -->
</div>

<!--
<p style='text-align:center;margin:10px 0'><a href='http://clk.adgatemedia.com/aff_c?offer_id=5025&aff_id=6280' rel='nofollow'><img src='http://i.imgur.com/FxUKyMV.png'/></a></p>
-->

<p class='muted' style='font-size:12px;margin-top:20px'>What are these formats ? MP3 is a popular file extension for an audio file. MP4 is most commonly used to store digital video and digital audio streams.
3GP designed as a multimedia format for transmitting audio and video files between 3G cell phones and over the Internet.</p>

<p style='text-align:center'>
<!--Copy and paste the code below into the location on your website where the ad will appear.-->
<script type='text/javascript'>
var adParams = {a: '11920181', size: '468x60',serverdomain: 'ads.adk2.com'   };
</script>
<script type='text/javascript' src='http://cdn.adk2.com/adstract/scripts/smart/smart.js'></script>
</p>

<h2 style='margin-top:30px'>For People Who Love This Song</h2>

<p>
	Say something about it !! It's free and NO signup needed
	<form class='form-inline'>
		<input type='text' name='name' id='name' class='input-small' placeholder='Anonymous'/>
		<input type='text' name='comment' id='comment' class='input-xlarge' placeholder='Your comment here...max 200 char'/>
		<input type='hidden' name='yid' id='yid' value='<?=$id?>'/>
		<input type='checkbox' name='iamhuman' id='iamhuman'/> Yes, i am human!
		<input class='btn' type='button' id='sendcomment' Value='Say!' style='background-color:#6B7459;color:#fff'/>
	</form>

	<p id='commentprogress' style='text-align:center'></p>

	<?php
	$sql = "SELECT * FROM mp3.comments WHERE youtubeid='$id' ORDER BY added DESC LIMIT 20";
	$res = $wpdb->get_results($sql);
	if ($wpdb->num_rows==0)
	{
		echo "<p class='muted'>Noone say anything about this song.. Be the first!</p>";
	}
	else
	{
		echo "<strong>$wpdb->num_rows comment(s):</strong><br/>";
		foreach($res as $r)
		{
			if ($r->name=='') $user='Anonymous';
			else $user = $r->name;

			echo "<blockquote><p>$r->comment</p><small>$user</small></blockquote>";
		}
	}
	?>

</p>


<h2 style='margin-top:30px'>Other Songs</h2>

<?php
$sql = "SELECT youtubeid,title,LENGTH FROM mp3.ytentry WHERE id > FLOOR(RAND()*(SELECT MAX(id) FROM mp3.ytentry)) LIMIT 5";
$res = $wpdb->get_results($sql);

echo "<table class='table table-condensed table-result'>";
foreach($res as $r)
{

	$clip = "http://i.ytimg.com/vi/".$r->youtubeid."/1.jpg";
	$title = $r->title;
	$length = $r->length;
	$html .= format_output($r->youtubeid,$clip,$title,$length);
}
echo $html;
echo "</table>";
?>

</div> <!-- end span8 -->

<div id='playthemusic' class='modal hide noborder'>
	<div id='playthemusiccontent'></div>

	<div id="social" style='max-width:300px;margin:0px auto;margin-bottom: 10px'>
		<!-- AddThis Button BEGIN -->
		<div class="addthis_toolbox addthis_default_style">
		<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
		<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
		<a class="addthis_counter addthis_pill_style"></a>
		</div>
		<!-- AddThis Button END -->
	</div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>