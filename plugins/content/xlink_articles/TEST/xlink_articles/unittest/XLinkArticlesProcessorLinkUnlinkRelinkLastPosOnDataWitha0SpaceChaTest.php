<?php

require_once dirname ( __FILE__ ) . '\..\baseclass\XLinkArticlesTestBase.php';
require_once PLG_XLINK_ARTICLES_PATH . '\xlink_articles_processor.php';

class XLinkArticlesProcessorLinkUnlinkRelinkLastPosOnDataWitha0SpaceChaTest extends XLinkArticlesTestBase {
	
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
	
	public function testProcessArticleXLinksIntegrationAtPosLastLinkUnlinkRelink() {
		$linkAddDefPos = 1;	// 0 == position first
		$xLinkProcessor = new XlinkArticlesProcessor ($this->linkSectionStartString, $this->isSpaceAddedlinkSectionStartString, $this->linkSeparator, $this->isSpaceAddedlinkSeparator, $linkAddDefPos);
		
		$sourceArticle_A_LK = JTable::getInstance ( 'content' );
		$sourceArticle_A_LK->load ( 1 );
		
		// processing the LK
		
		$xLinkProcessor->processArticleXLinks ( $sourceArticle_A_LK );
		
		$this->checkLastPosLinksAfterLK($xLinkProcessor, $sourceArticle_A_LK);
		
		// replacing LK by ULK in article A introtext
		
		$sourceArticleIntroTextWithLK = $sourceArticle_A_LK->introtext;
		$sourceArticleIntroTextWithULK = preg_replace("#(class=\"LK\")#", "class=\"ULK\"", $sourceArticleIntroTextWithLK);
		$sourceArticle_A_LK->introtext = $sourceArticleIntroTextWithULK;
		
		// processing the ULK

		$xLinkProcessor = new XlinkArticlesProcessor ($this->linkSectionStartString, $this->isSpaceAddedlinkSectionStartString, $this->linkSeparator, $this->isSpaceAddedlinkSeparator, $linkAddDefPos);
		$xLinkProcessor->processArticleXLinks ( $sourceArticle_A_LK );
		
		$this->checkLastPosLinksAfterULK($xLinkProcessor, $sourceArticle_A_LK);	
		
		// replacing ULK by LK in article A introtext
		
		$sourceArticleIntroTextWithULK_ = $sourceArticle_A_LK->introtext;
		$sourceArticleIntroTextWithLK_ = preg_replace("#(class=\"ULK\")#", "class=\"LK\"", $sourceArticleIntroTextWithULK_);
		$sourceArticle_A_LK->introtext = $sourceArticleIntroTextWithLK_;

		// processing the LK again
		
		$xLinkProcessor = new XlinkArticlesProcessor ($this->linkSectionStartString, $this->isSpaceAddedlinkSectionStartString, $this->linkSeparator, $this->isSpaceAddedlinkSeparator, $linkAddDefPos);
		$xLinkProcessor->processArticleXLinks ( $sourceArticle_A_LK );
		
		$this->checkLastPosLinksAfterLK($xLinkProcessor, $sourceArticle_A_LK);		
	}
	
