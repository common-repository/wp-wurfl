<input name="s" value="<?php echo wp_specialchars($s, 1); ?>" />
 <anchor>
        <go method="get" href="<?php bloginfo('home'); ?>">
          <postfield name="s" value="$(s)"/>
        </go>
        <?php _e('Search'); ?>
</anchor>
