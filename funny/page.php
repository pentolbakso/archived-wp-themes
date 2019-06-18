<?php get_header(); ?>

<div class='col-md-8'><div class='post well'>


	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
	<h3><?php the_title(); ?></h3>
	
	<div class="post-entry">
		<?php the_content(''); ?>
	</div>

	<?php endwhile; endif; ?>

</div></div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>