<?php
/*
 * Created on 25.5.2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  class WmlParser{
  	var $xml_parser;
  	var $curr_event;
  	var $content;
  	var $singleElements = array('hr','br','img','access','meta','noop','postfield','setvar','input','timer');
  	var $passElements = array(
'wml', 
			'card', 'do', 'onevent', 'head', 'template', 'access', 'meta', 'go', 'prev',
			'refresh',
			'select',
			'optgroup',
			'option',
			'fieldset',
			'table',
			'tr',
			'td',
			'em',
			'strong',
			'b',
			'i',
			'u',
			'big',
			'small');
  	var $removeP=false;
  	var $attachBrAtPEnd=false;
  	function WmlParser(){
	$this->xml_parser = xml_parser_create();
	xml_parser_set_option($this->xml_parser, XML_OPTION_CASE_FOLDING, false);
	xml_set_element_handler($this->xml_parser, array($this,"startElement"), array($this,"endElement"));
	xml_set_character_data_handler($this->xml_parser, array($this,"characterData")); 
  	}
  	function startElement($parser, $name, $attr){
  		$name = strtolower($name);
		$isSingle = in_array($name,$this->singleElements);
		$allowed = NULL;
		$isPrint = $isSingle;
		if(''!=$this->curr_event){
			$this->content.=$this->getCharBuff();
		}
		if(!$isPrint){
			$isPrint = in_array($name,$this->passElements);
		}
  		switch($name){
  			case 'p':
  			$allowed = array('id','class');
  			$isPrint = !$this->removeP;
  			break;
  			case 'img':
  			$isPrint = true;
  			$allowed = array('id','class','alt','src','localsrc','vspace','hspace','align','height','width');
  			break;
  			case 'a':
  			$isPrint = true;
  			$allowed = array('id','class','title','href');
  			break;
  			default:
//  			$isPrint = true;
  			break;
  		}
  		if($isPrint){
  			$this->content .= '<'.$name;
  			foreach($attr as $k=>$v){
  				$add = $allowed==NULL;
  				if($allowed!=NULL){
  					$add = (in_array($k,$allowed));
  				}
  				if($add){
  					$this->content .= ' '.strtolower($k).'="'.$v.'"';
  				}else{
//  					echo 'removed '.$k.' with value '.$v."\r\n";	
  				}
  			}
  			$this->content .= (($isSingle)?' /':'').'>';
  		}
  	}
  	function endElement($parser, $name){
  		$isPrint = !in_array($name,$this->singleElements);
		if($isPrint){
			$isPrint = in_array($name,$this->passElements);
		}
		$this->content.=$this->getCharBuff();
  		switch($name){
  			case 'content'://root container
  			break;
  			case 'a':
  			$isPrint = true;
  			break;
  			case 'p':
  			$isPrint = !$this->removeP;
  			if($this->removeP && $this->attachBrAtPEnd){
  				$this->content.='<br />';
  			}
  			break;
  			case 'li':
	  			if(''!=$this->attachAfterLi){
	  				$this->content.=$this->attachAfterLi;
	  			}
	  			break;
  			}
		if($isPrint){
			$this->content.='</'.$name.'>';
  		}
  	}
  	function getCharBuff(){
  		$r =$this->curr_event;
  		$this->curr_event=''; 
  		return $r;
  	}
function characterData($parser, $data) {
	if (trim($data) != "" ) {
		$this->curr_event.=$data;
	}
}
  	function parse($content){
  		$content = '<content>'.$content.'</content>';
  		if (!xml_parse($this->xml_parser, $content, strlen($content))){
  			$err = sprintf("XML error: %s at line %d",
			    xml_error_string(xml_get_error_code($this->xml_parser)),
			    xml_get_current_line_number($this->xml_parser));
			    sql_log('wmlparser',$content,LOG_ERR);
			    sql_log('wmlparser',$err,LOG_ERR);
  			$this->content.=((!$this->removeP)?'<p>':'').$err.((!$this->removeP)?'</p>':'');
  		} 
  		return $this->content;
  	}
  }
?>
