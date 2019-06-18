<?php
/* 
Template Name: CameraTemplate 
*/
?>


<?php 
include "myfunctions.php";

global $wpdb;

$id= get_query_var('id');
$sql = "SELECT * FROM memorycard.mem_camera WHERE slug='".$id."'";
$row = $wpdb->get_row($sql);

$rawcap = calculate_raw($row->raw_size_mb,$row->res_width,$row->res_height);
$jpegcap = calculate_jpeg($row->jpeg_size_mb,$row->res_width,$row->res_height);
$rec_normal = $row->recommend_normal;
$rec_video = $row->recommend_video;
$rec_action = $row->recommend_action;

$modecount = 0;
$array = explode("\n",$row->video_mode);
$vidcap = calculate_video_duration($array,$modecount);
$imgurl = $row->img_url;
if (!file_exists($imgurl))
{
	$imgurl = get_bloginfo('template_directory')."/no-photo.jpg";
}
else
{
	$imgurl = get_bloginfo('url')."/".$imgurl;
}

global $the_title,$the_noindex,$the_canonical,$the_description;
$the_title = "Recommended Memory Card for ".$row->camera_name;

$permalink = "http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
?>

<?php get_header(); ?>

<h2>Recommended Memory Card for <?=$row->camera_name?></h2>

<div id="social">
	<div class='fb-like'><fb:like href="<?=$permalink?>" send="false" layout="box_count" width="450" show_faces="false"></fb:like></div>
	<div class='twitter'><a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical" data-via="">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
</div>

<p>Get to know the recommended memory cards for <?=$row->camera_name?>. Learn its storage capacity, class rating, compatible cards
and tips on how choosing cards based on your photography needs (normal, video or sports).
The <?=$row->camera_name?> support following types: <?=($row->sd>0)?'SD':''?>,<?=($row->sdhc>0)?'SDHC':''?>,<?=($row->sdxc>0)?'SDXC':''?>

<div class="row">
	<div class="span5">
		<img src='<?=$imgurl?>'/>	
	</div>
	<div class="span7">
		<p><?php echo nl2br($row->specs); ?></p>
	</div>
</div>

<h3>Photos Capacity (number of shots)</h3>

<div class="row">
	<div class="span9">
		<table class='table table-striped table-bordered'>
			<tr><th>&nbsp;</th><th colspan='6' style='text-align:center'>Memory Card Size</th></tr>
			<tr><th>Image Quality</th><th>2 GB</th><th>4 GB</th><th>8 GB</th><th>16 GB</th><th>32 GB</th><th>64 GB</th></tr>
			<tr><td>RAW</td><td><?=$rawcap['2gb']?></td><td><?=$rawcap['4gb']?></td><td><?=$rawcap['8gb']?></td><td><?=$rawcap['16gb']?></td><td><?=$rawcap['32gb']?></td><td><?=$rawcap['64gb']?></td></tr>
			<tr><td>Fine JPEG</td><td><?=$jpegcap['2gb']?></td><td><?=$jpegcap['4gb']?></td><td><?=$jpegcap['8gb']?></td><td><?=$jpegcap['16gb']?></td><td><?=$jpegcap['32gb']?></td><td><?=$jpegcap['64gb']?></td></tr>
		</table>
	</div>
	<div class="span3">
		<p style='font-size:13px'>How many photos can your card hold? The higher the number of megapixels/camera's resolution the larger the image file, and hence the smaller the number of files you can store.</p>
	</div>
</div>

<h3>Video Capacity (duration)</h3>
<div class="row">
	<div class="span9">
		<table class='table table-striped table-bordered'>
			<tr><th>&nbsp;</th><th colspan='6' style='text-align:center'>Memory Card Size</th></tr>
			<tr><th>Video Mode</th><th>2 GB</th><th>4 GB</th><th>8 GB</th><th>16 GB</th><th>32 GB</th><th>64 GB</th></tr>
<?php
	$notfound = FALSE;

	if ($row->can_record_video>0)
	{

	for ($i=0; $i < $modecount; $i++)
	{
		if ($vidcap[$i]['2gb']=="*") $notfound = TRUE;

		echo "<tr><td>".$vidcap[$i]['mode']."</td>
			<td>".$vidcap[$i]['2gb']."</td>
			<td>".$vidcap[$i]['4gb']."</td>
			<td>".$vidcap[$i]['8gb']."</td>
			<td>".$vidcap[$i]['16gb']."</td>
			<td>".$vidcap[$i]['32gb']."</td>
			<td>".$vidcap[$i]['64gb']."</td>
			</tr>";			
	}

	}
	else
	{
		echo "<tr><td colspan='7'>This camera unable to record video</td></tr>";
	}
