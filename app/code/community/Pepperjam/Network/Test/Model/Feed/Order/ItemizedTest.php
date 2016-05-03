<?php
/**
 * Copyright (c) 2016 Pepperjam Network.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Pepperjam Network
 * Magento Extensions End User License Agreement
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://assets.pepperjam.com/legal/magento-connect-extension-eula.pdf
 *
 * @copyright   Copyright (c) 2016 Pepperjam Network. (http://www.pepperjam.com/)
 * @license     http://assets.pepperjam.com/legal/magento-connect-extension-eula.pdf  Pepperjam Network Magento Extensions End User License Agreement
 */

class Pepperjam_Network_Test_Model_Feed_Order_ItemizedTest extends EcomDev_PHPUnit_Test_Case
{
	/**
	 * Test getting the fields to include in the feed. Should be pulling the
	 * comma-separated list of fields from config.xml and splitting it to produce
	 * an array of fields.
	 * @return array
	 */
	public function testFeedFields()
	{
		$config = $this->getHelperMock('pepperjam_network/config', array('getItemizedOrderFeedFields'));
		$config->expects($this->any())
			->method('getItemizedOrderFeedFields')
			->will($this->returnValue('one,two,three'));
		$this->replaceByMock('helper', 'pepperjam_network/config', $config);
		$feed = Mage::getModel('pepperjam_network/feed_order_itemized');
		$this->assertSame(
			array('one', 'two', 'three'),
			EcomDev_Utils_Reflection::invokeRestrictedMethod($feed, '_getFeedFields')
		);
	}
}
