<?php

class XLinkArticlesHelper {
	
	/**
	 * Returns the id's of the articles referenced in the link section of the target article,
	 * null if no link section was found. The function is public in order to be unit testable !
	 * 
	 * @param unknown_type $linksArray
	 */
	public static function getLinkedArticlesIds($linksArray) {
		$linkIds = array();
		$unlinkIds = array();
		$skippedLinkIds = array();
		
		foreach ( $linksArray as $link ) {
			preg_match ( "#&(id|Itemid)=(\d+)#", $link, $matches );
			
			if (preg_match ( "#class=\"LK\"#", $link ) == 1) {
				$linkIds [] = $matches [2];
			} elseif (preg_match ( "#class=\"ULK\"#", $link ) == 1) {
				$unlinkIds [] = $matches [2];
			} else {
				$skippedLinkIds [] =  $matches [2];
			}
		}

		return array($linkIds, $unlinkIds, $skippedLinkIds);
	}

	/**
	 * Returns an array containing the links in the link section of the passed article.
	 * The function is public in order to be unit testable !
	 * 
	 * @param unknown_type $article
	 * @param unknown_type $linkSectionStartString
	 * @param unknown_type $isSpaceAddedlinkSectionStartString
	 * @param unknown_type $userMessageArray	passed by reference !
	 */
	public static function getLinksArray($article, $linkSectionStartString, $isSpaceAddedlinkSectionStartString, $linkSeparator, $isSpaceAddedLinkSeparator, &$userMessageArray) {
		$linksStr = self::getLinksInLinkSection ( $article, $linkSectionStartString, $isSpaceAddedlinkSectionStartString, $userMessageArray );
		
		if (empty ( $linksStr )) {
			// link section not found
			return array();
		}
		
		if ($isSpaceAddedLinkSeparator) {
 			$pattern = "#</a>" . $linkSeparator . "[  ]{1}#";	// warning: 2 different space char are specified in the 
		} else {												// char class: \x20 and \xa0 or ascii 32 and ascii 160 !!!!
			$pattern = "#</a>" . $linkSeparator . "#";
		}
		
		$linksArray = array();
		
		if (preg_match ( $pattern, $linksStr ) > 0) {
			$linksArray = preg_split ( $pattern, $linksStr );
		} else {
			$linksArray = array($linksStr);
		}
		
		// adding the end of html anchor tag removed by the preg_split
		
		for ($i = 0; $i < count($linksArray) - 1; $i++) {
			$linksArray[$i] .= "</a>";
		}
		
		return $linksArray;
	}

	/**
	 * Returns the link part of the link section, null if no link section was found.
	 * 
	 * @param unknown_type $article
	 * @param unknown_type $articleLinkSectionStartString
	 * @param unknown_type $isSpaceAddedlinkSectionStartString
	 * @param unknown_type $userMessageArray	passed by reference !
	 */
	private function getLinksInLinkSection($article, $articleLinkSectionStartString, $isSpaceAddedlinkSectionStartString, &$userMessageArray) {
		$components = self::getLinkSectionComponents ( $article , $articleLinkSectionStartString, $isSpaceAddedlinkSectionStartString, "Cross-link processing not performed", $userMessageArray );
		$linksStr = null;
		
		if (count($components) > 0) {
			$linksStr = $components [2];
		}
			
		return $linksStr;
	}