?>
		</table>
	</div>
	<div class="span3">
		<p style='font-size:13px'>How long can your card record video? That depends on video resolution (SD,HD or Full HD) and video bitrate.</p>
	</div>
</div>

<p style='font-size:13px'>1h 20m = video duration of 1 hour and 20 minutes</p>

<?php
	if ($notfound)
	{
		echo "<p style='font-size:13px;color: #999'>(*) video bitrate data for $row->camera_name not available yet. help me, please <a href='#suggestModal' data-toggle='modal'>tip me</a> if you know the bitrate.</p>";
	}
?>

<div>
	<h3>General Choosing Tips</h3>
	<ul>
		<li>Choose memory card based on your capacity need (see table above)</li>
		<li>If you're shooting videos alot, choose memory card with higher class rating. Class 2 is suitable for SD format, while Class 4 and Class 6 can record HD video. Class 10 is the ultimate</li>
		<li>If you're shooting hi-res RAW photos, you'll need a quick card to take more than two or three shots at a time. Choose fast cards to smooth the process. Recommended minimum Class 6</li>
		<li>Pick known brand. They're slighly more expensive, but also more reliable. Lexar and SanDisk are recommended.</li>
		<li>Get a second memory card as backup. You'll never know what gonna happen on the field</li>
		<li>and makes sure the card is compatible with your camera :)</li>
	</ul>
</div>

<h3>Recommendations:</h3>

<div class="row">
	<div class="span4">
<?php
    $img_url = "";
    $str = get_recommendation($rec_normal,$img_url);
    if ($str!="")
    {
	echo "
	<div class='recommend well'>
	    <img src='".get_bloginfo('url')."/".$img_url."'/>
	    <strong>For Daily Usage</strong> For normal usage, balance combination between taking photos and video recording. 
	    <div style='clear:both'></div>".$str."
	    
	</div>";
    }
?>
	</div>
	<div class="span4">
<?php
    $img_url = "";
    $str = get_recommendation($rec_video,$img_url);
    if ($str!="")
    {
	echo "<div class='recommend well'>
	    <img src='".get_bloginfo('url')."/".$img_url."'/>
	    <strong>For Video Recording</strong> Recording videos required fast card and also enough storage capacity. 
	    <div style='clear:both'></div>".$str."
	</div>";
    }
?>
	</div>
	<div class="span4">
<?php
    $img_url = "";
    $str = get_recommendation($rec_action,$img_url);
    if ($str!="")
    {
	echo "<div class='recommend well'>
	    <img src='".get_bloginfo('url')."/".$img_url."'/>
	    <strong>For Actions (sport, burst)</strong> Professional cards that deliver high speed performance and reability. 
	    <div style='clear:both'></div>".$str."
	</div>";
    }
?>
	</div>
</div>

<h3>Supported Cards</h3>

<p>The <?=$row->camera_name?> supports the following memory card type. Click to browse available cards:</p>

<table border='0' id='supportedcards'>
	<?php if ($row->sd>0) { ?>
		<tr><td><img src='<?=get_bloginfo('template_directory')."/img/sd.gif"?>'/></td><td><a href='<?=get_bloginfo('url')."/cards/sd-for-".$row->slug."/"?>'>SD card</a></td></tr>
	<?php } if ($row->sdhc>0) { ?>
		<tr><td><img src='<?=get_bloginfo('template_directory')."/img/sdhc.gif"?>'/></td><td><a href='<?=get_bloginfo('url')."/cards/sdhc-for-".$row->slug."/"?>'>SDHC card</a></td></tr>
	<?php } if ($row->sdxc>0) { ?>
		<tr><td><img src='<?=get_bloginfo('template_directory')."/img/sdxc.gif"?>'/></td><td><a href='<?=get_bloginfo('url')."/cards/sdxc-for-".$row->slug."/"?>'>SDXC card</a></td></tr>
	<?php } ?>
</table>

<?php get_footer(); ?>
