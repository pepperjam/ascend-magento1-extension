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

class Pepperjam_Network_Test_Model_ObserverTest extends EcomDev_PHPUnit_Test_Case
{
	/**
	 * Test that Pepperjam_Network_Model_Observer::createProductFeed
	 * will be invoked and expected the method
	 * Pepperjam_Network_Model_Observer::getWebsitesDefaultStoreviews
	 * to be called and returned a known array of program keys map to default
	 * website store views, then expects the method
	 * Pepperjam_Network_Model_Product::generateFeed to be invoked per
	 * default program id and store views
	 */
	public function testCreateProductFeed()
	{
		$websitesDefaultStoreViews = array(
			'P1' => Mage::getModel('core/store', array('name' => 'store view 1')),
			'P2' => Mage::getModel('core/store', array('name' => 'store view 2'))
		);
		$programIds = array_keys($websitesDefaultStoreViews);

		$helper = $this->getHelperMock('pepperjam_network/data', array(
			'getAllProgramIds', 'getStoreForProgramId'
		));
		$helper->expects($this->once())
			->method('getAllProgramIds')
			->will($this->returnValue($programIds));
		$helper->expects($this->exactly(2))
			->method('getStoreForProgramId')
			->will($this->returnValueMap(array(
				array($programIds[0], $websitesDefaultStoreViews[$programIds[0]]),
				array($programIds[1], $websitesDefaultStoreViews[$programIds[1]])
			)));
		$this->replaceByMock('helper', 'pepperjam_network', $helper);

		$productFeed = $this->getModelMockBuilder('pepperjam_network/feed_product')
			->disableOriginalConstructor()
			->setMethods(array('generateFeed'))
			->getMock();
		$productFeed->expects($this->exactly(2))
			->method('generateFeed')
			->will($this->returnSelf());
		$this->replaceByMock('model', 'pepperjam_network/feed_product', $productFeed);

		Mage::getModel('pepperjam_network/observer')->createProductFeed();
	}
}