	/**
	 * Returns the matched groups in the link section.
	 * 
	 * Ex:
	 * 		[0] => Ecouter également <a href="index.php?option=com_content&amp;view=article&id=279:philippe-jurgensen&amp;catid=77:francais">Retraites, un futur sans avenir</a>, <a href="index.php?option=com_content&amp;view=article&id=330:michel-tarrier-dictature-verte&amp;catid=47:francais">Michel Tarrier - dictature verte</a>, <a href="index.php?option=com_content&amp;view=article&id=280:les-causes-structurelles-de-la-crise-alimentaire-en-2008&amp;catid=51:francais">Les causes structurelles de la crise alimentaire en 2008</a>, <a href="index.php?option=com_content&amp;view=article&id=46:albert-jacquard-surpopulation&amp;catid=49:francais">Interview d'Albert Jacquard sur le thème de la surpopulation</a>, <a href="index.php?option=com_content&amp;view=article&id=78:la-contrainte-carbone&amp;catid=47:francais">Jean-Marc Jancovici - La contrainte carbone</a>, <a href="index.php?option=com_content&amp;view=article&id=122:le-premier-economiste-digne-de-ce-nom-nicholas-georgescu-roegen&amp;catid=56:francais">Nicholas Georgescu-Roegen, économiste de génie</a></p>
     *		[1] => Ecouter également 
     *		[2] => <a href="index.php?option=com_content&amp;view=article&id=279:philippe-jurgensen&amp;catid=77:francais">Retraites, un futur sans avenir</a>, <a href="index.php?option=com_content&amp;view=article&id=330:michel-tarrier-dictature-verte&amp;catid=47:francais">Michel Tarrier - dictature verte</a>, <a href="index.php?option=com_content&amp;view=article&id=280:les-causes-structurelles-de-la-crise-alimentaire-en-2008&amp;catid=51:francais">Les causes structurelles de la crise alimentaire en 2008</a>, <a href="index.php?option=com_content&amp;view=article&id=46:albert-jacquard-surpopulation&amp;catid=49:francais">Interview d'Albert Jacquard sur le thème de la surpopulation</a>, <a href="index.php?option=com_content&amp;view=article&id=78:la-contrainte-carbone&amp;catid=47:francais">Jean-Marc Jancovici - La contrainte carbone</a>, <a href="index.php?option=com_content&amp;view=article&id=122:le-premier-economiste-digne-de-ce-nom-nicholas-georgescu-roegen&amp;catid=56:francais">Nicholas Georgescu-Roegen, économiste de génie</a>
     *		[3] => </p>
     *
	 * @param unknown_type $article
	 * @param unknown_type $articleLinkSectionStartString
	 * @param unknown_type $isSpaceAddedlinkSectionStartString
	 * @param unknown_type $noEcouterSectionFoundMsg
	 * @param unknown_type $userMessageArray	passed by reference !
	 */
	public  static function getLinkSectionComponents($article, $articleLinkSectionStartString, $isSpaceAddedlinkSectionStartString, $noEcouterSectionFoundMsg, &$userMessageArray) {
		$articleLinkSectionStartStringWithSepForPattern = ($isSpaceAddedlinkSectionStartString) ? $articleLinkSectionStartString . "[  ]{1}" : $articleLinkSectionStartString;
		$articleLinkSectionStartStringWithSep = ($isSpaceAddedlinkSectionStartString) ? $articleLinkSectionStartString . ' ' : $articleLinkSectionStartString;
		$pattern = "#($articleLinkSectionStartStringWithSepForPattern)(.+)(</p>)#";
		$introTextDecoded = html_entity_decode($article->introtext);
		preg_match ( $pattern, $introTextDecoded, $matches );
		
		if (count ( $matches ) == 0) {
			$pattern = "#($articleLinkSectionStartString)(</p>)#";
			preg_match ( $pattern, $introTextDecoded, $matches );
			if (count ( $matches ) == 0) {
				$userMessageArray [] = '!!! ' . sprintf(JText::_('LINK_SECTION_NOT_FOUND_IN_ARTICLE'), $articleLinkSectionStartStringWithSep, $article->id, $article->title, $noEcouterSectionFoundMsg);
			} else {
				// adding an empty link section to the returned matches
				$matches = array("$articleLinkSectionStartString</p>", $articleLinkSectionStartStringWithSep, "", "</p>");
			}
		}

		return $matches;
	}
	
	/**
	 * Build a link string from the passed links array ommiting the link for the passed
	 * $sourceArticleIdAlias. The function is public in order to be unit testable !
	 * 
	 * @param unknown_type $linkToRemove
	 * @param unknown_type $links
	 */
	public static function rebuidLinksWithoutLinkOnSourceArticle($sourceArticleIdAlias, $linksArray, $linkSeparator) {
		$newLinksInLinkSection = '';
		
		foreach ( $linksArray as $link ) {
			$found = strpos ( $link, $sourceArticleIdAlias );
			
			if ($found === false) {
				$newLinksInLinkSection .= $link . $linkSeparator;
			} // else,link to remove is not added to the new link string
		}
		
		// removing the last linkSeparator
		$matches = null;
		
		if (strlen ( $newLinksInLinkSection ) > 0) {
			$pattern = "#(.+)(" . $linkSeparator . "$)#";
			preg_match($pattern,$newLinksInLinkSection,$matches);
		}
		
		return $matches[1];
	}
}