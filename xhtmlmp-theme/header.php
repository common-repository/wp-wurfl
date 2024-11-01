<?php load_theme_textdomain('pda-theme');
echo '<'.'?xml version="1.0"?'.'>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta name="HandheldFriendly" content="true" />
<link rel="alternate" media="handheld" href="" />
<style type="text/css">
<? include_once('style.min.css');?>
</style>
<!--
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>?nocache=<?php echo time() ?>" type="text/css" media="screen" />
-->
<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; <?php _e('Blog Archive','wp-wurfled'); ?> <?php } ?> <?php wp_title(); ?></title>
</head>
<body>
<div id="header">
	<h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
	<div class="description"><?php bloginfo('description'); ?></div>
</div>
<!-- 
<div id="searchform"><?php include (TEMPLATEPATH . '/searchform.php'); ?></div>
-->
