<h2><?php _e('Wurfl database','wp-wurfled'); ?></h2>
<p>
<form class="wurfled_submit_form" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="post">
<input type="hidden" name="action" value="wurfled_submit_form" />
<input type="submit" name="download_xml" value="<?php _e('Download WURFL XML','wp-wurfled');?>" />
<input type="submit" name="file_cache" value="<?php _e('Regenerate cache','wp-wurfled');?>" />
<input type="submit" name="on_install" value="<?php _e('Install SQL','wp-wurfled');?>" />
<br />
<input type="text" name="wurfled_page" value="0" size="4"/>
<input type="text" name="wurfled_perpage" value="200" size="6"/>
<input type="submit" name="models" value="<?php _e('Save models to database','wp-wurfled');?>" />
<input type="submit" name="temp" value="<?php _e('Temp','wp-wurfled');?>" />
<br />
<?php _e('Wurfl ID: ','wp-wurfled');?>
<input type="text" name="wurfled_id" value="<?php echo $_POST['wurfled_id'];?>" /><input type="submit" name="printdevice" value="<?php _e('Get Device','wp-wurfled');?>" />
<br />
<?php _e('User-Agent: ');?><input type="text" name="wurfled_ua" value="<?php echo $_POST['wurfled_ua'];?>" />
<input type="submit" name="printua" value="<?php _e('Get Device','wp-wurfled');?>" />
</form>
</p>
<div id="wurfled_result">
</div>
