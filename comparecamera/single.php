<?php get_header(); ?>

	<div id='pagecontent'>
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<div class='post' id="post-<?php the_ID(); ?>">
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<div class="post-header">
			<span class="post-meta">posted on <?php the_time('j-M-Y'); ?></span>
			<span class="post-meta"><?php edit_post_link('Edit'); ?></span>
		</div>
		<div class="entry">
			<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
		</div>
	</div>

	<?php endwhile; endif; ?>

	</div>

<?php get_footer(); ?>