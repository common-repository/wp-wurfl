<?php
//require_once('html/sendlink.php');
/*
 * Created on 25.5.2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class WurfledHtml {
 	var $device;
 	var $currentPost;
	var $wml;
	var $xhtml;
	var $html = true;
	var $contentType;
	var $errors = array();
	function setDevice($device){
		$this->device = $device;
	}
 	function setWml(){
		$this->wml=true;
		$this->xhtml=false;
		$this->html=false;
 	}
 	function setXhtml(){
		$this->wml=false;
		$this->xhtml=true;
		$this->html = false;
 	}
 	function setHtml(){
		$this->wml=false;
		$this->xhtml=false;
		$this->html =true;
 	}
 	function html_type($value){
 		if($this->contentType!=''){
 			return $this->contentType;
 		}
        	if($this->xhtml){
        		return 'application/vnd.wap.xhtml+xml';
        	}else if($this->wml){
        		return 'text/vnd.wap.wml';
        	} 
        	return 'text/html';
	}
	function detect_device($query){
		$browserAgent = $_SERVER['HTTP_USER_AGENT'];
		$isChanged = false;
		if(is_array($_SESSION) && array_key_exists('WP-WURFLED-UA',$_SESSION)){
			if($_SESSION['WP-WURFLED-UA'] != $browserAgent){
				$isChanged = true;
			}
		}else{
			$isChanged = true;
		}
		if($isChanged){
			$_SESSION['WP-WURFLED-UA'] = $browserAgent;
			$myDevice = new wurfl_class();
			$myDevice->GetDeviceCapabilitiesFromAgent( $browserAgent,true);
			$_SESSION['WP-WURFLED-BROWSER'] = $myDevice;
		}else{
			$myDevice = WurfledPlugin::CurrentBrowser();// $_SESSION['WP-WURFLED-BROWSER'];
		}
//		$myDevice->browser_is_wap=false;
		/*
application/x-wap-prov.browser-settings, 
application/x-nokia.settings, 
application/vnd.wap.wmlc, 
application/vnd.wap.wmlscriptc, 
text/x-vcard, text/x-vcalendar, 
application/vnd.wap.wtls-ca-certificate, 
application/vnd.wap.sic, 
application/x-wap-prov.browser-bookmarks, 
text/x-co-desc, 
image/gif, image/jpeg, image/jpg, image/bmp, image/png, image/vnd.wap.wbmp, image/vnd.nok-wallpaper, image/vnd-nok-camera-snap, image/vnd-nok-camera-snsp, 
application/vnd.wap.mms-message, 
text/vnd.sun.j2me.app-descriptor, 
application/vnd.nokia.ringing-tone, 
audio/midi, audio/mid, audio/x-midi, audio/x-mid, audio/sp-midi, 
application/java, application/java-archive, application/x-java-archive, 
image/vnd.nok-oplogo-color
		 * */
//		 wurfl_log('wurfled',print_r(split(',',$_SERVER['HTTP_ACCEPT']),true));
//			foreach(split(',',$_SERVER['HTTP_ACCEPT']) as $_a){
//				$_a = trim($_a);
//				switch($_a){
////					case 'application/xhtml+xml':
//					case 'application/vnd.wap.wml':
//					case 'application/vnd.wap.wmlc':
//					case 'text/vnd.wap.wml':
//					case 'application/vnd.wap.xhtml+xml':
//					$myDevice->browser_is_wap=true;
//					break;
//				}	
//			}
		$this->setDevice($myDevice->brand.'/'.$myDevice->model.'('.$myDevice->id.') - '.$browserAgent);
		if($myDevice->browser_is_wap){
			switch($myDevice->capabilities['markup']['preferred_markup']){
				case 'html_wi_w3_xhtmlbasic':
				case 'html_wi_oma_xhtmlmp_1_0':
				$this->contentType = $myDevice->capabilities['xhtml_ui']['xhtmlmp_preferred_mime_type'];
				$this->setXhtml();
				break;
				case 'wml_1_3':
				case 'wml_1_2':
				case 'wml_1_1':
				$this->setWml();
				break;
				default:
				$this->setHtml();
				//wurfl_log($func, $msg, $logtype=3)
				sql_log('wurfled',$this->device.':'.str_replace("\n",'',var_export($myDevice->capabilities,true)));
				break;
			}
		}else{
			$this->setHtml();
		}
		sql_log('wurfled',$this->device.', is_wireless_device:'.$myDevice->browser_is_wap.',preferred_markup:'.$myDevice->capabilities['markup']['preferred_markup'].', Content-Type: '.$this->html_type('').',Accept:'.str_replace("\n","",print_r($_SERVER['HTTP_ACCEPT'],true)));
	}	
	function theme_root($path){
		$theme_root = dirname(__FILE__);
		if($this->wml || $this->xhtml){
			return $theme_root;
		}else{
			return $path;
		}
	}
	
	function get_theme_root_uri($url){
		if($this->wml || $this->xhtml){
			return get_option('siteurl') . "/wp-content/plugins/wp-wurfled";
		}else{
			return $url;
		}
	}
	
	function get_stylesheet($stylesheet) {
		if($this->xhtml){
			return 'xhtmlmp-theme';
		}elseif($this->wml){
			return 'wml-theme';
		}else{
			return $stylesheet;
		}
	}
	
	function get_template($template) {
		if($this->xhtml){
			return 'xhtmlmp-theme';
		}elseif($this->wml){
			return 'wml-theme';
		}else{
			return $template;
		}
	}
	
	function get_template_directory($value){
		if($this->xhtml){
			return 'xhtmlmp-theme';
		}elseif($this->wml){
			return 'wml-theme';
		}else{
			return $value;
		}
	}
	
	
 	
