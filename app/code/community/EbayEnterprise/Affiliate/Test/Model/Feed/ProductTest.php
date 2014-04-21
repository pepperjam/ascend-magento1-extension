<?php

class EbayEnterprise_Affiliate_Test_Model_Feed_ProductTest
	extends EcomDev_PHPUnit_Test_Case
{
	/**
	 * Test that EbayEnterprise_Affiliate_Model_Feed_Product::_getItems will return
	 * a Mage_Catalog_Model_Resource_Product_Collection
	 * @test
	 */
	public function testGetItems()
	{
		$store = Mage::getModel('core/store');
		$collection = $this->getResourceModelMockBuilder('catalog/product_collection')
			->disableOriginalConstructor()
			->setMethods(array('addAttributeToSelect', 'addStoreFilter', 'addFieldToFilter'))
			->getMock();
		$collection->expects($this->once())
			->method('addAttributeToSelect')
			->with($this->identicalTo(array('*')))
			->will($this->returnSelf());
		$collection->expects($this->once())
			->method('addStoreFilter')
			->with($this->identicalTo($store))
			->will($this->returnSelf());
		$collection->expects($this->once())
			->method('addFieldToFilter')
			->with($this->identicalTo('status'), $this->identicalTo(Mage_Catalog_Model_Product_Status::STATUS_ENABLED))
			->will($this->returnSelf());
		$this->replaceByMock('resource_model', 'catalog/product_collection', $collection);

		$feedProduct = Mage::getModel('eems_affiliate/feed_product', array('store' => $store));

		$this->assertSame($collection, EcomDev_Utils_Reflection::invokeRestrictedMethod(
			$feedProduct, '_getItems', array()
		));
	}
	/**
	 * Test that EbayEnterprise_Affiliate_Model_Feed_Product::_getFeedFields will
	 * retrieve a list of field from the configuration and explode it into an array
	 * of fields
	 * @test
	 */
	public function testGetFeedFields()
	{
		$maps = array(
			'field_1' => array('column_name' => 'field_1'),
			'field_2' => array('column_name' => 'field_2'),
			'field_3' => array('column_name' => 'field_3')
		);
		$fields = array('field_1' => 'key 1', 'field_2' => 'key 2', 'field_3' => 'key 3');
		$configHelper = $this->getHelperMock('eems_affiliate/config', array(
			'getProductFeedFields', 'getCallbackMappings'
		));
		$result = array_keys($fields);

		$configHelper->expects($this->once())
			->method('getProductFeedFields')
			->will($this->returnValue($fields));
		$configHelper->expects($this->once())
			->method('getCallbackMappings')
			->will($this->returnValue($maps));
		$this->replaceByMock('helper', 'eems_affiliate/config', $configHelper);

		$feedProduct = Mage::getModel('eems_affiliate/feed_product');

		$this->assertSame($result, EcomDev_Utils_Reflection::invokeRestrictedMethod(
			$feedProduct, '_getFeedFields', array()
		));
	}
	/**
	 * Test that EbayEnterprise_Affiliate_Model_Feed_Product::_getFileName will return
	 * a string representing a feed file name
	 * @test
	 */
	public function testFileName()
	{
		$filenameFormat = '%s_Some_Feed.csv';
		$programId = 'P12';
		$result = sprintf($filenameFormat, $programId);

		$store = Mage::getModel('core/store');
		$configHelper = $this->getHelperMock('eems_affiliate/config', array(
			'getProductFeedFilenameFormat', 'getProgramId'
		));
		$configHelper->expects($this->once())
			->method('getProductFeedFilenameFormat')
			->will($this->returnValue($filenameFormat));
		$configHelper->expects($this->once())
			->method('getProgramId')
			->with($this->identicalTo($store))
			->will($this->returnValue($programId));
		$this->replaceByMock('helper', 'eems_affiliate/config', $configHelper);

		$feedProduct = Mage::getModel('eems_affiliate/feed_product', array('store' => $store));

		$this->assertSame($result, EcomDev_Utils_Reflection::invokeRestrictedMethod(
			$feedProduct, '_getFileName', array()
		));
	}
}
