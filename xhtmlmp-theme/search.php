<?php get_header(); ?>
<div id="content">
	<?php if (have_posts()) : ?>
		<div class="pagetitle"><?php _e('Search Results','wp-wurfled'); ?></div>
		<?php while (have_posts()) : the_post(); ?>
			<div class="post">
				<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo sprintf(__('Permanent Link to %s','wp-wurfled'),the_title('','',FALSE)); ?>"><?php the_title(); ?></a></h3>
				<small><?php the_time(__('l, F jS, Y','wp-wurfled')) ?></small>
				<div class="entry">
					<?php the_excerpt(); ?>
				</div>	
				<p class="postmetadata"><?php _e('Posted in', 'wp-wurfled'); print ' '; the_category(', '); ?> | <?php edit_post_link(__('Edit','wp-wurfled'), '', ' | '); ?>  <?php comments_popup_link(__('No Comments','wp-wurfled') . ' &#187;', __('1 Comment','wp-wurfled') . ' &#187;', __('% Comments','wp-wurfled') . ' &#187;'); ?></p>
			</div>
		<?php endwhile; ?>
		<div class="navigation">
			<?php next_posts_link('&laquo; ' . __('Previous Entries','wp-wurfled')) ?>&nbsp;<?php previous_posts_link(__('Next Entries','wp-wurfled') .' &raquo;') ?>
		</div>
	<?php else : ?>
		<h2 class="center"><?php _e('No posts found. Try a different search?','wp-wurfled'); ?></h2>
	<?php endif; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
