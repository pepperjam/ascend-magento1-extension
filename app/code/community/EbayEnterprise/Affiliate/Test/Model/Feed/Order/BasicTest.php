<?php

class EbayEnterprise_Affiliate_Test_Model_Feed_Order_BasicTest
	extends EcomDev_PHPUnit_Test_Case
{
	/**
	 * Test getting the fields to include in the feed. Should be pulling the
	 * comma-separated list of fields from config.xml and splitting it to produce
	 * an array of fields.
	 * @return array
	 */
	public function testFeedFields()
	{
		$config = $this->getHelperMock('eems_affiliate/config', array('getBasicOrderFeedFields'));
		$config->expects($this->any())
			->method('getBasicOrderFeedFields')
			->will($this->returnValue('one,two,three'));
		$this->replaceByMock('helper', 'eems_affiliate/config', $config);
		$feed = Mage::getModel('eems_affiliate/feed_order_basic');
		$this->assertSame(
			array('one', 'two', 'three'),
			EcomDev_Utils_Reflection::invokeRestrictedMethod($feed, '_getFeedFields')
		);
	}
}
