<?php get_header(); ?>
<card title="<?php wp_title('&gt;'); ?>">
	<p><?php _e('Error 404 - Not Found','wp-wurfled'); ?></p>
	<p class="center"><?php _e("Sorry, but you are looking for something that isn't here.",'wp-wurfled'); ?></p>
	<p><?php include (TEMPLATEPATH . "/searchform.php"); ?></p>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
