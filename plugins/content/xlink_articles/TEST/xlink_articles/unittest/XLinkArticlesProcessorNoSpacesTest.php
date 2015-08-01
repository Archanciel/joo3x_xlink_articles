<?php

require_once dirname ( __FILE__ ) . '\..\baseclass\XLinkArticlesTestBase.php';
require_once PLG_XLINK_ARTICLES_PATH . '\xlink_articles_processor.php';

class XLinkArticlesProcessorNoSpacesTest extends XLinkArticlesTestBase {
	
	private $linkSectionStartString;
	private $isSpaceAddedlinkSectionStartString;
	private $linkSeparator;
	private $isSpaceAddedlinkSeparator;
	
	public function setUp() {
		parent::setUp ();
		
		$this->linkSectionStartString = "Ecouter également:";
		$this->isSpaceAddedlinkSectionStartString = 0;
		$this->linkSeparator = ",";
		$this->isSpaceAddedlinkSeparator = 0;
	}
	
	public function testProcessArticleXLinksIntegrationAddLinksAtPosFirst() {
		$linkAddDefPos = 0;	// 0 == position first
		$xLinkProcessor = new XlinkArticlesProcessor ($this->linkSectionStartString, $this->isSpaceAddedlinkSectionStartString, $this->linkSeparator, $this->isSpaceAddedlinkSeparator, $linkAddDefPos);
		
		$sourceArticle_A = JTable::getInstance ( 'content' );
		$sourceArticle_A->load ( 1 );
		
		$xLinkProcessor->processArticleXLinks ( $sourceArticle_A );
		
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
						"<p>Ecouter &eacute;galement:<a href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=1:a\">A</a></p>\n" .
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
						"<p>Ecouter &eacute;galement:<a href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=1:a\">A</a>,<a class=\"LK\" href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=2:b\">B</a></p>\n" .
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
						"<p>Ecouter &eacute;galement:<a href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=1:a\">A</a>,<a class=\"LK\" href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=3:c\">C</a>,<a class=\"LK\" href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=2:b\">B</a></p>\n" .
						"<p>A voir</p>";
		$this->assertEquals ( $expIntroText_D, $targetArticle_D->introtext, '$targetArticle_D->introtext' );
		
		/*
		 *  targetArticle_H 
		 */
		$targetArticle_H = JTable::getInstance ( 'content' );
		$targetArticle_H->load ( 8 );
		$expIntroText_H = "<p>Année: 2006</p>\n" .
						"<p>Durée: 40' 57''</p>\n" .
						"<p>Article H.</p>\n" .
						"<p>Source: <a href=\"http://histoirevivante.rsr.ch/index.html?siteSect=1005&amp;sid=10381313&amp;cKey=1236843106000\" target=\"_blank\">RSR - Histoire Vivante: Paroles de démographes </a></p>\n" . 
						"<p>{audio}/attachments/XXX{/audio}</p>\n" .
						"<div style=\"visibility: hidden; height: 0px;\">{enclose XXX}</div>\n" .
						"<p>A voir</p>";
		$this->assertEquals ( $expIntroText_H, $targetArticle_H->introtext, '$targetArticle_H->introtext' );
		
		/*
		 *  targetArticle_I 
		 */
		$targetArticle_I = JTable::getInstance ( 'content' );
		$targetArticle_I->load ( 9 );
		$expIntroText_I = "<p>Année: 2006</p>\n" .
						"<p>Durée: 40' 57''</p>\n" .
						"<p>Article I.</p>\n" .
						"<p>Source: <a href=\"http://histoirevivante.rsr.ch/index.html?siteSect=1005&amp;sid=10381313&amp;cKey=1236843106000\" target=\"_blank\">RSR - Histoire Vivante: Paroles de démographes </a></p>\n" . 
						"<p>{audio}/attachments/XXX{/audio}</p>\n" .
						"<div style=\"visibility: hidden; height: 0px;\">{enclose XXX}</div>\n" .
						"<p>A voir</p>";
		$this->assertEquals ( $expIntroText_I, $targetArticle_I->introtext, '$targetArticle_I->introtext' );

		/*
		 *  targetArticle_J 
		 */
		$targetArticle_J = JTable::getInstance ( 'content' );
		$targetArticle_J->load ( 10 );
		$expIntroText_J = "<p>Année: 2006</p>\n" .
						"<p>Durée: 40' 57''</p>\n" .
						"<p>Article J.</p>\n" .
						"<p>Source: <a href=\"http://histoirevivante.rsr.ch/index.html?siteSect=1005&amp;sid=10381313&amp;cKey=1236843106000\" target=\"_blank\">RSR - Histoire Vivante: Paroles de démographes </a></p>\n" . 
						"<p>{audio}/attachments/XXX{/audio}</p>\n" .
						"<div style=\"visibility: hidden; height: 0px;\">{enclose XXX}</div>\n" .
						"<p>Ecouter &eacute;galement:<a class=\"LK\" href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=1:a\">A</a></p>\n" .
						"<p>A voir</p>";
		$this->assertEquals ( $expIntroText_J, $targetArticle_J->introtext, '$targetArticle_J->introtext' );

		/*
		 *  targetArticle_K 
		 */
		$targetArticle_K = JTable::getInstance ( 'content' );
		$targetArticle_K->load ( 11 );
		$expIntroText_K = "<p>Année: 2006</p>\n" .
						"<p>Durée: 40' 57''</p>\n" .
						"<p>Article K. Xlink to A no comma to remove</p>\n" .
						"<p>Source: <a href=\"http://histoirevivante.rsr.ch/index.html?siteSect=1005&amp;sid=10381313&amp;cKey=1236843106000\" target=\"_blank\">RSR - Histoire Vivante: Paroles de démographes </a></p>\n" . 
						"<p>{audio}/attachments/XXX{/audio}</p>\n" .
						"<div style=\"visibility: hidden; height: 0px;\">{enclose XXX}</div>\n" .
						"<p>Ecouter &eacute;galement:</p>\n" .
						"<p>A voir</p>";
		$this->assertEquals ( $expIntroText_K, $targetArticle_K->introtext, '$targetArticle_K->introtext' );
		
		/*
		 *  targetArticle_G 
		 */
		$targetArticle_G = JTable::getInstance ( 'content' );
		$targetArticle_G->load ( 7 );
		$expIntroText_G = "<p>Année: 2006</p>\n" .
						"<p>Durée: 40' 57''</p>\n" .
						"<p>Article G. Xlink to A placed left to remove</p>\n" .
						"<p>Source: <a href=\"http://histoirevivante.rsr.ch/index.html?siteSect=1005&amp;sid=10381313&amp;cKey=1236843106000\" target=\"_blank\">RSR - Histoire Vivante: Paroles de démographes </a></p>\n" . 
						"<p>{audio}/attachments/XXX{/audio}</p>\n" .
						"<div style=\"visibility: hidden; height: 0px;\">{enclose XXX}</div>\n" .
						"<p>Ecouter &eacute;galement:<a href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=2:b\">B</a></p>\n" .
						"<p>A voir</p>";
		$this->assertEquals ( $expIntroText_G, $targetArticle_G->introtext, '$targetArticle_G->introtext' );
		
		/*
		 *  targetArticle_F 
		 */
		$targetArticle_F = JTable::getInstance ( 'content' );
		$targetArticle_F->load ( 6 );
		$expIntroText_F = "<p>Année: 2006</p>\n" .
						"<p>Durée: 40' 57''</p>\n" .
						"<p>Article F. Xlink to A placed right to remove</p>\n" .
						"<p>Source: <a href=\"http://histoirevivante.rsr.ch/index.html?siteSect=1005&amp;sid=10381313&amp;cKey=1236843106000\" target=\"_blank\">RSR - Histoire Vivante: Paroles de démographes </a></p>\n" . 
						"<p>{audio}/attachments/XXX{/audio}</p>\n" .
						"<div style=\"visibility: hidden; height: 0px;\">{enclose XXX}</div>\n" .
						"<p>Ecouter &eacute;galement:<a href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=3:c\">C</a></p>\n" .
						"<p>A voir</p>";
		$this->assertEquals ( $expIntroText_F, $targetArticle_F->introtext, '$targetArticle_F->introtext' );
		
		/*
		 *  userMessageArray
		 */
		$userMessageArray = $xLinkProcessor->getUserMessageArray ();
//		print_r ($userMessageArray);
		
		$this->assertEquals ( 12 , count ( $userMessageArray), 'count ( $userMessageArray)' );

		$this->assertEquals ( "=== Cross-link already exists in 10 - J. No change performed !", $userMessageArray[0], '$userMessageArray[0]' );
		$this->assertEquals ( "!!! 'Ecouter &eacute;galement:' section not found in article 8 - H. No cross-link inserted in this article !", $userMessageArray[1], '$userMessageArray[1]' );
		$this->assertEquals ( "+++ Cross-link added to 4 - D", $userMessageArray[2], '$userMessageArray[2]' );
		$this->assertEquals ( "+++ Cross-link added to 3 - C", $userMessageArray[3], '$userMessageArray[3]' );
		$this->assertEquals ( "+++ Cross-link added to 2 - B", $userMessageArray[4], '$userMessageArray[4]' );
		$this->assertEquals ( "!!! Link on article 13 invalid since id 13 not found in DB !", $userMessageArray[5], '$userMessageArray[5]' );
		$this->assertEquals ( "---- Cross-link removed from 11 - K", $userMessageArray[6], '$userMessageArray[6]' );
		$this->assertEquals ( "!!! 'Ecouter &eacute;galement:' section not found in article 9 - I. No cross-link removed from this article !", $userMessageArray[7], '$userMessageArray[7]' );
		$this->assertEquals ( "---- Cross-link removed from 7 - G", $userMessageArray[8], '$userMessageArray[8]' );
		$this->assertEquals ( "---- Cross-link removed from 6 - F", $userMessageArray[9], '$userMessageArray[9]' );
		$this->assertEquals ( "=== Cross-link not found in 5 - E. No change performed !", $userMessageArray[10], '$userMessageArray[10]' );
		$this->assertEquals ( "=== Link on 12 - L skipped. No change performed !", $userMessageArray[11], '$userMessageArray[11]' );

		// applying the xlink processing on same article a second time
		
		$xLinkProcessor = new XlinkArticlesProcessor ($this->linkSectionStartString, $this->isSpaceAddedlinkSectionStartString, $this->linkSeparator, $this->isSpaceAddedlinkSeparator, $linkAddDefPos);
		$xLinkProcessor->processArticleXLinks ( $sourceArticle_A );
		$userMessageArray = $xLinkProcessor->getUserMessageArray ();

		$this->assertEquals ( "=== Cross-link already exists in 10 - J. No change performed !", $userMessageArray[0], '$userMessageArray[0]' );
		$this->assertEquals ( "!!! 'Ecouter &eacute;galement:' section not found in article 8 - H. No cross-link inserted in this article !", $userMessageArray[1], '$userMessageArray[1]' );
		$this->assertEquals ( "=== Cross-link already exists in 4 - D. No change performed !", $userMessageArray[2], '$userMessageArray[2]' );
		$this->assertEquals ( "=== Cross-link already exists in 3 - C. No change performed !", $userMessageArray[3], '$userMessageArray[3]' );
		$this->assertEquals ( "=== Cross-link already exists in 2 - B. No change performed !", $userMessageArray[4], '$userMessageArray[4]' );
		$this->assertEquals ( "!!! Link on article 13 invalid since id 13 not found in DB !", $userMessageArray[5], '$userMessageArray[5]' );
		$this->assertEquals ( "=== Cross-link not found in 11 - K. No change performed !", $userMessageArray[6], '$userMessageArray[6]' );
		$this->assertEquals ( "!!! 'Ecouter &eacute;galement:' section not found in article 9 - I. No cross-link removed from this article !", $userMessageArray[7], '$userMessageArray[7]' );
		$this->assertEquals ( "=== Cross-link not found in 7 - G. No change performed !", $userMessageArray[8], '$userMessageArray[8]' );
		$this->assertEquals ( "=== Cross-link not found in 6 - F. No change performed !", $userMessageArray[9], '$userMessageArray[9]' );
		$this->assertEquals ( "=== Cross-link not found in 5 - E. No change performed !", $userMessageArray[10], '$userMessageArray[10]' );
		$this->assertEquals ( "=== Link on 12 - L skipped. No change performed !", $userMessageArray[11], '$userMessageArray[11]' );
	}
	
