<?php

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.plugin.plugin' );
jimport ( 'joomla.error.log' );

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'xlink_articles_processor.php';

/**
 * This plugin fires when an article is saved. Provided "@Ecouter également " (NOTE THE @ !)
 * is found in the article intro text, the href links are parsed and each reference
 * article is edited in order to add a cross link to the current article.
 *
 * USAGE: when creating a new article, add a '@' in front of 'Ecouter également' and add
 * links to the articles you want to cross-link to the new article. Save. Then, remove
 * the '@' and add links for articles you don't want to cross-link to the new article.
 *
 * Before leaving the new article, be sure to remove the '@' in front of 'Ecouter également ' !
 *
 * @package		Joomla
 * @subpackage	Content
 * @since 		1.5
 */
class plgContentXLink_articles extends JPlugin {

	/**
	 * Constructor
	 *
	 * @param 	$subject
	 * @param	array $config
	 */
	function __construct(&$subject, $config = array()) {
		// call parent constructor
		parent::__construct($subject, $config);
	}
	
	/**
	 * After save content method
	 * Article is passed by reference, but after the save, so no changes will be saved.
	 * Method is called right after the content is saved.
	 *
	 *
	 * $param	$context 		The context of the content passed to the plugin.
	 * @param 	$article		A reference to the JTableContent object that was saved 
	 * 							which holds the article data.
	 * @param 	$isNew			A boolean which is set to true if the content was created.
	 * @return true
	 */
	public function onContentAfterSave($context, $article, $isNew) {
		// obtaining plugin parms

		$plugin = JPluginHelper::getPlugin ( 'content', 'xlink_articles' );
		$pluginParams = new JRegistry($plugin->params);

		$linkSectionStartString = trim($pluginParams->get('link_section_tag'));
		$isSpaceAddedlinkSectionStartString = $pluginParams->get('add_space_after_link_section_tag');
		$linkSeparator = $pluginParams->get('link_separator');
		$isSpaceAddedlinkSeparator = $pluginParams->get('add_space_after_link_separator');
		$linkAddDefPos = $pluginParams->get('link_add_default_position');

		// instanciating the xlink processor

		$xLinkProcessor = new XlinkArticlesProcessor($linkSectionStartString, $isSpaceAddedlinkSectionStartString, $linkSeparator, $isSpaceAddedlinkSeparator, $linkAddDefPos);

		$xLinkProcessor->processArticleXLinks($article);

		// displaying whaz was done to the user

		$userMessageArray = $xLinkProcessor->getUserMessageArray();
		$application = JFactory::getApplication();

		if (!empty($userMessageArray)) {
			foreach ($userMessageArray as $userMessage) {
				$application->enqueueMessage( JText::_( $userMessage ), 'message' );
			}
		}
			
		return true;
	}
}
