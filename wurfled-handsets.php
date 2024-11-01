<?php
/*
 * Created on 25.5.2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class WurfledHandsets {
	var $brand='';
	var $device='';
	function short_code($atts, $content = null){
		global $wp_rewrite,$wp_query,$post;
		$h='';
		$pagestruct = $wp_rewrite->get_page_permastruct();
		$isperm = ( '' != $pagestruct && isset($post->post_status) && 'draft' != $post->post_status );
		$link = post_permalink();
		if($this->brand!=''){
			$brand = WurfledHandsets::get_brand_sanitized($this->brand);
			$h.='<p><a href="'.WurfledHandsets::get_permanent_link_brand_device($link,$isperm,$brand->sanitized).'">'.$brand->name.'</a></p>';
			if($this->device!=''){
//				$h.='<p><a href="'.WurfledHandsets::get_permanent_link_brand_device($link,$isperm,$brand->sanitized).'">'.$brand->name.'</a></p>';
				//
				$device= WurfledHandsets::get_device_id($this->device);
				if($device!=NULL){
					$r = $this->get_device_wurfl($device->wurfl_id);
					$h.='<p><b>'.$r['product_info']['model_name'].'</b></p>';
					$t = __('Display: width: %s px, height: %s px');
					$h.='<p>'.sprintf($t,$r['display']['resolution_width'],$r['display']['resolution_height']).'</p>';
				}else{
					$h.='<p><b>'.sprintf(__("Device %s not found"),$this->device).'</b></p>';	
				}
				
//				var_dump($device);
//var_dump(MULTICACHE_DIR);
			}else{
				if($brand!=NULL){
					$h.='<p>';
					$devices= WurfledHandsets::get_devices($brand->id);
					$dt = '';
					foreach($devices as $d){
						$nm = $d->name.(($d->extra!='')?(' '.$d->extra):'');
						$dt.=', <a href="'.WurfledHandsets::get_permanent_link_brand_device($link,$isperm,$brand->sanitized,$d->id).'">'.$nm.'</a>';
					}
					$h.= substr($dt,2);
					$h.='</p>';
				}
			}
		}else {
			$brands = WurfledHandsets::get_brands();
			foreach($brands as $b){
				$h.=', <a href="'.WurfledHandsets::get_permanent_link_brand_device($link,$isperm,$b->sanitized).'">'.$b->name.'</a>';
			}	
			$h = substr($h,2);
		}
		return $h;
	}
	/**
	 * adds the wurfl_brand and wurfl_device parameters to input filter
	 */
	function query_vars($vars){
		array_push($vars,'wurfl_brand','wurfl_device');
		return $vars;
	}
	/**
	 * retrieves the wurfl_brand and wurfl_device from 
	 */
	function parse_query($query){
			if(array_key_exists('wurfl_brand',$query->query_vars) && ''!=$query->query_vars['wurfl_brand'])
				$this->brand = $query->query_vars['wurfl_brand'];
			if(array_key_exists('wurfl_device',$query->query_vars) && ''!=$query->query_vars['wurfl_device'])
				$this->device = $query->query_vars['wurfl_device'];
		return $this->device != '' || $this->brand != '';  
	}
	function cache_set_device($wurfl_id,$device){
		$fname = WURFL_WHOLEDEVICEDIR.$wurfl_id.'.bin';
		$dirname = dirname($fname);
  		if(!file_exists($dirname)){
  			mkdir($dirname,0777,true);
  		}
		(file_put_contents($fname,serialize($device)));
	}
	function cache_get_device($wurfl_id){
		$fname = WURFL_WHOLEDEVICEDIR.$wurfl_id.'.bin';
		if(!file_exists($fname)){
			return NULL;
		}
		return unserialize(file_get_contents($fname));
	}
	function get_device_wurfl($wurfl_id){
		$r = false;
		$r = WurfledHandsets::cache_get_device($wurfl_id);
		if($r!=NULL){
			return $r;
		}else{
			$r = false;
		}
		$ch =(MULTICACHE_DIR.$wurfl_id.'.php');
					if(file_exists($ch)){
						$r = file_get_contents($ch);
						$ex= '=array \((.*)\);[^\)]+';
						if(ereg($ex,$r,$capt)){
							$ar = '$r = array('.$capt[1].');';
							eval($ar);
						}
					}
					
			if($r['fall_back']!=''){
				$r1 = WurfledHandsets::get_device_wurfl($r['fall_back']);
				if($r1){
					if(is_array($r1)){
						$ks = array_keys($r1);
					foreach($r as $k=>$v){
						if(array_key_exists($k,$r1)){
							if(is_array($r1[$k])){
								$r[$k] = array_merge($r1[$k],$v);
							}else{
								//echo '<p>'.$k.'='.$r1[$k].'</p>';
							}
							unset($ks[array_search($k,$ks)]);
//						}else{
//							echo '<p>'.$k.'='.$r1[$k].'</p>';
						}
					}
					foreach($ks as $k){
						$r[$k] = $r1[$k];
					}
					}
				}
			}
			WurfledHandsets::cache_set_device($wurfl_id,$r);
		return $r;
	}
	static $cache_brand = array();
	function get_device_id($id){
		global $wpdb;
		return $wpdb->get_row('SELECT * from '.$wpdb->prefix.'device WHERE id="'.$id.'"');
	}
	function get_device_sanitized($name){
		global $wpdb;
		return $wpdb->get_row('SELECT * from '.$wpdb->prefix.'device WHERE sanitized="'.$name.'"');
	}
	function get_devices_wurfl_id(){
		global $wpdb;
		$current_devices = array();
		$ar = $wpdb->get_results('SELECT wurfl_id from '.$wpdb->prefix.'device',ARRAY_A);
		if(is_array($ar)){
			foreach($ar as $c){
				$current_devices[] = $c['wurfl_id']; 
			}
		}
			return $current_devices; 
	}
	function get_devices($brand_id=-1){
		global $wpdb;
		$brand='';
		if($brand_id!=-1){
			$brand = ' WHERE brand_id="'.$brand_id.'"';
		}
		return $wpdb->get_results('SELECT * from '.$wpdb->prefix.'device '.$brand);
	}
	function get_brand_sanitized($name){
		global $wpdb;
		return $wpdb->get_row('SELECT * from '.$wpdb->prefix.'brand WHERE sanitized="'.$name.'"');
	}
	function get_brand($name){
		global $wpdb;
		return $wpdb->get_row('SELECT * from '.$wpdb->prefix.'brand WHERE name="'.$name.'"');
	}
	function get_brand_id($name){
		global $wpdb;
		if(array_key_exists($name,WurfledHandsets::$cache_brand)){
			return WurfledHandsets::$cache_brand[$name];
		}
		$rid = WurfledHandsets::get_brand($name);
		if($rid != NULL){
			$id = $rid->id;
		}else{
			if($wpdb->insert(''.$wpdb->prefix.'brand',array('name'=>$name,'sanitized'=>sanitize_title($name)))){
				$id = $wpdb->get_var('SELECT LAST_INSERT_ID();');	
			}
			else{
				$id = -1;
			}
		}
		WurfledHandsets::$cache_brand[$name] = $id;
		return $id;
	}
	function get_brands(){
		global $wpdb;
		return $wpdb->get_results('SELECT b.*,count(d.id) as devices FROM '.$wpdb->prefix.'brand b JOIN '.$wpdb->prefix.'device d ON (d.brand_id = b.id) GROUP BY b.id');
	}
	function temp($timer){
		global $wpdb;
		set_time_limit(600);
		$current_devices = WurfledHandsets::get_devices_wurfl_id();
		$s= $_POST['wurfled_page'] *$_POST['wurfled_perpage'];
		/*
SELECT m.t90_id, m.vendor,m.model,m.identified_as, m.saphira_id,m.wurfl_id,d.user_agent  FROM mms_temp m LEFT JOIN mms_device d ON (d.wurfl_id = m.wurfl_id);//
		 * */
		$devs = $wpdb->get_results('SELECT * FROM handsets m where SaphiraImportName IS NULL  and WurflId IS NULL;');
		$timer->tick(sprintf(__('devices loaded: %s'),count($devs)));
		$cnt = count($devs); 
		$e = $s+$_POST['wurfled_perpage'];
		if($e> $cnt) $e = $cnt;
		$unknown = array();
		var_dump($cnt);
    	for($i=$s;$i<$e;$i++){
    		$d = $devs[$i];
    		$kw = ''.$d->Vendor;
    		$chunks = array();
    		$ws = 0;
    		$wsmodel = $d->Model;
    		for($ci=0;$ci<strlen($wsmodel);$ci++){
    			eregi('([a-z]*)',$wsmodel[$ci],$cchunks);//."\r\n";
    			eregi('([0-9]*)',$wsmodel[$ci],$nchunks);//."\r\n";
					if(!($cchunks[0]===FALSE)){
						if($ws==0 || $ws == 2){
							$kw.='%'.$wsmodel[$ci];
						}else{
							$kw.=$wsmodel[$ci];
						}
						$ws = 1;
					}else if(!($nchunks[0]===FALSE)){
						if($ws==0 || $ws == 1){
							$kw.='%'.$wsmodel[$ci];
						}else{
							$kw.=$wsmodel[$ci];
						}
						$ws = 2;
					}else{
						$ws = 0;
					}
			}
//    		if(eregi('([0-9]*)|([a-z]*)',$d->Model,$chunks)){
//    			
//    			for($z=1;$z<count($chunks);$z++){
//    				if($chunks[$z]){
//    					$kw.='%'.$chunks[$z];
//    				}
//    			}
//    		}
    		$kw.='%';
    		$q = 'SELECT * FROM '.$wpdb->prefix.'device m where wurfl_id LIKE "'.$kw.'";';
    		$match = $wpdb->get_results($q);
    		$c =count($match);
    		if($c>0){
				$w = array('T90Name'=>$d->T90Name);
				$_m = $match[0];
				$da = array('WurflId'=>$_m->wurfl_id,'WurflName'=>$_m->name,'UA'=>$_m->user_agent);
//				var_dump($kw);
//				var_dump($da);
//				die();
    			if($c == 1){
    				echo '<p>unique match:'.$kw.','.str_replace('\n','',print_r($match[0],true))."</p>\r\n";
/*
h.`T90Name`, h.`Vendor`, h.`Model`, h.`SaphiraImportName`, h.`WurflId`, h.`WurflName`, h.`UA`, h.`Status`
		 * */
		 			$da['Status'] = 'IdentifiedByWurflLS';
    				$wpdb->update('handsets',$da,$w);
    			}else{
    				$da['Status'] = 'IdentifiedByWurflMulti';
    				echo '<p>multiple match:'.$kw.','.str_replace('\n','',print_r($da,true))."</p>\r\n";
    				$wpdb->update('handsets',$da,$w);
    			}
    		}else{
    			$unknown[] = $d;
    		}
    		$timer->tick('#'.$i.'/'.$e.':'.$d->t90_id);
//    		var_dump($)
    	}
	}
	function import_handsets($timer){
		global $wpdb;
			set_time_limit(600);
			$current_devices = WurfledHandsets::get_devices_wurfl_id();
			$s= $_POST['wurfled_page'] *$_POST['wurfled_perpage'];
			$timer->tick(__('devices loaded'));
			$files = array();
			if ($handle = opendir(MULTICACHE_DIR)) {
		    	while (false !== ($file = readdir($handle))) {
		        if ($file != "." && $file != "..") {
		        	$files[].=$file;
		        }
    		}
			closedir($handle);
			$cnt = count($files); 
			$e = $s+$_POST['wurfled_perpage'];
			if($e> $cnt) $e = $cnt;
    	for($i=$s;$i<$e;$i++){
        	$f = pathinfo($files[$i]);
        	$d = WurfledHandsets::get_device_wurfl($f['filename']);
        	
        	if($d['actual_device_root']){
        		$p = $d['product_info'];
				$bid = WurfledHandsets :: get_brand_id($p['brand_name']);
				$extra = $p['marketing_name'] . $p['model_extra_info'];
				$name = $p['model_name'].(($extra!='')?(' '.$extra):'');
				$ins = array (
					'brand_id' => $bid,
					'name' => $p['model_name'],
					'extra' => $p['marketing_name'] . $p['model_extra_info'],
					'wurfl_id' => $d['id'],
					'sanitized'=>sanitize_title($name),
					'user_agent'=>$d['user_agent']
				);
				
				if(!(in_array($d['id'],$current_devices))){
					$wpdb->insert(''.$wpdb->prefix.'device', $ins);
				}else{
					$wpdb->update(''.$wpdb->prefix.'device', $ins, array('wurfl_id'=>$d['id']));
				}
				$timer->tick('#'.$i.'/'.$e.':'.$d['user_agent']);
        	}
        }
    }
//    echo '</ul>';
	}
	function get_permanent_link_brand_device($link,$isperm,$brand,$device=''){
	global $post, $wp_rewrite;
	$link = substr($link,strlen(get_option('home')));
	$brand = sanitize_title($brand);
	$device = sanitize_title($device);
//	$brand = str_replace('/','-',$brand);
//	$device = str_replace('/','-',$device);
	if ($isperm) {
		$link = get_option('home').$link.$brand.'/'.(($device!='')?($device.'/'):'');
	}else{
		$link = get_option('home').$link.'&amp;wurfl_brand='.$brand.(($device!='')?('&amp;wurfl_device='.$device):'');
	}
	return $link;
}
	
	
}
?>