	public function testProcessArticleXLinksIntegrationAddLinksAtPosLast() {
		$linkAddDefPos = 1;	// 1 == position last
		$xLinkProcessor = new XlinkArticlesProcessor ($this->linkSectionStartString, $this->isSpaceAddedlinkSectionStartString, $this->linkSeparator, $this->isSpaceAddedlinkSeparator, $linkAddDefPos);
		
		$sourceArticle_A = JTable::getInstance ( 'content' );
		$sourceArticle_A->load ( 1 );
		
		$xLinkProcessor->processArticleXLinks ( $sourceArticle_A );
		
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
						"<p>Ecouter &eacute;galement:<a href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=1:a\">A</a></p>\n" .
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
						"<p>Ecouter &eacute;galement:<a class=\"LK\" href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=2:b\">B</a>,<a href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=1:a\">A</a></p>\n" .
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
						"<p>Ecouter &eacute;galement:<a class=\"LK\" href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=3:c\">C</a>,<a class=\"LK\" href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=2:b\">B</a>,<a href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=1:a\">A</a></p>\n" .
						"<p>A voir</p>";
		$this->assertEquals ( $expIntroText_D, $targetArticle_D->introtext, '$targetArticle_D->introtext' );
		
		/*
		 *  targetArticle_H 
		 */
		$targetArticle_H = JTable::getInstance ( 'content' );
		$targetArticle_H->load ( 8 );
		$expIntroText_H = "<p>Année: 2006</p>\n" .
						"<p>Durée: 40' 57''</p>\n" .
						"<p>Article H.</p>\n" .
						"<p>Source: <a href=\"http://histoirevivante.rsr.ch/index.html?siteSect=1005&amp;sid=10381313&amp;cKey=1236843106000\" target=\"_blank\">RSR - Histoire Vivante: Paroles de démographes </a></p>\n" . 
						"<p>{audio}/attachments/XXX{/audio}</p>\n" .
						"<div style=\"visibility: hidden; height: 0px;\">{enclose XXX}</div>\n" .
						"<p>A voir</p>";
		$this->assertEquals ( $expIntroText_H, $targetArticle_H->introtext, '$targetArticle_H->introtext' );

		/*
		 *  targetArticle_I 
		 */
		$targetArticle_I = JTable::getInstance ( 'content' );
		$targetArticle_I->load ( 9 );
		$expIntroText_I = "<p>Année: 2006</p>\n" .
						"<p>Durée: 40' 57''</p>\n" .
						"<p>Article I.</p>\n" .
						"<p>Source: <a href=\"http://histoirevivante.rsr.ch/index.html?siteSect=1005&amp;sid=10381313&amp;cKey=1236843106000\" target=\"_blank\">RSR - Histoire Vivante: Paroles de démographes </a></p>\n" . 
						"<p>{audio}/attachments/XXX{/audio}</p>\n" .
						"<div style=\"visibility: hidden; height: 0px;\">{enclose XXX}</div>\n" .
						"<p>A voir</p>";
		$this->assertEquals ( $expIntroText_I, $targetArticle_I->introtext, '$targetArticle_I->introtext' );

		/*
		 *  targetArticle_J 
		 */
		$targetArticle_J = JTable::getInstance ( 'content' );
		$targetArticle_J->load ( 10 );
		$expIntroText_J = "<p>Année: 2006</p>\n" .
						"<p>Durée: 40' 57''</p>\n" .
						"<p>Article J.</p>\n" .
						"<p>Source: <a href=\"http://histoirevivante.rsr.ch/index.html?siteSect=1005&amp;sid=10381313&amp;cKey=1236843106000\" target=\"_blank\">RSR - Histoire Vivante: Paroles de démographes </a></p>\n" . 
						"<p>{audio}/attachments/XXX{/audio}</p>\n" .
						"<div style=\"visibility: hidden; height: 0px;\">{enclose XXX}</div>\n" .
						"<p>Ecouter &eacute;galement:<a class=\"LK\" href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=1:a\">A</a></p>\n" .
						"<p>A voir</p>";
		$this->assertEquals ( $expIntroText_J, $targetArticle_J->introtext, '$targetArticle_J->introtext' );

		/*
		 *  targetArticle_K 
		 */
		$targetArticle_K = JTable::getInstance ( 'content' );
		$targetArticle_K->load ( 11 );
		$expIntroText_K = "<p>Année: 2006</p>\n" .
						"<p>Durée: 40' 57''</p>\n" .
						"<p>Article K. Xlink to A no comma to remove</p>\n" .
						"<p>Source: <a href=\"http://histoirevivante.rsr.ch/index.html?siteSect=1005&amp;sid=10381313&amp;cKey=1236843106000\" target=\"_blank\">RSR - Histoire Vivante: Paroles de démographes </a></p>\n" . 
						"<p>{audio}/attachments/XXX{/audio}</p>\n" .
						"<div style=\"visibility: hidden; height: 0px;\">{enclose XXX}</div>\n" .
						"<p>Ecouter &eacute;galement:</p>\n" .
						"<p>A voir</p>";
		$this->assertEquals ( $expIntroText_K, $targetArticle_K->introtext, '$targetArticle_K->introtext' );
		
		/*
		 *  targetArticle_G 
		 */
		$targetArticle_G = JTable::getInstance ( 'content' );
		$targetArticle_G->load ( 7 );
		$expIntroText_G = "<p>Année: 2006</p>\n" .
						"<p>Durée: 40' 57''</p>\n" .
						"<p>Article G. Xlink to A placed left to remove</p>\n" .
						"<p>Source: <a href=\"http://histoirevivante.rsr.ch/index.html?siteSect=1005&amp;sid=10381313&amp;cKey=1236843106000\" target=\"_blank\">RSR - Histoire Vivante: Paroles de démographes </a></p>\n" . 
						"<p>{audio}/attachments/XXX{/audio}</p>\n" .
						"<div style=\"visibility: hidden; height: 0px;\">{enclose XXX}</div>\n" .
						"<p>Ecouter &eacute;galement:<a href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=2:b\">B</a></p>\n" .
						"<p>A voir</p>";
		$this->assertEquals ( $expIntroText_G, $targetArticle_G->introtext, '$targetArticle_G->introtext' );
		
		/*
		 *  targetArticle_F 
		 */
		$targetArticle_F = JTable::getInstance ( 'content' );
		$targetArticle_F->load ( 6 );
		$expIntroText_F = "<p>Année: 2006</p>\n" .
						"<p>Durée: 40' 57''</p>\n" .
						"<p>Article F. Xlink to A placed right to remove</p>\n" .
						"<p>Source: <a href=\"http://histoirevivante.rsr.ch/index.html?siteSect=1005&amp;sid=10381313&amp;cKey=1236843106000\" target=\"_blank\">RSR - Histoire Vivante: Paroles de démographes </a></p>\n" . 
						"<p>{audio}/attachments/XXX{/audio}</p>\n" .
						"<div style=\"visibility: hidden; height: 0px;\">{enclose XXX}</div>\n" .
						"<p>Ecouter &eacute;galement:<a href=\"index.php?option=com_content&amp;view=article&amp;catid=103:francais&amp;id=3:c\">C</a></p>\n" .
						"<p>A voir</p>";
		$this->assertEquals ( $expIntroText_F, $targetArticle_F->introtext, '$targetArticle_F->introtext' );
	
		/*
		 *  userMessageArray
		 */
		$userMessageArray = $xLinkProcessor->getUserMessageArray ();
//		print_r ($userMessageArray);
		
		$this->assertEquals ( 12 , count ( $userMessageArray), 'count ( $userMessageArray)' );

		$this->assertEquals ( "=== Cross-link already exists in 10 - J. No change performed !", $userMessageArray[0], '$userMessageArray[0]' );
		$this->assertEquals ( "!!! 'Ecouter &eacute;galement:' section not found in article 8 - H. No cross-link inserted in this article !", $userMessageArray[1], '$userMessageArray[1]' );
		$this->assertEquals ( "+++ Cross-link added to 4 - D", $userMessageArray[2], '$userMessageArray[2]' );
		$this->assertEquals ( "+++ Cross-link added to 3 - C", $userMessageArray[3], '$userMessageArray[3]' );
		$this->assertEquals ( "+++ Cross-link added to 2 - B", $userMessageArray[4], '$userMessageArray[4]' );
		$this->assertEquals ( "!!! Link on article 13 invalid since id 13 not found in DB !", $userMessageArray[5], '$userMessageArray[5]' );
		$this->assertEquals ( "---- Cross-link removed from 11 - K", $userMessageArray[6], '$userMessageArray[6]' );
		$this->assertEquals ( "!!! 'Ecouter &eacute;galement:' section not found in article 9 - I. No cross-link removed from this article !", $userMessageArray[7], '$userMessageArray[7]' );
		$this->assertEquals ( "---- Cross-link removed from 7 - G", $userMessageArray[8], '$userMessageArray[8]' );
		$this->assertEquals ( "---- Cross-link removed from 6 - F", $userMessageArray[9], '$userMessageArray[9]' );
		$this->assertEquals ( "=== Cross-link not found in 5 - E. No change performed !", $userMessageArray[10], '$userMessageArray[10]' );
		$this->assertEquals ( "=== Link on 12 - L skipped. No change performed !", $userMessageArray[11], '$userMessageArray[11]' );

		// applying the xlink processing on same article a second time
		
		$xLinkProcessor = new XlinkArticlesProcessor ($this->linkSectionStartString, $this->isSpaceAddedlinkSectionStartString, $this->linkSeparator, $this->isSpaceAddedlinkSeparator, $linkAddDefPos);
		$xLinkProcessor->processArticleXLinks ( $sourceArticle_A );
		$userMessageArray = $xLinkProcessor->getUserMessageArray ();

		$this->assertEquals ( "=== Cross-link already exists in 10 - J. No change performed !", $userMessageArray[0], '$userMessageArray[0]' );
		$this->assertEquals ( "!!! 'Ecouter &eacute;galement:' section not found in article 8 - H. No cross-link inserted in this article !", $userMessageArray[1], '$userMessageArray[1]' );
		$this->assertEquals ( "=== Cross-link already exists in 4 - D. No change performed !", $userMessageArray[2], '$userMessageArray[2]' );
		$this->assertEquals ( "=== Cross-link already exists in 3 - C. No change performed !", $userMessageArray[3], '$userMessageArray[3]' );
		$this->assertEquals ( "=== Cross-link already exists in 2 - B. No change performed !", $userMessageArray[4], '$userMessageArray[4]' );
		$this->assertEquals ( "!!! Link on article 13 invalid since id 13 not found in DB !", $userMessageArray[5], '$userMessageArray[5]' );
		$this->assertEquals ( "=== Cross-link not found in 11 - K. No change performed !", $userMessageArray[6], '$userMessageArray[6]' );
		$this->assertEquals ( "!!! 'Ecouter &eacute;galement:' section not found in article 9 - I. No cross-link removed from this article !", $userMessageArray[7], '$userMessageArray[7]' );
		$this->assertEquals ( "=== Cross-link not found in 7 - G. No change performed !", $userMessageArray[8], '$userMessageArray[8]' );
		$this->assertEquals ( "=== Cross-link not found in 6 - F. No change performed !", $userMessageArray[9], '$userMessageArray[9]' );
		$this->assertEquals ( "=== Cross-link not found in 5 - E. No change performed !", $userMessageArray[10], '$userMessageArray[10]' );
		$this->assertEquals ( "=== Link on 12 - L skipped. No change performed !", $userMessageArray[11], '$userMessageArray[11]' );
	}

