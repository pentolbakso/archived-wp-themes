<?php get_header(); ?>

<?php
$permalink = "http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
?>

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
<div class="postcontent">
	<h2 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
	<div class="postmeta">
		<i class='icon icon-time'></i> <small><?php the_time('F jS, Y') ?> | <?php the_category(', ') ?> <?php edit_post_link('| Edit'); ?>  </small>
	</div>
	<div id="social">
		<div class='fb-like'><fb:like href="<?=$permalink?>" send="false" layout="box_count" width="450" show_faces="false"></fb:like></div>
		<div class='twitter'><a href="http://twitter.com/share" class="twitter-share-button" data-count="vertical" data-via="">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
	</div>
	<p><?php the_content(''); ?></p>	
</div>
<?php endwhile; ?>
<?php else : ?>
	<h2 class="center">Not Found</h2>
	<p class="center">Sorry, but you are looking for something that isnt here.</p>
<?php endif; ?>

<?php get_footer(); ?>