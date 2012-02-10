<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* @version    1.0
* @copyright  Copyright (C) 2012 Jason Lowry. All rights reserved.
* @license    GNU/GPL
* @author     Jason Lowry
*
*/

jimport('joomla.plugin.plugin');
JLoader::register('MenusHelper', JPATH_ADMINISTRATOR . '/components/com_menus/helpers/menus.php');

class plgSystemCanonical extends JPlugin
{
  /**
   * Object Constructor.
   *
   * @access  public
   * @param object  The object to observe -- event dispatcher.
   * @param object  The configuration object for the plugin.
   * @return  void
   * @since 1.0
   */
  function __construct(&$subject, $config) {
    parent::__construct($subject, $config);
  }

  function onAfterDispatch() {
  	$catids = $this->params->get('catids');
  	$domain = $this->params->get('domain');
  	$canonical_lang = $this->params->get('canonical_lang','en-US');
  	$app = &JFactory::getApplication();
    if (!$app->isSite()) return ;
    
    if (!$domain) {
  		$domain="http";
  		if (isset($_SERVER['HTTPS'])) $domain.="s";	 
  		$domain .= "://".$_SERVER['HTTP_HOST'];
  	}
  	
    
    $languages = JLanguageHelper::getLanguages();
  	foreach($languages as &$language) {
  		if ($language->lang_code == $canonical_lang) {
            $canonical_sef = $language->sef;
  		}
   	}
    
	$view = $app->input->get('view');
	$lang_code = JFactory::getLanguage()->getTag();
	
    switch ($view) {
	    case 'article':
	    	$catID = $app->input->get('catid');
			$article = $app->input->get('id');
			
			//sometimes articles don't have a category available via the $app
			if (!$catID) {
				$db =& JFactory::getDBO();
				$query = "SELECT catid FROM #__content WHERE id=".$article.";";
				$db->setQuery($query);
				$catID = $db->loadResult();
			}
			if (self::getGoAhead($catID, $catids, $lang_code,$canonical_lang)) {
				$canonical_url = self::getArticleURL($article,$catID,$app,$domain,$canonical_lang,$canonical_sef);
			} 
	        break;
	    case 'category':
	    	$catID = $app->input->get('id');
	    	if (self::getGoAhead($catID, $catids, $lang_code,$canonical_lang)) {
	    		$canonical_url = self::getCategoryURL($app,$domain,$canonical_lang,$canonical_sef);
	    	} 
	        break;
		}

	  	if(isset($canonical_url)) {
	  			if ($_SERVER['HTTP_HOST']="localhost") {
					error_log("\nCanonical URL: ".$canonical_url,3,'/var/tmp/errors.log');
	  			}
				$document=& JFactory::getDocument();
				$document->addHeadLink($canonical_url, 'canonical', 'rel');
		}
  	}
  
	function getArticleURL ($article,$catID,$app,$domain,$canonical_lang,$canonical_sef) {
		$itemid = self::getAssociations($app,$canonical_lang);
		if (isset($catID) && isset($itemid)) {
			$canonical_url = $domain.JRoute::_('index.php?view=article&Itemid='.$itemid.'&lang='.$canonical_sef.'&catid='.$catID.'&id='.$article);
			return $canonical_url;
		} else {
			$canonical_url = $domain.JRoute::_('index.php?lang='.$canonical_sef);
			return $canonical_url;
		}
		return false;
	}
  
	function getCategoryURL ($app,$domain,$canonical_lang,$canonical_sef) {
			$itemid = self::getAssociations($app,$canonical_lang);
			if(isset($itemid)) {
				$canonical_url = $domain.JRoute::_('index.php?lang='.$canonical_sef.'&Itemid='.$itemid);
			} else {
				$canonical_url = $domain.JRoute::_('index.php?lang='.$canonical_sef);
			}
			return $canonical_url;
	}
	
	function getAssociations ($app,$canonical_lang) {
		if ($app->get('menu_associations', 0)) {
			$menu = $app->getMenu();
			$active = $menu->getActive();
				if ($active) {
					$associations = MenusHelper::getAssociations($active->id);
					if ((count($associations)>0) && (array_key_exists($canonical_lang, $associations))) {
						$itemid = $associations[$canonical_lang];
						return $itemid;
					}
				}
		} 
	}
	
	function getGoAhead ($catID,$catids,$lang_code,$canonical_sef){
		if (in_array($catID, $catids) && $lang_code != $canonical_sef) {
			return true;
		} else {
			return false;
		}
	}

}
?>
