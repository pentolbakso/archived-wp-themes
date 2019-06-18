<?php get_header(); ?>

<div class='span8'>

<?php include "searchbox.php"; ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<div class='post'>
	<h2><?php the_title(); ?></h2>
	<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

	<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
</div>

<?php endwhile; endif; ?>

</div> <!-- end span8 -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>