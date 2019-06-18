<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
	<div class="post well" id="post-<?php the_ID(); ?>">
		<h3>Blog: <?php the_title(); ?></h3>
		<div class="post-header">
			<span class="post-meta">posted on <?php the_time('j-M-Y'); ?></span>
			<span class="post-meta"><?php edit_post_link('Edit'); ?></span>
		</div>
		<div class="entry">
			<?php the_content('<p class="serif">Read more &raquo;</p>'); ?>
		</div>

	</div>

	<?php endwhile; endif; ?>
	
<?php get_footer(); ?>