	public function testProcessArticleXLinksEmptyLinkSectionPosFirst() {
		$linkAddDefPos = 0;	// 0 == position first
		$xLinkProcessor = new XlinkArticlesProcessor ($this->linkSectionStartString, $this->isSpaceAddedlinkSectionStartString, $this->linkSeparator, $this->isSpaceAddedlinkSeparator, $linkAddDefPos);
	
		$sourceArticle_B = JTable::getInstance ( 'content' );
		$sourceArticle_B->load ( 2 );
	
		$xLinkProcessor->processArticleXLinks ( $sourceArticle_B );
	
		/*
		 *  userMessageArray
		*/
		$userMessageArray = $xLinkProcessor->getUserMessageArray ();
		//		print_r ($userMessageArray);
	
		$this->assertEquals ( 0 , count ( $userMessageArray), 'count ( $userMessageArray)' );
	}
	
	public function testProcessArticleXLinksNoLinkSectionPosFirst() {
		$linkAddDefPos = 0;	// 0 == position first
		$xLinkProcessor = new XlinkArticlesProcessor ($this->linkSectionStartString, $this->isSpaceAddedlinkSectionStartString, $this->linkSeparator, $this->isSpaceAddedlinkSeparator, $linkAddDefPos);
	
		$sourceArticle_H = JTable::getInstance ( 'content' );
		$sourceArticle_H->load ( 8 );
	
		$xLinkProcessor->processArticleXLinks ( $sourceArticle_H );
	
		/*
		 *  userMessageArray
		*/
		$userMessageArray = $xLinkProcessor->getUserMessageArray ();
		// 				print_r ($userMessageArray);
	
		$this->assertEquals ( 1 , count ( $userMessageArray), 'count ( $userMessageArray)' );
	
		$this->assertEquals ( "!!! 'Ecouter &eacute;galement:' section not found in article 8 - H. Cross-link processing not performed !", $userMessageArray[0], '$userMessageArray[0]' );
	}
	
