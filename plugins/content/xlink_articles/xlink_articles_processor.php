<?php

require_once (dirname(__FILE__) . DIRECTORY_SEPARATOR . 'helper.php');

/**
 * This class performs the addition of the cross links pointing to the source
 * article into the target articles linked to in the source article.
 *
 * @author Jean-Pierre Schnyder
 *
 */
class XlinkArticlesProcessor {

	private $linkSectionStartString;
	private $isSpaceAddedlinkSectionStartString;
	private $linkSeparator;
	private $isSpaceAddedlinkSeparator;
	private $linkAddDefPos;

	/**
	 * Array receiving the msg to be displayed to the user after the plugin has finished
	 * processing.
	 */
	private $userMessageArray;

	/**
	 * Ctor.
	 *
	 * @param string $linkSectionStartString
	 * @param string $isSpaceAddedlinkSectionStartString
	 * @param string $linkSeparator
	 * @param string $isSpaceAddedlinkSeparator
	 * @param string $linkAddDefPos
	 */
	function __construct($linkSectionStartString, $isSpaceAddedlinkSectionStartString, $linkSeparator, $isSpaceAddedlinkSeparator, $linkAddDefPos ) {
		$this->linkSectionStartString = $linkSectionStartString;
		$this->isSpaceAddedlinkSectionStartString = $isSpaceAddedlinkSectionStartString;
		$this->linkSeparator = $linkSeparator;
		$this->isSpaceAddedlinkSeparator = $isSpaceAddedlinkSeparator;
		$this->linkAddDefPos = $linkAddDefPos;

		JPlugin::loadLanguage( 'plg_content_xlink_articles', JPATH_ADMINISTRATOR);
	}

	/**
	 * Adds a x-link on the passed article to the articles linked into the passed article
	 * which are marked for cross-linking.
	 */
	public function processArticleXLinks($article) {
		$linksArray = XLinkArticlesHelper::getLinksArray($article, $this->linkSectionStartString, $this->isSpaceAddedlinkSectionStartString, $this->linkSeparator, $this->isSpaceAddedlinkSeparator, $this->userMessageArray);

		if (empty ( $linksArray )) {
			// link section not found or contains no links
			return true;
		}

		$arrOfIdsArr = XLinkArticlesHelper::getLinkedArticlesIds ( $linksArray );

		$linkIds = $arrOfIdsArr[0];
		$unlinkIds = $arrOfIdsArr[1];
		$skippedLinkIds = $arrOfIdsArr[2];

		$linkToSourceArticle = $this->buildLinkToArticle ( $article );
		$sourceArticleIdAlias = $this->buildArticleIdAlias ($article);

		foreach ( $linkIds as $id ) {
			$this->addArticleLinkToArticleForId ($sourceArticleIdAlias, $linkToSourceArticle, $id, $this->linkAddDefPos );
		}

		foreach ( $unlinkIds as $id ) {
			$this->removeArticleLinkToArticleForId ($sourceArticleIdAlias, $id );
		}

		foreach ( $skippedLinkIds as $id ) {
			$this->reportSkippedLinkToArticleForIds ( $id );
		}

		return  true;
	}

	/**
	 * @return the $userMessageArray.
	 */
	public function getUserMessageArray() {
		return $this->userMessageArray;
	}

	/**
	 * Builds the link which is to be inserted into the target articles.
	 */
	private function buildLinkToArticle($article) {
		$articleTitle = $article->title;

		$sourceArticleCatIdAlias = $this->buildArticleCatIdAlias ($article);
		$sourceArticleIdAlias = $this->buildArticleIdAlias ($article);

		return "<a href=\"index.php?option=com_content&amp;view=article&amp;$sourceArticleCatIdAlias&amp;$sourceArticleIdAlias\">$articleTitle</a>";
	}

	/**
	 * Builds the category id / category alias part of the link to the article.
	 */
	private function buildArticleCatIdAlias($article) {
		$catId = $article->catid;

		/* @var $category JTable */
		$category = JTable::getInstance ( 'category' );
		$category->load ( $catId );

		return "catid=$catId:$category->alias";
	}

	/**
	 * Builds the article id / article alias part of the link to the article.
	 */
	private function buildArticleIdAlias($article) {
		return "id=$article->id:$article->alias";
	}

