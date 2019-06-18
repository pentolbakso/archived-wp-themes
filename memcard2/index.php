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
		<div class="span5">
			<div class='searchbox well'>
				<p style='text-align:center'>Get the recommended memory card for your camera now! Begin the search using form below</p>
				<form method="get" action="<?php bloginfo('url'); ?>/" class='form-search'>
					<input type='text' value="<?php the_search_query(); ?>" name="q" placeholder='Type Your Camera Here...' style='padding:10px;font-size:18px;' data-provide="typeahead" data-items="8" data-source='<?=$data?>' autocomplete="off">
					<input type='submit' value='Search' class='btn btn-primary' style='padding:10px;font-size:18px'>
				</form>
			</div>
		</div>
		<div class="span7">
		<p>
		Hello, welcome to BestMemoryCard.Net , a website dedicated to give you a guide on choosing best recommended memory cards for your camera and photography needs.
		For years, manufacturers produce SD memory cards in a range of memory capacities designed to fit your needs and budget. 
		When it comes to memory cards for digital cameras, there are many different options to choose from. You must keep in mind that certain cameras will only take one form of memory while others may allow you to use various storage options.
		You should consider how you will use a memory card to determine the right memory capacity and speed choice.
		Click or search your camera on my website, to learn about guides on choosing the right card.		
		</p>
		</div>
	</div>
</div>

<div class="content">

	<div class="recentcameras">
	<h2>Recent Cameras</h2>
	
<?php
$sql = "SELECT camera_name,img_url,id,slug FROM memorycard.mem_camera ORDER BY RAND() LIMIT 8";
$res = $wpdb->get_results($sql);

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
?>	
	<p style='text-align:right'><a class='btn btn-primary' href='<?=get_bloginfo('url')?>/browse/'><i class='icon icon-list icon-white'></i> Browse Other Cameras</a></p>

	</div>

	<div class="recentnews">
		<h2>Recent News</h2>

		<div>
	<?php
	$cat_id = get_category_by_slug('blog')->term_id;
	
	$str = "";
	query_posts(array('category__in' => array($cat_id),'posts_per_page' => 2));
	if (have_posts())
	{
		$str .= "<ul class='thumbnails'>";
		while (have_posts())
		{
			the_post();

			$argsThumb = array(
				'numberposts' 	 => 1,
				'order'          => 'ASC',
				'post_type'      => 'attachment',
				'post_parent'    => $post->ID,
				'post_mime_type' => 'image',
				'post_status'    => null
			);

			$attachments = get_posts($argsThumb);
			if ($attachments) {
				foreach ($attachments as $attachment) {
					$src = wp_get_attachment_image_src($attachment->ID, 'thumbnail');
					$thumbimg = '<div style="float:left; margin: 0 10px 5px 0"><img src="'.$src[0].'" /></div>';
					break;
				}
			}
			else
				$thumbimg="";

			$str .= "<li class='span6'>".$thumbimg."
				<a href='".get_permalink()."'><strong>".get_the_title()."</strong></a> 
				<p>".get_the_excerpt()."</p></li>";
		}
		$str .= "</ul>";
	}
	//wp_reset_query();
	echo $str;	
	?>
		</div>

		<p><a href='<?=get_bloginfo('url')?>/category/blog/'>Read more news</a></p>
	</div>

</div>
	
<div style="clear:both"></div>

<?php get_footer(); ?>