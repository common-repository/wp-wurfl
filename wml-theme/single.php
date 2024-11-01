<?php get_header(); ?>
<card title="<?php wp_title('&gt;'); ?>">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<p><b><a href="<?php echo get_permalink() ?>" title="<?php echo sprintf(__('Permanent Link to %s','wp-wurfled'),the_title('','',FALSE)); ?>"><?php the_title(); ?></a></b></p>
			<?php the_content();?>
	<?php /*comments_template();*/ ?> 
	<p>
		<?php previous_post_link('&lt; %link') ?>&nbsp;<?php next_post_link('%link &gt;') ?>
	</p>
	<?php endwhile; else: ?>
		<p><?php _e('Sorry, no posts matched your criteria.','wp-wurfled'); ?></p>
	<?php endif; ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
