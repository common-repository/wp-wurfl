<?php get_header(); ?>
<div id="content">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
		<h2><?php the_title(); ?></h2>
			<div class="entrytext">
				<?php the_content('<p class="serif">' . __('Read the rest of this page','wp-wurfled') . ' &raquo;</p>'); ?>
				<?php wp_link_pages('<p><strong>' . __('Pages:','wp-wurfled') . '</strong>' , '</p>', __('number','wp-wurfled')); ?>
			</div>
		</div>
	  <?php endwhile; endif; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
