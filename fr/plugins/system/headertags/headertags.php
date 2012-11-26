<?php
/**
 * @version		$Id: headertags.php 12 2011-10-23 12:03:31Z confalte $
 * @package		Insert header tags in component titles
 * @copyright	(C) 2009 Conflate. All rights reserved.
 * @license		GNU/GPL 2.0
 * @author		Conflate <info@conflate.nl>
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');
jimport( 'joomla.version' );

class plgSystemHeaderTags extends JPlugin{

	function plgSystemHeaderTags( &$subject, $params ){
		parent::__construct( $subject, $params );
		$this->_plugin = JPluginHelper::getPlugin( 'system', 'headertags' );
		$this->_params = new JRegistry( $this->_plugin->params );
	}
	
	function onAfterDispatch(){
		$app =& JFactory::getApplication();
		if ( $app->isAdmin() ) {
			return;
		}
		
		if($this->_params->get('content_area') == 'component'){
			$document =& JFactory::getDocument();
			$buffer =& $document->getBuffer();
			
			if($document->getType() == 'html' && isset($buffer['component'][''])){
				$version = new JVersion();
				if($version->RELEASE == '3.0'){
					$component_output = $buffer['component'][''][''];
				}else{
					$component_output = $buffer['component'][''];
				}
				$tags = $this->_findHeaderTags($component_output);
				if($tags && is_array($tags)){
					$this->_replaceHeaderTags($tags, $component_output);
					$document->setBuffer($component_output, 'component', null);
				}
			}
		}
	}
	
	function onAfterRender(){
		$app =& JFactory::getApplication();
		if ( $app->isAdmin() ) {
			return;
		}
		
		if($this->_params->get('content_area') == 'page'){
			$body = JResponse::getBody();
			$tags = $this->_findHeaderTags($body);
			if($tags && is_array($tags)){
				$this->_replaceHeaderTags($tags, $body);
				JResponse::setBody($body);
			}
			
		}
		
	}
	
	private function _findHeaderTags($content){
		$matches = array();
		$regex = '/\<(h[1-7]{1})(.*?)\>(.*?)<\/h[1-7]{1}\>/is';
		preg_match_all( $regex, $content, $matches, PREG_SET_ORDER );
		if(count($matches)){
			$tags = array();
			for($i=1; $i<7;$i++){
				$ht = 'h'.$i;
				$tags[$ht]['count'] = 0;
				$tags[$ht]['mandatory'] = $this->_params->get($ht.'_mandatory');
				$tags[$ht]['mandatory_replace'] = $this->_params->get($ht.'_mandatory_replace');
				$tags[$ht]['limit'] = $this->_params->get($ht.'_limit');
				$tags[$ht]['limit_replace'] = $this->_params->get($ht.'_limit_replace');
				$tags[$ht]['matches'] = array();
			}
			foreach($matches as $match){
				$ht = $match[1];
				if(isset($tags[$ht])){
					$tags[$ht]['count']++;					
					$tags[$ht]['matches'][] = $match;
				}
			}
			return $tags;
		}
		return false;
	}
	
	private function _replaceHeaderTags(&$tags, &$content){
		foreach($tags as $ht => $tag){
			//check if the tag exists and is mandadory
			if($tag['count'] == 0 && $tag['mandatory'] == 1){
				//A mandatory tag, but doesn't occur. Find the first replacement tag en set it to the current tag
				$rht = $tag['mandatory_replace'];
				if($tags[$rht]['count'] > 0){
					$first_match = array_shift($tags[$rht]['matches']);
					
					//replace old match with the new heading tag only once!
					$str_new = '<' . $ht . $first_match[2] . '>' . trim($first_match[3]) . '</' . $ht . '>';
					$content = substr_replace($content, $str_new, strpos($content, $first_match[0]), strlen($first_match[0]));
					
					//update tags array
					$first_match[0] = $str_new;
					$tags[$ht]['matches'][] = $first_match;
					$tags[$ht]['count']++;
					$tags[$rht]['count']--;
					
				}
			}
			
			//check if the tag has a limit and if it exceeds that limit
			if($tag['limit'] != 'ul' && $tag['count'] > 0 && $tag['limit'] < $tag['count']){
				$over = $tag['count'] - $tag['limit'];
				//The tag has more then the allowed accurences
				$rht = $tag['limit_replace'];
				$exceeded_tags = array_splice($tags[$ht]['matches'], $tag['limit']);
				foreach($exceeded_tags as $match){
					
						//replace old match with the new heading tag only once!
						$str_new = '<' . $rht . $match[2] . '>' . trim($match[3]) . '</' . $rht . '>';
						$content = substr_replace($content, $str_new, strpos($content, $match[0]), strlen($match[0]));
					
						//update tags array
						$match[0] = $str_new;
						if(isset($tags[$rht])){
							$tags[$rht]['matches'] = array_merge(array($match), $tags[$rht]['matches']);
							$tags[$rht]['count']++;
						}
						$tags[$ht]['count']--;

				}
			}
		}
	}
}
