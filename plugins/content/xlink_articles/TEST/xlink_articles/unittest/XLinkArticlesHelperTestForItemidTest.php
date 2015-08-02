<?php

require_once dirname ( __FILE__ ) . '\..\baseclass\XLinkArticlesTestBase.php';
require_once PLG_XLINK_ARTICLES_PATH . '\helper.php';

/**
 * This test case ensures that the XLinkArticlesHelper class handles correctly the id of the linked
 * target articles. In Joomla 3, those id's are no longer specified with 'id=', but with 'Itemid=',
 * like in <a href="index.php?option=com_content&amp;view=article&amp;id=580&amp;catid=77&amp;Itemid=118">A</a>.
 *  
 * @author Jean-Pierre
 */
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
	
	/**
	 * Tests that the XLinkArticlesHelper class handles correctly the Itemid= joo3 id attribute.
	 */
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