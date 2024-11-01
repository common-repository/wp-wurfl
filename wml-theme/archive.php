<?php get_header(); ?>
	<?php if (have_posts()) : ?>
	<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
	<?php /* If this is a category archive */ if (is_category()) { ?>
						
		<card title="<?php echo sprintf(__("Archive for the '%s' Category",'wp-wurfled'),single_cat_title('', FALSE));?>">
		<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<card title="<?php echo sprintf(__('Archive for %s', 'wp-wurfled'), get_the_time(__('F jS, Y','wp-wurfled'))); ?>">
		<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<card title="<?php echo sprintf(__('Archive for %s', 'wp-wurfled'), get_the_time(__('F, Y','wp-wurfled'))); ?>">
		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<card title="<?php echo sprintf(__('Archive for %s', 'wp-wurfled'), get_the_time('Y')); ?>">
		<?php /* If this is a search */ } elseif (is_search()) { ?>
		<card title="<?php _e('Search Results','wp-wurfled');?>">
		<?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<card title="<?php _e('Author Archive','wp-wurfled');?>">
		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<card title="<?php _e('Blog Archives','wp-wurfled');?>">
		<?php } ?>
		<?php while (have_posts()) : the_post(); ?>
						<p class="entry">
				<b><a href="<?php the_permalink() ?>" title="<?php echo sprintf(__('Permanent Link to %s','wp-wurfled'),the_title('','',FALSE)); ?>"><?php the_title(); ?></a></b><br />
					<?php the_excerpt(); ?>
				</p>
			<p>
				<?php _e('Posted in','wp-wurfled'); ?> <?php the_category(', ') ?> | <?php edit_post_link(__('Edit','wp-wurfled'), '', ' | '); ?>  <?php comments_popup_link(__('No Comments','wp-wurfled') . ' &#187;', __('1 Comment','wp-wurfled') . ' &#187;', __('% Comments','wp-wurfled') . ' &#187;'); ?>
			</p>
		<?php endwhile; ?>
		<p>
			<?php next_posts_link('&lt; ' . __('Previous Entries','wp-wurfled')) ?>&nbsp;<?php previous_posts_link(__('Next Entries','wp-wurfled') . ' &gt;') ?>
		</p>
	<?php else : ?>
		<p><?php _e('Not Found','wp-wurfled');?></p>
	<?php endif; ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
