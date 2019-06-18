<?php
/* 
Template Name: BrowseTemplate 
*/
?>

<?php get_header(); ?>

<?php 
$permalink = "http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
?>

<h2>Browse Cameras</h2>

<div id="social">
	<div class='fb-like'><fb:like href="<?=$permalink?>" send="false" layout="box_count" width="450" show_faces="false"></fb:like></div>
	<div class='twitter'><a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical" data-via="">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
</div>
	
<p>Browse available DSLR and non-DSLR cameras that already reviewed at BestMemoryCard.net. Can't find the camera you're looking for? drop me a suggestion</p>

<h3>DSLR cameras</h3> 
<?php
global $wpdb;

$sql = "SELECT camera_name,img_url,id,slug FROM memorycard.mem_camera WHERE type='dslr' ORDER BY camera_name";

$res = $wpdb->get_results($sql);
echo "<ul class='thumbnails'>";
foreach($res as $row)
{
	$imgurl = $row->img_url;
	if (!file_exists($imgurl))
	{
		$imgurl = get_bloginfo('template_directory')."/no-photo.jpg";
	}

	echo "<li class='span2'>
		<a href='".get_bloginfo('url')."/camera/".$row->slug."/' class='thumbnail'><img src='".get_bloginfo('url')."/".$imgurl."'/>
		<p>".$row->camera_name."</p>
		</a></li>";
}
echo "</ul>";
?>

<h3>Non-DSLR cameras <small>mirrorless, pocket camera, micro four third, etc</small></h3> 
<?php
global $wpdb;

$sql = "SELECT camera_name,img_url,id,slug FROM memorycard.mem_camera WHERE type!='dslr' ORDER BY camera_name";

$res = $wpdb->get_results($sql);
echo "<ul class='thumbnails'>";
foreach($res as $row)
{
	$imgurl = $row->img_url;
	if (!file_exists($imgurl))
	{
		$imgurl = get_bloginfo('template_directory')."/no-photo.jpg";
	}

	echo "<li class='span2'>
		<a href='".get_bloginfo('url')."/camera/".$row->slug."/' class='thumbnail'><img src='".get_bloginfo('url')."/".$imgurl."'/>
		<p>".$row->camera_name."</p>
		</a></li>";
}
echo "</ul>";
?>

<?php get_footer(); ?>