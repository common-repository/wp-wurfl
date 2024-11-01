<?php get_header(); ?>
<div id="content">
	<?php if (have_posts()) : ?>
	<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
	<?php /* If this is a category archive */ if (is_category()) { ?>				
		<div class="pagetitle"><?php echo sprintf(__("Archive for the '%s' Category",'wp-wurfled'),single_cat_title('', FALSE));?></div>
		<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<div class="pagetitle"><?php echo sprintf(__('Archive for %s', 'wp-wurfled'), get_the_time(__('F jS, Y','wp-wurfled'))); ?></div>
		<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<div class="pagetitle"><?php echo sprintf(__('Archive for %s', 'wp-wurfled'), get_the_time(__('F, Y','wp-wurfled'))); ?></div>
		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<div class="pagetitle"><?php echo sprintf(__('Archive for %s', 'wp-wurfled'), get_the_time('Y')); ?></div>
		<?php /* If this is a search */ } elseif (is_search()) { ?>
		<div class="pagetitle"><?php _e('Search Results','wp-wurfled');?></div>
		<?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<div class="pagetitle"><?php _e('Author Archive','wp-wurfled');?></div>
		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<div class="pagetitle"><?php _e('Blog Archives','wp-wurfled');?></div>
		<?php } ?>
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
			<?php next_posts_link('\'' . __('Previous Entries','wp-wurfled')) ?>&nbsp;<?php previous_posts_link(__('Next Entries','wp-wurfled') . ' \'') ?>
		</div>
	<?php else : ?>
		<h2 class="center"><?php _e('Not Found','wp-wurfled');?></h2>
	<?php endif; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
