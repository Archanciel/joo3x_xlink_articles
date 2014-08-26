<?php

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.plugin.plugin' );
jimport ( 'joomla.error.log' );

$processorFilePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'xlink_articles_processor.php';

if (file_exists($processorFilePath)) {
	// execution in context of PHPUnit tests execution
	require_once ($processorFilePath);
} else {
	// execution triggered by Joomla 1.5
	require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . 'plg_xlink_articles' . DIRECTORY_SEPARATOR . 'xlink_articles_processor.php');
}

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
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param object $params  The object that holds the plugin parameters
	 * @since 1.5
	 */
	function plgContentXLink_articles(&$subject, $params) {
		parent::__construct ( $subject, $params );
	}

	/**
	 * After save content method
	 * Article is passed by reference, but after the save, so no changes will be saved.
	 * Method is called right after the content is saved.
	 *
	 *
	 * @param 	$article		A JTableContent object
	 * @param 	$isNew			If the content is just about to be created
	 * @return	void
	 */
	public function onAfterContentSave($article, $isNew) {
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
