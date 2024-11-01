<?php get_header(); ?>
<div id="content">
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<div class="post">
				<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo sprintf(__('Permanent Link to %s','wp-wurfled'),the_title('','',FALSE)); ?>"><?php the_title(); ?></a></h2>
				<small><?php the_time(__('F jS, Y','wp-wurfled')) ?> <!-- <?php _e('by','wp-wurfled'); ?> <?php the_author() ?> --></small>
				<div class="entry">
					<?php the_excerpt(); ?>
				</div>
			</div>
			<p class="postmetadata">
				<?php _e('Posted in','wp-wurfled'); ?> <?php the_category(', ') ?> | <?php edit_post_link(__('Edit','wp-wurfled'), '', ' | '); ?>  <?php comments_popup_link(__('No Comments','wp-wurfled') . ' &#187;', __('1 Comment','wp-wurfled') . ' &#187;', __('% Comments','wp-wurfled') . ' &#187;'); ?>
			</p>
		<?php endwhile; ?>
		<div class="navigation">
			<?php next_posts_link('&laquo; ' . __('Previous Page','wp-wurfled')); ?>&nbsp;<?php previous_posts_link(__('Next Page','wp-wurfled') . ' &raquo;') ?>
		</div>
	<?php else : ?>
		<h2 class="center"><?php _e('Not Found','wp-wurfled'); ?></h2>
		<p class="center"><?php _e("Sorry, but you are looking for something that isn't here.",'wp-wurfled'); ?></p>
		<?php include (TEMPLATEPATH . "/searchform.php"); ?>
	<?php endif; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
