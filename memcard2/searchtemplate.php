<?php
/* 
Template Name: SearchTemplate 
*/
?>

<?php
include_once "myfunctions.php";
$q= get_query_var('q');

global $the_title,$the_noindex,$the_canonical,$the_description;
$the_title = "Search camera $q";
?>

<?php get_header(); ?>

<?php
global $wpdb;
$res = $wpdb->get_results("SELECT camera_name FROM memorycard.mem_camera ORDER BY camera_name ASC");
$data = "[";
foreach($res as $row)
	$data .= "&quot;".$row->camera_name."&quot;,";
$data = substr_replace($data,"",-1);
$data .= "]";
?>

<div class="container">
	<div class="row">
		<div class="span12">
			<div class='searchbox well'>
				<p style='text-align:center'>Get the recommended memory card for your camera now! Begin the search using form below</p>
				<form method="get" action="<?php bloginfo('url'); ?>/" class='form-search'>
					<input type='text' value="<?php the_search_query(); ?>" name="q" placeholder='Type Your Camera Here...' style='padding:10px;font-size:18px;' data-provide="typeahead" data-items="8" data-source='<?=$data?>' autocomplete="off">
					<input type='submit' value='Search' class='btn btn-primary' style='padding:10px;font-size:18px'>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="content">

	<div class="popular">
	<h3>Search result for '<?=$q?>' :</h3>

	<p>Click on camera image to learn about its memory capacity and recommended cards</p>
	
<?php

$sql = "SELECT * FROM memorycard.mem_camera WHERE camera_name LIKE '%".$q."%'";

$res = $wpdb->get_results($sql);
if ($wpdb->num_rows==0)
{
	echo "<p class='alert alert-error'>Ooops, we cannot find the camera you're looking for. 
	The camera is probably not available in our database yet. Meanwhile, please try again using different terms/camera</p>";
}
else
{
	echo "<ul class='thumbnails'>";
	foreach($res as $row)
	{
		$imgurl = $row->img_url;
		if (!file_exists($imgurl))
		{
			$imgurl = get_bloginfo('template_directory')."/no-photo.jpg";
		}

		echo "<li class='span3'>
			<a href='".get_bloginfo('url')."/camera/".$row->slug."/' class='thumbnail'><img src='".$imgurl."'/>
			<p>".$row->camera_name."</p>
			</a></li>";
	}
	echo "</ul>";
}
?>	
	</div>

</div>
	
<div style="clear:both"></div>

<?php get_footer(); ?>