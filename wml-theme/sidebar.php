<?php
$defaults = array('format' => 'array');
$listTags = wp_tag_cloud($defaults);
?>
<?php if(!false/*is_single()*/) : ?>
		<?php if(!empty($listTags)) : ?>
			<p><?php _e('Tags:','wp-wurfled'); ?> </p>
			<?php foreach ($listTags as $tagLink): ?>
			<p><?php echo $tagLink ?></p>
			<?php endforeach; ?>
		<?php endif; ?>
		<p><?php _e('Pages:','wp-wurfled'); ?>
		<?php 
		$s = array(
				'removeP'=>true,
				'attachBrAtPEnd'=>false,
				'attachAfterLi'=>'<br />');
		echo WurfledPlugin::wml_content(wp_list_pages(array('title_li'=>'','echo'=>'0')),$s); ?>
		</p>
<?php endif; ?>
