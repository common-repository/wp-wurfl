<?php
/*
Plugin Name: WP-Wurfled
Plugin URI: http://www.demoru.com/en/products/wp-wurfled/
Description: This plugin helps the users to view your blog in a mobile device using WML or XHTML-MP.
Author: Libor Sigmund
Version: 0.0.2
Author URI: http://liborsigmund.cz/

*/ 
define('WURFL_DIR', dirname(__FILE__).'/wurfl/');
//custom caching mechanism directory root
define('WURFL_WHOLEDEVICEDIR', WURFL_DIR.'/data/devices/');
define('WURFL_PATCH_FILE',WURFL_DIR.'data/web_browsers_patch.xml');
require_once(WURFL_DIR.'wurfl_config.php');
require_once(WURFL_CLASS_FILE);
require_once('wurfled-handsets.php');
require_once('wurfled-html.php');
require_once('wurfled-wml.php');
//wurfled_content_root
define("WURFL_AUTOLOAD", false);
define("WURFL_USE_MULTICACHE",true);
define("WURFL_USE_CACHE",true);
/**
 * 
 */
class WurfledPlugin {
	/**
	 * HTML view
	 */
	var $view;
	/**
	 * brand/device view
	 */
	var $handsetsView;
	/**
	 * list of errors generated during queries
	 */
	var $errors = array();
	function WurfledPlugin(){
		load_plugin_textdomain('wp-wurfled', '/wp-content/plugins/wp-wurfled/lang/');
		$this->view = new WurfledHtml();
		$this->handsetsView = new WurfledHandsets();
		if(function_exists('add_action')){
			if(!is_admin()){
				add_action('plugins_loaded',array(&$this->view,'detect_device'));
				add_filter('the_content',array(&$this->view,'the_content'));
			}else{
	    		add_action('admin_menu', array(&$this,'admin_menu'));
				add_action('admin_print_scripts', array(&$this,'ajax_scripts') );
				add_action('wp_ajax_wurfled_submit_form', array(&$this,'ajax_submit_form') );
			}
		}
		if(function_exists('add_filter')){
						if(!is_admin()){
			
			add_filter('theme_root',array(&$this->view,'theme_root'));
			add_filter('theme_root_uri',array(&$this->view,'get_theme_root_uri'));
			add_filter('stylesheet',array(&$this->view,'get_stylesheet'));
			add_filter('template',array(&$this->view,'get_template'));
			add_filter('option_html_type', array(&$this->view,'html_type'));
			add_filter('option_template',array(&$this->view,'get_template_directory'));
			add_filter('option_stylesheet',array(&$this->view,'get_template_directory'));
			
			add_filter('query_vars' ,array(&$this->handsetsView,'query_vars'));
			add_action('parse_query',array(&$this->handsetsView,'parse_query'));
			
						}else{
			add_filter('post_rewrite_rules',array(&$this,'rewrite_rules_array'));
						}
			//handsets shortcode brand/device
		}
		if(function_exists('add_shortcode') && !is_admin()){
			add_shortcode('wurfl', array($this->view, 'short_code'));
			add_shortcode('handsets',array($this->handsetsView, 'short_code'));
		}
	}
	/**
	 * 
	 * @return wurfl_class from session 
	 * */
static function CurrentBrowser(){
	return $_SESSION['WP-WURFLED-BROWSER'];
}
/**
 * 
 * @return variant false or array of capabilities 
 */
static function CurrentCapability($capability){
	$s = $_SESSION['WP-WURFLED-BROWSER'];
	if($s==NULL) return false;
	if($s->capabilities != NULL && !array_key_exists('object_download',$s->capabilities)){
		return false;
	}
	if(!is_array($s->capabilities['object_download']) || !array_key_exists($capability,$s->capabilities['object_download'])){
		return false;
	}
	return $s->capabilities['object_download'][$capability];
		
}
	/**
	 * adds the (post_name)/Brand/ and (post_name)/Brand/Device/ Rewrite Rule  
	 */
	function rewrite_rules_array($rules){
		global $wpdb,$wp_rewrite;
		if($this->view->currentPost!=''){
			$post = get_post($this->view->currentPost);
			$out = array();
			$newrules = array();
			if(ereg('[handsets]',$post->post_content)){
				$newrules = array(
					"(".$post->post_name.")/([^/]+)/?$"=> 'index.php?pagename=$matches[1]&wurfl_brand=$matches[2]',
					"(".$post->post_name.")/([^/]+)/([^/]+)/?$"=> 'index.php?pagename=$matches[1]&wurfl_brand=$matches[2]&wurfl_device=$matches[3]'
				 );
			}
			$rules = ($newrules + $rules);
		}
		return $rules;
	}
	
