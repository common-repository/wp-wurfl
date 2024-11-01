<?php get_header(); ?>
<card title="<?php wp_title('&gt;'); ?>">
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
				<p><b><a href="<?php the_permalink() ?>" title="<?php echo sprintf(__('Permanent Link to %s','wp-wurfled'),the_title('','',FALSE)); ?>"><?php the_title(); ?></a></b></p>
					<p><?php the_content(); ?></p>
		<?php endwhile; ?>
		<p>
			<?php next_posts_link('\'' . __('Previous Page','wp-wurfled')); ?>&#160;<?php previous_posts_link(__('Next Page','wp-wurfled') . '\'') ?>
		</p>
	<?php else : ?>
		<p><b><?php _e('Not Found','wp-wurfled'); ?></b></p>
		<p class="center"><?php _e("Sorry, but you are looking for something that isn't here.",'wp-wurfled'); ?></p>
		<?php include (TEMPLATEPATH . "/searchform.php"); ?>
	<?php endif; ?>
<?php get_sidebar();?>
<?php get_footer(); ?>
