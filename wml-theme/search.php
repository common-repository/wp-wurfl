<?php get_header(); ?>
<card title="<?php wp_title('&gt;'); ?>">
	<?php if (have_posts()) : ?>
		<p><b><?php _e('Search Results','wp-wurfled'); ?></b></p>
		<?php while (have_posts()) : the_post(); ?>
			<p>
				<b><a href="<?php the_permalink() ?>" title="<?php echo sprintf(__('Permanent Link to %s','wp-wurfled'),the_title('','',FALSE)); ?>"><?php the_title(); ?></a></b><br />
				<small><?php the_time(__('l, F jS, Y','wp-wurfled')) ?></small>
				<br />
				<?php the_excerpt(); ?>
				<br />
				<?php _e('Posted in', 'wp-wurfled'); print ' '; the_category(', '); ?> | <?php edit_post_link(__('Edit','wp-wurfled'), '', ' | '); ?>  <?php comments_popup_link(__('No Comments','wp-wurfled') . ' &#187;', __('1 Comment','wp-wurfled') . ' &#187;', __('% Comments','wp-wurfled') . ' &#187;'); ?>
			</p>
		<?php endwhile; ?>
		<p>
			<?php next_posts_link('\' ' . __('Previous Entries','wp-wurfled')) ?>&#160;<?php previous_posts_link(__('Next Entries','wp-wurfled') .' \'') ?>
		</p>
	<?php else : ?>
		<p><b><?php _e('No posts found. Try a different search?','wp-wurfled'); ?></b></p>
	<?php endif; ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