	private function checkLastPosLinksAfterLK($xLinkProcessor, $sourceArticle_A) {
		/*
		 *  targetArticle_B
		*/
		$targetArticle_B = JTable::getInstance ( 'content' );
		$targetArticle_B->load ( 2 );
		$expIntroText_B = "<p>Année: 2006</p>\n" .
				"<p>Durée: 40' 57''</p>\n" .
				"<p>Article B.</p>\n" .
				"<p>Source: <a href=\"http://histoirevivante.rsr.ch/index.html?siteSect=1005&amp;sid=10381313&amp;cKey=1236843106000\" target=\"_blank\">RSR - Histoire Vivante: Paroles de démographes </a></p>\n" .
				"<p>{audio}/attachments/XXX{/audio}</p>\n" .
				"<div style=\"visibility: hidden; height: 0px;\">{enclose XXX}</div>\n" .
				"<p>Ecouter également <a href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=1:a\">A</a></p>\n" .
				"<p>A voir</p>";
		$this->assertEquals ( $expIntroText_B, $targetArticle_B->introtext, '$targetArticle_B->introtext' );
	
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
				"<p>Ecouter également <a class=\"LK\" href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=2:b\">B</a>, <a href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=1:a\">A</a></p>\n" .
				"<p>A voir</p>";
		$this->assertEquals ( $expIntroText_C, $targetArticle_C->introtext, '$targetArticle_C->introtext' );
	
		/*
		 *  targetArticle_D
		*/
		$targetArticle_D = JTable::getInstance ( 'content' );
		$targetArticle_D->load ( 4 );
		$expIntroText_D = "<p>Année: 2006</p>\n" .
				"<p>Durée: 40' 57''</p>\n" .
				"<p>Article D.</p>\n" .
				"<p>Source: <a href=\"http://histoirevivante.rsr.ch/index.html?siteSect=1005&amp;sid=10381313&amp;cKey=1236843106000\" target=\"_blank\">RSR - Histoire Vivante: Paroles de démographes </a></p>\n" .
				"<p>{audio}/attachments/XXX{/audio}</p>\n" .
				"<div style=\"visibility: hidden; height: 0px;\">{enclose XXX}</div>\n" .
				"<p>Ecouter également <a class=\"LK\" href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=3:c\">C</a>, <a class=\"LK\" href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=2:b\">B</a>, <a href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=1:a\">A</a></p>\n" .
				"<p>A voir</p>";
		$this->assertEquals ( $expIntroText_D, $targetArticle_D->introtext, '$targetArticle_D->introtext' );
	
		/*
		 *  userMessageArray
		*/
		$userMessageArray = $xLinkProcessor->getUserMessageArray ();
		// 		print_r ($userMessageArray);
	
		$this->assertEquals ( 3 , count ( $userMessageArray), 'count ( $userMessageArray)' );
	
		$this->assertEquals ( "+++ Cross-link added to 4 - D", $userMessageArray[0], '$userMessageArray[0]' );
		$this->assertEquals ( "+++ Cross-link added to 3 - C", $userMessageArray[1], '$userMessageArray[1]' );
		$this->assertEquals ( "+++ Cross-link added to 2 - B", $userMessageArray[2], '$userMessageArray[2]' );
	
		// applying the xlink processing on same article a second time
	
		$xLinkProcessor = new XlinkArticlesProcessor ($this->linkSectionStartString, $this->isSpaceAddedlinkSectionStartString, $this->linkSeparator, $this->isSpaceAddedlinkSeparator, $linkAddDefPos);
		$xLinkProcessor->processArticleXLinks ( $sourceArticle_A );
		$userMessageArray = $xLinkProcessor->getUserMessageArray ();
	
		$this->assertEquals ( "=== Cross-link already exists in 4 - D. No change performed !", $userMessageArray[0], '$userMessageArray[0]' );
		$this->assertEquals ( "=== Cross-link already exists in 3 - C. No change performed !", $userMessageArray[1], '$userMessageArray[1]' );
		$this->assertEquals ( "=== Cross-link already exists in 2 - B. No change performed !", $userMessageArray[2], '$userMessageArray[2]' );
	}
	
