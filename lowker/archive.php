<?php get_header(); ?>

<?php include('searchbox.php'); ?>

<div class="container"><div class="row"><div class="span12">
	<div class="well">
	<h2 style='text-align:center'>Blog LowonganIndonesia.Net</h2>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div class="post" id="post-<?php the_ID(); ?>">
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<span class="post-meta mylabel">posted on <?php the_time('j-M-Y'); ?></span>
		<div class="entry">
			<?php the_excerpt('<p class="serif">Read more &raquo;</p>'); ?>
		</div>
	</div>

	<?php endwhile; endif; ?>
	
	<div class="pagenavi">
		<?php if(get_previous_posts_link()): ?><div class="alignleft"><?php previous_posts_link('&laquo; Newer Entries') ?></div><?php endif; ?>
		<?php if(get_next_posts_link()): ?><div class="alignright"><?php next_posts_link('Older Entries &raquo;') ?></div><?php endif; ?>
		<div style='clear:both'></div>
	</div>

	</div>

</div></div></div>

<?php get_footer(); ?>