	/**
	 * jquery,jquery-form forms for admin purposes using admin interface
	 */	
	function ajax_scripts(){
		wp_print_scripts( array('jquery','jquery-form'));
		?>
<script type="text/javascript">
//<![CDATA[
// prepare the form when the DOM is ready 
jQuery(document).ready(function($){  
    var options = { 
        target:        '#wurfled_result', 
        beforeSubmit:  showRequest, 
        success:       showResponse, 
        url:       '<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php', 
        timeout:   600000 
    }; 
    $('form.wurfled_submit_form').ajaxForm(options); 
}); 
// pre-submit callback 
function showRequest(formData, jqForm, options) { 
	jQuery('#wurfled_result').html('querying');
    return true; 
} 
// post-submit callback 
function showResponse(responseText, statusText)  { 
    if(statusText!='success'){
    alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + 
        '\n\nThe output div should have already been updated with the responseText.');
        } 
} 
//]]>
</script>
<?php
	}
	
	/**
	 * 
	 */
	function ajax_submit_form(){
		echo '<div>';
		WurfledHtml::handsets_process_post();
		echo '</div>';
		die();
	}
	function admin_menu(){
    	if ( function_exists('add_options_page') ) {
    		add_options_page( 'WP-Wurfled', __('WP-Wurfled'), 8, 'wp-wurfled', array( &$this->view, 'html_handsets' ) );
    	}
	}
	function get_download_capability_keyed(){
		$vals = array();
		$en = array();
		if(!is_array( $_SESSION['WP-WURFLED-BROWSER']->capabilities['object_download'])){
			return $vals;
		}
		foreach($_SESSION['WP-WURFLED-BROWSER']->capabilities['object_download'] as $k=>$v){
				$pos = strpos($k,'_');
				if(!($pos === FALSE)){
					if($v){
						$vals[substr($k,0,$pos)][substr($k,$pos+1)] = $v;
					}
				}else{
					$en[$k] = $v;
				}
			}
		return $vals;
	}
	function get_download_capability($keys){
		$vals = array();
		if(!is_array($_SESSION['WP-WURFLED-BROWSER']->capabilities)  || !array_key_exists('object_download',$_SESSION['WP-WURFLED-BROWSER']->capabilities))
		return $vals;		
		$r = $_SESSION['WP-WURFLED-BROWSER']->capabilities['object_download'];
		if(is_array($r)){
			foreach($r as $k=>$v){
				$pos = strpos($k,$keys);
				if(!($pos === FALSE)){
					$vals[$k] = $v;
				}
			}
		}
		return $vals;
	}
	function wml_content($content,$settings=NULL){
		if(''==$content) return '';
		if($settings==NULL){
			$settings = array('removeP'=>false);
		}
		$p = new WmlParser();
		foreach($settings as $k=>$v){
			$p->{$k} = $v;	
		}
		return $p->parse($content);
//    $content = ereg_replace('<li[^>]*>','',$content);
//    $content = ereg_replace('</li>',', ',$content);
//    $content = ereg_replace('<ul[^>]*>','',$content);
//    $content = ereg_replace('</ul>','',$content);
//    return $content; 

  }
}
$wp_wurfled = new WurfledPlugin();

require_once('activate_wurfled.php');
if(function_exists('register_activation_hook')){
	register_activation_hook(__FILE__, 'wurfled_on_install');
//                        register_deactivation_hook(__FILE__, 'wurfled_on_uninstall');
}
        /**
         * unix sys utility
         */
function sys($command,$say=false)
{
    if($say) echo ($command);
    //global $db;
    $out="";
    $rows = array();
    //$out.="<cmd>".$db->fix_xml($command)."</cmd>\r\n";
    $descriptorspec = array(
        0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
    	1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
        2 => array("pipe", "w") // stderr is a file to write to
    );
    $pipes = NULL;
    $process = proc_open($command, $descriptorspec, $pipes);
    if (is_resource($process)) {
        $stdout=stream_get_contents($pipes[1]);
	$stderr=stream_get_contents($pipes[2]);
	$out=$stdout;
	if($stderr){
	    $out.=$stderr;
	}
	fclose($pipes[0]);
	fclose($pipes[1]);
	fclose($pipes[2]);
	$return_value = proc_close($process);
    }  
    return $out;
}
if(!class_exists('Timer')){
class Timer {
	var $start = 0;
	var $times = array();
	var $prev;
	function __construct(){
		$this->start = $this->now();
		$this->prev = $this->start;
	}
	function now(){
		list($usec, $sec) = explode(' ', microtime());
		return ((float)$usec + (float)$sec); 
	}
	function tick($name,$echo = true){
		$t = array('name'=>$name,'time'=>$this->now());
		$this->times[] = $t;
		if($echo) echo str_replace('&','&amp;','<p>'.$t['name'].': '.($t['time'] - $this->start).' s, step: '.($t['time'] - $this->prev))."</p>\r\n";
		$this->prev = $t['time'];
	}
	function dump(){
		$r = '<div>';
		$r.='Start: '.$this->start.', duration:'.($this->prev - $this->start)."\r\n";
		$prev = $this->start;
		foreach($this->times as $t){
			$r.=str_replace('&','&amp;','<p>'.$t['name'].': '.($t['time'] - $this->start).' s, step: '.($t['time'] - $prev))."\r\n";
			$prev = $t['time'];
		}
		return $r.'</div>';
	}
}
 }
 
function sql_log($function,$msg,$level= 3){
	global $wpdb;
	$wpdb->insert($wpdb->prefix.'log',array('date'=>date('Y-m-d H:i:s'),'thread'=>getmypid(),'msg'=>($msg),'level'=>$level));
} 
?>