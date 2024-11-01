<?php
/**
 * installation function
 */
 function wurfled_on_install(){
 		global $wpdb;
  		require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); 
        $table_name = $wpdb->prefix . "brand";
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			$sql_createtable = file_get_contents(dirname(__FILE__).'/sql/0.0.1.sql') ;//$charset_collate;";
			$sql_createtable = str_replace('WP_PREFIX_',$wpdb->prefix,$sql_createtable);
        	dbDelta($sql_createtable);
        } else {                                                                                                                                             
			//TODO: update versions ?                                                                                     
        }
 	}
?>
