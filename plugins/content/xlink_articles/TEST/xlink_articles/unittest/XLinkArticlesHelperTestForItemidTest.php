<?php

require_once dirname ( __FILE__ ) . '\..\baseclass\XLinkArticlesTestBase.php';
require_once PLG_XLINK_ARTICLES_PATH . '\helper.php';

class XLinkArticlesHelperTestForItemidTest extends XLinkArticlesTestBase {
	
	private $linkSectionStartString;
	private $isSpaceAddedlinkSectionStartString;
	private $linkSeparator;
	private $isSpaceAddedLinkSeparator;
	
	/**
	 * Array receiving the msg to be displayed to the user after the plugin has finished 
	 * processing.
	 */
	private $userMessageArray;
	
	public function setUp() {
		parent::setUp ();
		
		$this->linkSectionStartString = "Ecouter également";
		$this->isSpaceAddedlinkSectionStartString = 1;
		$this->linkSectionStartStringSepOneSpace = " ";
		$this->linkSeparator = ",";
		$this->isSpaceAddedLinkSeparator = 1;
	}
	
	public function testGetLinkedArticlesIdsMultipleLinksAllKinds() {
		$sourceArticle_A = JTable::getInstance ( 'content' );
		$sourceArticle_A->load ( 1 );
		$linksArray = XLinkArticlesHelper::getLinksArray($sourceArticle_A, $this->linkSectionStartString, $this->isSpaceAddedlinkSectionStartString, $this->linkSeparator, $this->isSpaceAddedLinkSeparator, $this->userMessageArray);
		
		$res = XLinkArticlesHelper::getLinkedArticlesIds( $linksArray );
		
		$this->assertEquals(3, count($res),'count($res)');
		
		$linkIds = $res[0];
		$unlinkIds = $res[1];
		$skippedLinkIds = $res[2];
		$this->assertEquals(array(10,8,4,3,2,13),$linkIds,'$linkIds');
		$this->assertEquals(array(11,9,7,6,5),$unlinkIds,'$unlinkIds');
		$this->assertEquals(array(12),$skippedLinkIds,'$skippedLinkIds');
	}
	
	/**
	 * Gets the data set to be loaded into the database during setup
	 *
	 * @return xml dataset
	 */
	protected function getDataSet() {
		return $this->createXMLDataSet ( dirname ( __FILE__ ) . '\..\data\xlink_articles_test_data_Itemid.xml' );
	}
}

?>