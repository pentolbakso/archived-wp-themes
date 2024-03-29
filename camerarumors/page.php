<?php get_header(); ?>

<div id="main">

	<div class="post">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<h2><?php the_title(); ?></h2>
	<div class="post" id="post-<?php the_ID(); ?>">
		<div class="entry">
			<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

			<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

		</div>
	</div>

	<?php endwhile; endif; ?>
	
	</div>
	
</div>

<?php get_sidebar(); ?> 
<?php get_footer(); ?>