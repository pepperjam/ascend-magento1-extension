<?php
class EbayEnterprise_Affiliate_Test_Model_ObserverTest
	extends EcomDev_PHPUnit_Test_Case
{
	/**
	 * Test that EbayEnterprise_Affiliate_Model_Observer::createProductFeed
	 * will be invoked and expected the method
	 * EbayEnterprise_Affiliate_Model_Observer::getWebsitesDefaultStoreviews
	 * to be called and returned a known array of program keys map to default
	 * website store views, then expects the method
	 * EbayEnterprise_Affiliate_Model_Product::generateFeed to be invoked per
	 * default program id and store views
	 * @test
	 */
	public function testCreateProductFeed()
	{
		$websitesDefaultStoreViews = array(
			'P1' => Mage::getModel('core/store', array('name' => 'store view 1')),
			'P2' => Mage::getModel('core/store', array('name' => 'store view 2'))
		);
		$programIds = array_keys($websitesDefaultStoreViews);

		$helper = $this->getHelperMock('eems_affiliate/data', array(
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
		$this->replaceByMock('helper', 'eems_affiliate', $helper);

		$productFeed = $this->getModelMockBuilder('eems_affiliate/feed_product')
			->disableOriginalConstructor()
			->setMethods(array('generateFeed'))
			->getMock();
		$productFeed->expects($this->exactly(2))
			->method('generateFeed')
			->will($this->returnSelf());
		$this->replaceByMock('model', 'eems_affiliate/feed_product', $productFeed);

		Mage::getModel('eems_affiliate/observer')->createProductFeed();
	}
}