	public function testProcessArticleXLinksEmptyLinkSectionPosLast() {
		$linkAddDefPos = 1;	// 0 == position first
		$xLinkProcessor = new XlinkArticlesProcessor ($this->linkSectionStartString, $this->isSpaceAddedlinkSectionStartString, $this->linkSeparator, $this->isSpaceAddedlinkSeparator, $linkAddDefPos);
	
		$sourceArticle_B = JTable::getInstance ( 'content' );
		$sourceArticle_B->load ( 2 );
	
		$xLinkProcessor->processArticleXLinks ( $sourceArticle_B );
	
		/*
		 *  userMessageArray
		*/
		$userMessageArray = $xLinkProcessor->getUserMessageArray ();
		//		print_r ($userMessageArray);
	
		$this->assertEquals ( 0 , count ( $userMessageArray), 'count ( $userMessageArray)' );
	}
	
	public function testProcessArticleXLinksNoLinkSectionPosLast() {
		$linkAddDefPos = 1;	// 0 == position first
		$xLinkProcessor = new XlinkArticlesProcessor ($this->linkSectionStartString, $this->isSpaceAddedlinkSectionStartString, $this->linkSeparator, $this->isSpaceAddedlinkSeparator, $linkAddDefPos);
	
		$sourceArticle_H = JTable::getInstance ( 'content' );
		$sourceArticle_H->load ( 8 );
	
		$xLinkProcessor->processArticleXLinks ( $sourceArticle_H );
	
		/*
		 *  userMessageArray
		*/
		$userMessageArray = $xLinkProcessor->getUserMessageArray ();
		// 				print_r ($userMessageArray);
	
		$this->assertEquals ( 1 , count ( $userMessageArray), 'count ( $userMessageArray)' );
	
		$this->assertEquals ( "!!! 'Ecouter &eacute;galement:' section not found in article 8 - H. Cross-link processing not performed !", $userMessageArray[0], '$userMessageArray[0]' );
	}
	
	/**
	 * Gets the data set to be loaded into the database during setup
	 *
	 * @return xml dataset
	 */
	protected function getDataSet() {
		return $this->createXMLDataSet ( dirname ( __FILE__ ) . '\..\data\xlink_articles_no_spaces_test_data.xml' );
	}
}

?>