function log_process_post(){
	if(array_key_exists('load',$_POST)){
		set_time_limit(600);
		echo '<p>';
		echo '<ul>';
		$timer = new Timer();
		// $l = '';
		//wurfl_log('aaa');
		//var_dump(WURFL_LOG_FILE);
		$fs = sys('tail -n 100 '.WURFL_LOG_FILE);
		$sp = split("\n",$fs);
		$timer->tick('file_loaded',false);
		$s = count($sp) - 20;
		if($s <0)$s=0;
		for($i=$s;$i<count($sp);$i++){
		echo '<li>'.$sp[$i].'</li>';
		}
		$timer->tick('log_printed',false);
		echo '</ul>';
		echo '</p>';
		echo '<p>';
		echo $timer->dump();
		echo '</p>';
	}
}
function handsets_process_post(){
	global $wpdb;
		if(array_key_exists('file_cache', $_POST)){
			require_once('wurfl/update_cache.php');
		}else if(array_key_exists('models', $_POST)){
			$timer = new Timer();
			WurfledHandsets::import_handsets($timer);
		}else if(array_key_exists('on_install', $_POST)){
			wurfled_on_install();
		}else if(array_key_exists('printdevice', $_POST)){
	echo '<pre>';
	$printDevice = WurfledHandsets::get_device_wurfl($_POST['wurfled_id']);
	echo '</pre>';
echo '<pre>';
echo str_replace('&','&amp;',print_r($printDevice,true));
echo '</pre>';
		}else if(array_key_exists('temp', $_POST)){
//	echo '<pre>';
	$timer = new Timer();
	WurfledHandsets::temp($timer);
//	echo str_replace('&','&amp;',print_r($printDevice->capabilities,true));
//	echo '</pre>';
			
		}else if(array_key_exists('printua', $_POST)){
	$printDevice = new wurfl_class();
			$printDevice->GetDeviceCapabilitiesFromAgent( $_POST['wurfled_ua'],false);
	echo '<pre>';
	echo str_replace('&','&amp;',print_r($printDevice->capabilities,true));
	echo '</pre>';
		}
}
/**
 * ajaxified processor for handsets options
 */
	function html_handsets(){
		global $wpdb;
		$printDevice = '';
		echo '<div id="wrap">';
		include_once('html/handsets.php');
		WurfledHtml::handsets_process_post();
		echo '</div>';
	}
 	
	function short_code($atts, $content = null){
		$myDevice = $_SESSION['WP-WURFLED-BROWSER'];
$ar = WurfledPlugin::get_download_capability_keyed();
$r .= '<p>'.$this->device.'</p>';
$r .= '<p><b>'.__('Download Capabilities','wp-wurfled').'</b></p>';
if(count($ar)==0){
$r .= '<p><b>'.__('Not Supported','wp-wurfled').'</b></p>';

}else{
	foreach($ar as $k=>$v){
	if(is_array($v)){
		$r .= '<p><b>'.$k.'</b></p>';	
		foreach($v as $kk=>$vv){
//			if($vv){
				$r .= '<p><b>'.$kk.':'.$vv.'</b></p>';
//			}
		}
	}else{
		$r .= '<p>'.$k.':'.$v.'</p>';
	}
	}
}
		return $r;
	}
	function the_content($content){
		if(!$this->wml)return $content;
		if($this->wml){
			global $wp_query;
			$s = array(
				'removeP'=>($wp_query->is_home),
				'attachBrAtPEnd'=>($wp_query->is_home),
				'attachAfterLi'=>'<br />');
			return WurfledPlugin::wml_content($content,$s);
		}
	}
 }
 
?>
