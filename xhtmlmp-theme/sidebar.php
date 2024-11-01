<?php
$defaults = array('format' => 'array');
$listTags = wp_tag_cloud($defaults);
?>
<?php if(!is_single()) : ?>
	<div id="sidebar">
		<?php if(!empty($listTags)) : ?>
		<ul class="list line">
			<li class="head"><?php _e('Tags:','wp-wurfled'); ?> </li>
			<?php foreach ($listTags as $tagLink): ?>
			<li><?php echo $tagLink ?></li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>
		<ul class="list line"><li class="head"><?php _e('Pages:','wp-wurfled'); ?> </li><?php wp_list_pages('title_li='); ?></ul>
		<ul class="list line"><li class="head"><?php _e('Categories:','wp-wurfled'); ?> </li><?php wp_list_categories('sort_column=name&optioncount=1&hierarchical=0'); ?></ul>
		<ul class="list"><li class="head"><?php _e('Archives:','wp-wurfled'); ?> </li><?php wp_get_archives('type=monthly'); ?></ul>
	</div>
<?php endif; ?>