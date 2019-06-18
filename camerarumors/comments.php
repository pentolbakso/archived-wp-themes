<!-- You can start editing here. -->

<?php if ('open' == $post->comment_status) : ?>

<div id="respond">

<h3><?php comment_form_title( 'Leave a Reply', 'Leave a Reply to %s' ); ?></h3>

<div class="cancel-comment-reply">
	<small><?php cancel_comment_reply_link(); ?></small>
</div>


<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

<?php if ( $user_ID ) : ?>

<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Log out &raquo;</a></p>

<?php else : ?>

<p><label for="author"><small>Name <?php if ($req) echo "(required)"; ?>:</small></label><br/>
<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
</p>

<p><label for="email"><small>Mail (will not be published)<?php if ($req) echo "(required)"; ?>:</small></label><br/>
<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
</p>

<p><label for="url"><small>Website:</small></label><br/>
<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
</p>

<?php endif; ?>

<!--<p><small><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></small></p>-->

<p><label for="comment"><small>Your Comment:</small></label><br/>
<textarea name="comment" id="comment" cols="30" rows="5" tabindex="4"></textarea></p>

<p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit Comment" class="formbutton" />
<?php comment_id_fields(); ?>
</p>
<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; // If registration required and not logged in ?>
</div>

<?php endif; // if you delete this the sky will fall on your head ?>

<?php if ($comments) : ?>
	<div id="responddata">
		<h3 id="comments"><?php comments_number('No comment :(', 'One comment', '% comments' );?> so far</h3>

		<ul class="commentlist">
		<?php wp_list_comments(); ?>
		</ul>
	</div>

 <?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments">Comments are closed.</p>

	<?php endif; ?>
<?php endif; ?>

<div style="clear:both"></div>