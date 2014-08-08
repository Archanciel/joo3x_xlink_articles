<?php

require_once dirname ( __FILE__ ) . '\..\baseclass\XLinkArticlesTestBase.php';
require_once PLG_XLINK_ARTICLES_PATH . '\xlink_articles_processor.php';

class XLinkArticlesProcessorSkippedArticlesBugTest extends XLinkArticlesTestBase {
	
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

	
	public function testProcessArticleXLinksIntegrationAddLinksWhithSkippedLinks() {
		$linkAddDefPos = 0;	// 0 == position first
		$xLinkProcessor = new XlinkArticlesProcessor ($this->linkSectionStartString, $this->isSpaceAddedlinkSectionStartString, $this->linkSeparator, $this->isSpaceAddedlinkSeparator, $linkAddDefPos);
		
		$sourceArticle_A = JTable::getInstance ( 'content' );
		$sourceArticle_A->load ( 1 );
		
		$xLinkProcessor->processArticleXLinks ( $sourceArticle_A );

		/*
		 *  userMessageArray
		 */
		$userMessageArray = $xLinkProcessor->getUserMessageArray ();
		print_r ($userMessageArray);
		
		$this->assertEquals ( 5 , count ( $userMessageArray), 'count ( $userMessageArray)' );

		$this->assertEquals ( "+++ Cross-link added to 4 - D", $userMessageArray[0], '$userMessageArray[0]' );
		$this->assertEquals ( "+++ Cross-link added to 3 - C", $userMessageArray[1], '$userMessageArray[1]' );
		$this->assertEquals ( "+++ Cross-link added to 2 - B", $userMessageArray[2], '$userMessageArray[2]' );
		$this->assertEquals ( "=== Link on 12 - L skipped. No change performed !", $userMessageArray[3], '$userMessageArray[3]' );
		$this->assertEquals ( "=== Link on 13 - M skipped. No change performed !", $userMessageArray[4], '$userMessageArray[4]' );
	}
	
	/**
	 * Gets the data set to be loaded into the database during setup
	 *
	 * @return xml dataset
	 */
	protected function getDataSet() {
		return $this->createXMLDataSet ( dirname ( __FILE__ ) . '\..\data\xlink_articles_skipped_article_bug_test_data.xml' );
	}
}

?>