<?php get_header(); ?>

<?php is_tag(); ?>
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); 

			$argsThumb = array(
				'numberposts' 	 => 1,
				'order'          => 'ASC',
				'post_type'      => 'attachment',
				'post_parent'    => $post->ID,
				'post_mime_type' => 'image',
				'post_status'    => null
			);

			$attachments = get_posts($argsThumb);
			if ($attachments) {
				foreach ($attachments as $attachment) {
					$src = wp_get_attachment_image_src($attachment->ID, 'thumbnail');
					$thumbimg = '<div style="float:left; margin: 0 10px 5px 0"><img src="'.$src[0].'" /></div>';
					break;
				}
			}
			else
				$thumbimg="";
		?>
			<div class="postcontent">
				<h2 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<div class="postmeta">
					<i class='icon icon-time'></i> <small><?php the_time('F jS, Y') ?> | <?php the_category(', ') ?></small>
				</div>
				<?=$thumbimg?><p><?php the_excerpt(''); ?></p><div style="clear:both"></div>
			</div>
		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php previous_posts_link('&laquo; Newer Entries') ?></div>
			<div class="alignright"><?php next_posts_link('Older Entries &raquo;') ?></div>
		</div>

	<?php else : ?>

		<h2 class="center">Not Found</h2>

	<?php endif; ?>

<?php get_footer(); ?>
