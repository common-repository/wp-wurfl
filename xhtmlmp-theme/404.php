<?php get_header(); ?>
<div id="content">
	<h2 class="center"><?php _e('Error 404 - Not Found','wp-wurfled'); ?></h2>
	<p class="center"><?php _e("Sorry, but you are looking for something that isn't here.",'wp-wurfled'); ?></p>
	<?php include (TEMPLATEPATH . "/searchform.php"); ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