	/**
	 * Inserts a link to the source article into the target article whose id is passed.
	 *
	 * @param string $sourceArticleIdAlias
	 * @param string $sourceArticleLink
	 * @param string $targetArticleId
	 * @param string $linkAddDefaultPosition
	 */
	private function addArticleLinkToArticleForId($sourceArticleIdAlias, $sourceArticleLink, $targetArticleId, $linkAddDefaultPosition) {
		$targetArticle = JTable::getInstance ( 'content' );
		$targetArticle->load ( $targetArticleId );

		if (empty($targetArticle->alias)) {
			$this->userMessageArray[] = '!!! ' . sprintf(JText::_('LINK_INVALID_IN_ARTICLE'), $targetArticleId, $targetArticleId);
			return;
		}

		$linkSectionComponents = XLinkArticlesHelper::getLinkSectionComponents ( $targetArticle, $this->linkSectionStartString, $this->isSpaceAddedlinkSectionStartString, "No cross-link inserted in this article", $this->userMessageArray );

		if (count ( $linkSectionComponents ) == 0) {
			// link section not present in the intro text of the target article !
			return;
		}

		$linksInLinkSection = trim($linkSectionComponents [2],"\xa0");

		$targetArticleIntroText = html_entity_decode($targetArticle->introtext);
		$found = strpos ( $targetArticleIntroText, $sourceArticleIdAlias );

		if ($found === false) {
			// $articleLink not already exist in link section of target article ==> must be inserted
			$oldLinkSection = $linkSectionComponents [0];
			$linkSeparatorWithPossibleSpace = ($this->isSpaceAddedlinkSeparator) ? $this->linkSeparator . " " : $this->linkSeparator;
			$linkSeparator = (strlen ( $linksInLinkSection ) > 0 ? $linkSeparatorWithPossibleSpace : "");
			$newLinksInLinkSection = null;
				
			if ($linkAddDefaultPosition == 0) {
				$newLinksInLinkSection = $sourceArticleLink . $linkSeparator . $linksInLinkSection;
			} else {
				$newLinksInLinkSection = $linksInLinkSection . $linkSeparator . $sourceArticleLink;
			}

			$articleLinkSectionStartStringWithSep = ($this->isSpaceAddedlinkSeparator) ? $this->linkSectionStartString . ' ' : $this->linkSectionStartString;
			$newLinkSection = $articleLinkSectionStartStringWithSep . $newLinksInLinkSection . $linkSectionComponents [3];
			$newIntroText = str_replace ( $oldLinkSection, $newLinkSection, $targetArticleIntroText );
			$targetArticle->introtext = $newIntroText;
				
			if (! $targetArticle->store ()) {
				JFactory::getApplication()->enqueueMessage($targetArticle->getError (), 'error');
			} else {
				$this->userMessageArray[] = '+++ ' . sprintf(JText::_('CROSS_LINK_ADDED_IN_ARTICLE'), $targetArticleId, $targetArticle->title);
			}
		} else {
			$this->userMessageArray[] = '=== ' . sprintf(JText::_('CROSS_LINK_ALREADY_EXIST_IN_ARTICLE'), $targetArticleId, $targetArticle->title);
		}
	}

	/**
	 * Removes the link to the source article into the target article whose id is passed as parm.
	 *
	 * @param string $articleLink
	 * @param int $id
	 */
	private function removeArticleLinkToArticleForId($sourceArticleIdAlias, $targetArticleId) {
		$targetArticle = JTable::getInstance ( 'content' );
		$targetArticle->load ( $targetArticleId );
		$linkSectionComponents = XLinkArticlesHelper::getLinkSectionComponents ( $targetArticle, $this->linkSectionStartString, $this->isSpaceAddedlinkSectionStartString, "No cross-link removed from this article", $this->userMessageArray );

		if (count ( $linkSectionComponents ) == 0) {
			// "link section not present in the intro text of the target article !
			return;
		}

		$targetArticleIntroText = html_entity_decode($targetArticle->introtext);
		$found = strpos ( $targetArticleIntroText, $sourceArticleIdAlias );

		if ($found === false) {
			$this->userMessageArray[] = '=== ' . sprintf(JText::_('CROSS_LINK_NOT_FOUND_IN_ARTICLE'), $targetArticleId, $targetArticle->title);
			return;
		}

		$linksArray = XLinkArticlesHelper::getLinksArray($targetArticle, $this->linkSectionStartString, $this->isSpaceAddedlinkSectionStartString, $this->linkSeparator, $this->isSpaceAddedlinkSeparator, $this->userMessageArray);

		$oldLinkSection = $linkSectionComponents [0];
		$linkSeparatorWithPossibleSpace = ($this->isSpaceAddedlinkSeparator) ? $this->linkSeparator . " " : $this->linkSeparator;
		$newLinksInLinkSection = XLinkArticlesHelper::rebuidLinksWithoutLinkOnSourceArticle($sourceArticleIdAlias, $linksArray, $linkSeparatorWithPossibleSpace);

		if (empty ( $newLinksInLinkSection )) {
			// if there are no more links in the link section, the space after the link section start string must be trimmed !
			$linkSectionComponentsOne = trim($linkSectionComponents [1]);
		} else {
			$linkSectionComponentsOne = $linkSectionComponents [1];
		}

		$newLinkSection = $linkSectionComponentsOne . $newLinksInLinkSection . $linkSectionComponents [3];
		$newIntroText = str_replace ( $oldLinkSection, $newLinkSection, $targetArticleIntroText );
		$targetArticle->introtext = $newIntroText;

		if (! $targetArticle->store ()) {
			JFactory::getApplication()->enqueueMessage($targetArticle->getError (), 'error');
		} else {
			$this->userMessageArray[] = '---- ' . sprintf(JText::_('CROSS_LINK_REMOVED_FROM_ARTICLE'), $targetArticleId, $targetArticle->title);
		}
	}

	/**
	 * Adds a skipped message in the userMessageArray for each article whose id is passed.
	 *
	 * @param int $targetArticleId
	 */
	private function reportSkippedLinkToArticleForIds ( $targetArticleId ) {
		$skippedArticle = JTable::getInstance ( 'content' );
		$skippedArticle->load ( $targetArticleId );
		$this->userMessageArray[] = '=== ' . sprintf(JText::_('LINK_ON_ARTICLE_SKIPPED'), $skippedArticle->id, $skippedArticle->title);
	}
}