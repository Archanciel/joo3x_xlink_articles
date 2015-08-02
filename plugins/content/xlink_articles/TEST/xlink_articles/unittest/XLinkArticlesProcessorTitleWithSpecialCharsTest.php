<?php

require_once dirname ( __FILE__ ) . '\..\baseclass\XLinkArticlesTestBase.php';
require_once PLG_XLINK_ARTICLES_PATH . '\xlink_articles_processor.php';

class XLinkArticlesProcessorTitleWithSpecialCharsTest extends XLinkArticlesTestBase {
	
	private $linkSectionStartString;
	private $isSpaceAddedlinkSectionStartString;
	private $linkSeparator;
	private $isSpaceAddedlinkSeparator;
	
	public function setUp() {
		parent::setUp ();
		
		$this->linkSectionStartString = "Ecouter également";
		$this->isSpaceAddedlinkSectionStartString = 1;
		$this->linkSeparator = ",";
		$this->isSpaceAddedlinkSeparator = 1;
	}
	
	public function testProcessArticleXLinksIntegrationAddLinksAtPosFirst() {
		$linkAddDefPos = 0;	// 0 == position first
		$xLinkProcessor = new XlinkArticlesProcessor ($this->linkSectionStartString, $this->isSpaceAddedlinkSectionStartString, $this->linkSeparator, $this->isSpaceAddedlinkSeparator, $linkAddDefPos);
		
		$sourceArticle_A = JTable::getInstance ( 'content' );
		$sourceArticle_A->load ( 1 );
		
		$xLinkProcessor->processArticleXLinks ( $sourceArticle_A );
				
		/*
		 *  targetArticle_C 
		 */
		$targetArticle_C = JTable::getInstance ( 'content' );
		$targetArticle_C->load ( 3 );
		$expIntroText_C = "<p>Année: 2006</p>\n" .
						"<p>Durée: 40' 57''</p>\n" .
						"<p>Article C.</p>\n" .
						"<p>Source: <a href=\"http://histoirevivante.rsr.ch/index.html?siteSect=1005&amp;sid=10381313&amp;cKey=1236843106000\" target=\"_blank\">RSR - Histoire Vivante: Paroles de démographes </a></p>\n" . 
						"<p>{audio}/attachments/XXX{/audio}</p>\n" .
						"<div style=\"visibility: hidden; height: 0px;\">{enclose XXX}</div>\n" .
						"<p>Ecouter &eacute;galement <a href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=1:a\">O&ugrave; sont pass&eacute;s les durs &agrave; cuire ?</a>, <a class=\"LK\" href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=2:b\">B</a></p>\n" .
						"<p>A voir</p>";
		$this->assertEquals ( $expIntroText_C, $targetArticle_C->introtext, '$targetArticle_C->introtext' );
		
		/*
		 *  userMessageArray
		 */
		$userMessageArray = $xLinkProcessor->getUserMessageArray ();
//		print_r ($userMessageArray);
		
		$this->assertEquals ( 1 , count ( $userMessageArray), 'count ( $userMessageArray)' );

		$this->assertEquals ( "+++ Cross-link added to 3 - C", $userMessageArray[0], '$userMessageArray[3]' );

		// applying the xlink processing on same article a second time
		
		$xLinkProcessor = new XlinkArticlesProcessor ($this->linkSectionStartString, $this->isSpaceAddedlinkSectionStartString, $this->linkSeparator, $this->isSpaceAddedlinkSeparator, $linkAddDefPos);
		$xLinkProcessor->processArticleXLinks ( $sourceArticle_A );
		$userMessageArray = $xLinkProcessor->getUserMessageArray ();

		$this->assertEquals ( "=== Cross-link already exists in 3 - C. No change performed !", $userMessageArray[0], '$userMessageArray[3]' );
	}
	
	/**
	 * Gets the data set to be loaded into the database during setup
	 *
	 * @return xml dataset
	 */
	protected function getDataSet() {
		return $this->createXMLDataSet ( dirname ( __FILE__ ) . '\..\data\xlink_articles_test_data_special_chars_in_title.xml' );
	}
}

?>