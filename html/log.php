<div class="wrap">
<h2><?php _e('Wurfled Log','wp-wurfled'); ?></h2>
<p>
<form class="wurfled_submit_form" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="post">
<input type="hidden" name="action" value="wurfled_submit_log" />
<input type="submit" name="load" value="Load" />
</form>
</p>
<div id="wurfled_result">
</div>
</div>
