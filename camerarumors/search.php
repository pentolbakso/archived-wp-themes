<?php get_header(); ?>

	<div id="main">

	<?php if (have_posts()) : ?>

		<center><h2>Related posts about "<?php echo $s; ?>"</h2></center>
		<div style='margin-bottom: 10px'></div>

		<?php while (have_posts()) : the_post(); ?>
			<div class="post" id="post-<?php the_ID(); ?>">
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<div class="post-header">
					<span class="post-meta">posted on <?php the_time('j-M-Y'); ?></span>
					<span class="post-meta"><?php the_category("</span><span class='post-meta'>"); ?></span>
					<span class="post-meta"><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></span>
					<span class="post-meta"><?php edit_post_link('Edit'); ?></span>
				</div>
				<div class="entry">
					<?php the_excerpt(); ?>
				</div>
			</div>
		<?php endwhile; ?>

	<?php else : ?>

<h2 class="center">No posts found. Try a different search?</h2>
<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
<div><input type="text" value="<?php the_search_query(); ?>" name="s" id="s" size="30" />
<input type="submit" id="searchsubmit" value="Search" />
</div>
</form>

	<?php endif; ?>

	</div>

<?php get_sidebar(); ?>
  
<?php get_footer(); ?>