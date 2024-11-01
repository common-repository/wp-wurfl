<?php 
// Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die (__('Please do not load this page directly. Thanks!','wp-wurfled'));
        if (!empty($post->post_password)) { // if there's a password
            if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
				?>
				<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.','wp-wurfled'); ?><p>
				<?php
				return;
            }
        }
		/* This variable is for alternating comment background */
		$oddcomment = 'alt';
?>
<!-- You can start editing here. -->
<?php if ($comments) : ?>
	<p><b><?php comments_number(__('No Responses','wp-wurfled'), __('One Response','wp-wurfled'), __('% Responses','wp-wurfled')); print(' '); print(__('to','wp-wurfled')); print(' &#8220;'); the_title(); ?></b></p> 
	<?php foreach ($comments as $comment) : ?>
	<p>
			<i><?php comment_author_link() ?></i> <?php _e('Says:','wp-wurfled'); ?>
			<?php if ($comment->comment_approved == '0') : ?>
			<em><?php _e('Your comment is awaiting moderation.','wp-wurfled'); ?></em>
			<?php endif; ?>
			<br />
			<small class="commentmetadata"><a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date(__('F jS, Y','wp-wurfled')); print" "; _e('at','wp-wurfled'); print " "; comment_time() ?></a> <?php edit_comment_link('e','',''); ?></small>
			<br />
			<?php comment_text() ?>
		</p>
	<?php /* Changes every other comment to a different class */	
		if ('alt' == $oddcomment) $oddcomment = '';
		else $oddcomment = 'alt';
	?>
	<?php endforeach; /* end for each comment */ ?>
 <?php else : // this is displayed if there are no comments so far ?>
  <?php if ('open' == $post->comment_status) : ?> 
		<!-- If comments are open, but there are no comments. -->
	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments"><?php _e('Comments are closed.','wp-wurfled'); ?></p>
	<?php endif; ?>
<?php endif; ?>
<?php if ('open' == $post->comment_status) : ?>
<p><b><?php _e('Leave a Reply','wp-wurfled'); ?></b></p>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p><?php echo sprintf("You must be %slogged in%s to post a comment.", "<a href=\"" . get_option('siteurl') . "/wp-login.php?redirect_to=" . get_permalink() . "\">", "</a>"); ?></p>
<?php else : ?>
<? if(false){ ?>
<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
<?php if ( $user_ID ) : ?>
<p><?php _e('Logged in as','wp-wurfled'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account','wp-wurfled'); ?>"><?php _e('Logout','wp-wurfled'); ?> &gt;</a></p>
<?php else : ?>
<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
<label for="author"><small><?php _e('Name','wp-wurfled'); ?> <?php if ($req) echo "(" . __('required','wp-wurfled') . ")"; ?></small></label></p>
<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
<label for="email"><small><?php _e('Mail (will not be published)','wp-wurfled'); ?> <?php if ($req) echo "(" . __('required','wp-wurfled') . ")"; ?></small></label></p>
<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
<label for="url"><small><?php _e('Website','wp-wurfled'); ?></small></label></p>
<?php endif; ?>
<!--<p><small><strong>XHTML:</strong> <?php _e('You can use these tags:','wp-wurfled'); ?> <?php echo allowed_tags(); ?></small></p>-->
<p><textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea></p>
<p><input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Submit Comment','wp-wurfled'); ?>" />
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
</p>
<?php do_action('comment_form', $post->ID); ?>
</form>
<?}?>
<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>
