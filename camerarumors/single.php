<?php get_header(); ?>

<div id="main">

	<?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
	<div class="post" id="post-<?php the_ID(); ?>">
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<div class="post-header">
			<span class="post-meta">posted on <?php the_time('j-M-Y'); ?></span>
			<span class="post-meta"><?php the_category("</span><span class='post-meta'>"); ?></span>
			<span class="post-meta"><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></span>
			<span class="post-meta"><?php edit_post_link('Edit'); ?></span>
		</div>
		<p>
			<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode(get_permalink($post->ID)); ?>&amp;layout=button_count&amp;show_faces=true&amp;width=90&amp;action=like&amp;font=segoe+ui&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:21px;" allowTransparency="true"></iframe>&nbsp;&nbsp;
			<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="camerarumors">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
			<g:plusone size="medium"></g:plusone>
		</p>		
		<div class="entry">
			<?php the_content(''); ?>
		</div>
		
		<?php
		$postDate = strtotime(get_the_date());  
		//$todayDate = strtotime(date( 'Y-m-d' )); 
		if ($postDate > strtotime("2012-09-20"))
		{					
			$posttags = get_the_tags();
			$count=0;
			if ($posttags) 
			{
				echo "<div class='relatedkw'>Related terms: ";
				foreach($posttags as $tag) 
				{
					echo "<a href='".makeSearchURL($tag->name)."'>".$tag->name."</a>";
					
					if ($count < count($posttags)-1)
						echo " , ";
						
					$count++;
				}
				echo "</div>";
			}
		}
		?>
		
	</div>
	<?php endwhile; ?>
	<?php else : ?>
		<h2 class="center">Not Found</h2>
		<p class="center">Sorry, but you are looking for something that isnt here.</p>
	<?php endif; ?>

<?php comments_template(); ?>

</div>
	
<?php get_sidebar(); ?>

<!--<div style="clear:both"></div>-->

<?php get_footer(); ?>