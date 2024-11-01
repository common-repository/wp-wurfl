<?php get_header(); ?>
<card title="<?php wp_title('&gt;'); ?>">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<p><b><?php the_title(); ?></b></p>
		<?php the_content('<p>' . __('Read the rest of this page','wp-wurfled') . ' \'</p>'); ?>
		<?php wp_link_pages('<p><strong>' . __('Pages:','wp-wurfled') . '</strong>' , '</p>', __('number','wp-wurfled')); ?>
	  <?php endwhile; endif; ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
