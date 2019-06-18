<?php
/* 
Template Name: LearnTemplate 
*/
?>
<?php get_header(); ?>

<?php
$permalink = "http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
?>

<h2>Learn the Basics</h2>

<div id="social">
	<div class='fb-like'><fb:like href="<?=$permalink?>" send="false" layout="box_count" width="450" show_faces="false"></fb:like></div>
	<div class='twitter'><a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical" data-via="">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
</div>

<p>Hi, welcome to BestMemoryCard.Net learn center. Here you can find articles and tips on how to select the best recommended memory card
for your camera. Click the title to learn more. <br/>
If you have any ideas, suggestion on what to put here , please let me know. Thanks and have fun learning</p>

<?php

	$cat_id = get_category_by_slug("Articles")->term_id;
	
	$str = "";
	query_posts(array('category__in' => array($cat_id),'posts_per_page' => $count));
	if (have_posts())
	{
		$str .= "<ul class='thumbnails'>";
		while (have_posts())
		{
			the_post();
			$str .= "<li class='span6'>";
			$str .= "<h3><a href='".get_permalink()."'>".get_the_title()."</a></h3>";
			$str .= "<p><i class='icon icon-bookmark'></i> ".get_the_excerpt()."</p>";
			$str .= "</li>";
		}
		$str .= "</ul>";
	}
	wp_reset_query();

	echo $str;
?>

<?php get_footer(); ?>