	private function checkLastPosLinksAfterULK($xLinkProcessor, $sourceArticle_A) {
		/*
		 *  targetArticle_B
		*/
		$targetArticle_B = JTable::getInstance ( 'content' );
		$targetArticle_B->load ( 2 );
		$expIntroText_B = "<p>Année: 2006</p>\n" .
				"<p>Durée: 40' 57''</p>\n" .
				"<p>Article B.</p>\n" .
				"<p>Source: <a href=\"http://histoirevivante.rsr.ch/index.html?siteSect=1005&amp;sid=10381313&amp;cKey=1236843106000\" target=\"_blank\">RSR - Histoire Vivante: Paroles de démographes </a></p>\n" .
				"<p>{audio}/attachments/XXX{/audio}</p>\n" .
				"<div style=\"visibility: hidden; height: 0px;\">{enclose XXX}</div>\n" .
				"<p>Ecouter également</p>\n" .
				"<p>A voir</p>";
		$this->assertEquals ( $expIntroText_B, $targetArticle_B->introtext, '$targetArticle_B->introtext' );
	
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
				"<p>Ecouter également <a class=\"LK\" href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=2:b\">B</a></p>\n" .
				"<p>A voir</p>";
		$this->assertEquals ( $expIntroText_C, $targetArticle_C->introtext, '$targetArticle_C->introtext' );
	
		/*
		 *  targetArticle_D
		*/
		$targetArticle_D = JTable::getInstance ( 'content' );
		$targetArticle_D->load ( 4 );
		$expIntroText_D = "<p>Année: 2006</p>\n" .
				"<p>Durée: 40' 57''</p>\n" .
				"<p>Article D.</p>\n" .
				"<p>Source: <a href=\"http://histoirevivante.rsr.ch/index.html?siteSect=1005&amp;sid=10381313&amp;cKey=1236843106000\" target=\"_blank\">RSR - Histoire Vivante: Paroles de démographes </a></p>\n" .
				"<p>{audio}/attachments/XXX{/audio}</p>\n" .
				"<div style=\"visibility: hidden; height: 0px;\">{enclose XXX}</div>\n" .
				"<p>Ecouter également <a class=\"LK\" href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=3:c\">C</a>, <a class=\"LK\" href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=2:b\">B</a></p>\n" .
				"<p>A voir</p>";
		$this->assertEquals ( $expIntroText_D, $targetArticle_D->introtext, '$targetArticle_D->introtext' );
	
		/*
		 *  userMessageArray
		*/
		$userMessageArray = $xLinkProcessor->getUserMessageArray ();
		// 		print_r ($userMessageArray);
	
		$this->assertEquals ( 3 , count ( $userMessageArray), 'count ( $userMessageArray)' );
	
		$this->assertEquals ( "---- Cross-link removed from 4 - D", $userMessageArray[0], '$userMessageArray[0]' );
		$this->assertEquals ( "---- Cross-link removed from 3 - C", $userMessageArray[1], '$userMessageArray[1]' );
		$this->assertEquals ( "---- Cross-link removed from 2 - B", $userMessageArray[2], '$userMessageArray[2]' );
	
		// applying the xlink processing on same article a second time
	
		$xLinkProcessor = new XlinkArticlesProcessor ($this->linkSectionStartString, $this->isSpaceAddedlinkSectionStartString, $this->linkSeparator, $this->isSpaceAddedlinkSeparator, $linkAddDefPos);
		$xLinkProcessor->processArticleXLinks ( $sourceArticle_A );
		$userMessageArray = $xLinkProcessor->getUserMessageArray ();
	
		$this->assertEquals ( "=== Cross-link not found in 4 - D. No change performed !", $userMessageArray[0], '$userMessageArray[0]' );
		$this->assertEquals ( "=== Cross-link not found in 3 - C. No change performed !", $userMessageArray[1], '$userMessageArray[1]' );
		$this->assertEquals ( "=== Cross-link not found in 2 - B. No change performed !", $userMessageArray[2], '$userMessageArray[2]' );
	}

	/**
	 * Gets the data set to be loaded into the database during setup
	 *
	 * @return xml dataset
	 */
	protected function getDataSet() {
		return $this->createXMLDataSet ( dirname ( __FILE__ ) . '\..\data\test_alternate_a0_space_char_link_unlink_relink.xml' );
	}
}

?>