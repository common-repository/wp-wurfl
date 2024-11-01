<form method="get" action="<?php bloginfo('home'); ?>/">
	<input type="text" value="<?php echo wp_specialchars($s, 1); ?>" name="s" />&nbsp;<input type="submit" value="<?php _e('Search','wp-wurfled'); ?>" />
</form>
