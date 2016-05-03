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

class Pepperjam_Network_Test_Helper_Map_ProductTest extends EcomDev_PHPUnit_Test_Case
{
	/**
	 * Test that Pepperjam_Network_Helper_Map_Product::getCategory will
	 * return a string of root category separated with the symbol '>' with inner
	 * category to the the inner most category.
	 */
	public function testGetCategory()
	{

		$categoryIds = array('1','2','3');
		$path = implode('/', $categoryIds);
		$categories = array('cat one', 'cat two', 'cat three');
		$collection = Mage::getResourceModel('catalog/category_collection');

		$categoryObjects = array();
		foreach ($categories as $index => $name) {
			$category = Mage::getModel('catalog/category', array(
				'name' => $name, 'entity_id' => $index + 9999, 'path' => $path
			));
			$categoryObjects[] = $category;
			$collection->addItem($category);
		}

		$productCategoryColl = Mage::getResourceModel('catalog/category_collection');
		;
		$productCategoryColl->addItem($categoryObjects[2]);
		$product = $this->getModelMock('catalog/product', array('getCategoryCollection'));
		$product->expects($this->once())
			->method('getCategoryCollection')
			->will($this->returnValue($productCategoryColl));

		$params = array('item' => $product);

		$result = implode(' > ', $categories);

		$mapProduct = $this->getHelperMock('pepperjam_network/map_product', array('_getCategoriesByIds'));
		$mapProduct->expects($this->once())
			->method('_getCategoriesByIds')
			->with($this->identicalTo($categoryIds))
			->will($this->returnValue($collection));

		$this->assertSame($result, $mapProduct->getCategory($params));
	}
	/**
	 * Test that Pepperjam_Network_Helper_Map_Product::getImageUrl will
	 * return an absolute URL to an image view.
	 */
	public function testGetImageUrl()
	{
		$key = 'image';
		$image = '/k/o/some-product-image.jpg';
		$mediaUrl = 'http://www.example.com/' .
			Pepperjam_Network_Helper_Map_Product::MEDIA_PATH . $image;

		$product = $this->getModelMockBuilder('catalog/product')
			->disableOriginalConstructor()
			->setMethods(array('getDataUsingMethod'))
			->getMock();
		$product->expects($this->once())
			->method('getDataUsingMethod')
			->with($this->identicalTo($key))
			->will($this->returnValue($image));

		$params = array('item' => $product, 'key' => $key);

		$helperProduct = $this->getHelperMock('pepperjam_network/map_product', array(
			'_getAbsoluteImagePath'
		));

		$helperProduct->expects($this->once())
			->method('_getAbsoluteImagePath')
			->with($this->identicalTo($image))
			->will($this->returnValue($mediaUrl));

		$this->assertSame($mediaUrl, $helperProduct->getImageUrl($params));
	}
	/**
	 * Test that Pepperjam_Network_Helper_Map_Product::getInStockQty will
	 * return an integer value representing the manage stock qty of a product.
	 */
	public function testGetInStockQty()
	{
		$qty = 999;
		$product = Mage::getModel('catalog/product');
		$params = array('item' => $product);

		$stockItem = $this->getModelMockBuilder('cataloginventory/stock_item')
			->disableOriginalConstructor()
			->setMethods(array('loadByProduct', 'getQty'))
			->getMock();
		$stockItem->expects($this->once())
			->method('loadByProduct')
			->with($this->identicalTo($product))
			->will($this->returnSelf());
		$stockItem->expects($this->once())
			->method('getQty')
			->will($this->returnValue($qty));
		$this->replaceByMock('model', 'cataloginventory/stock_item', $stockItem);

		$this->assertSame($qty, Mage::helper('pepperjam_network/map_product')->getInStockQty($params));
	}
	/**
	 * Test that Pepperjam_Network_Helper_Map_Product::_getCategoriesByIds
	 * will be invoked by this test passing an array of category entity ids
	 * and will return a Mage_Catalog_Model_Resource_Category_Collection
	 */
	public function testGetCategoriesByIds()
	{
		$entityIds = array(1,2,3);
		$collection = $this->getResourceModelMockBuilder('catalog/category_collection')
			->disableOriginalConstructor()
			->setMethods(array('addAttributeToSelect', 'addAttributeToFilter', 'load'))
			->getMock();
		$collection->expects($this->once())
			->method('addAttributeToSelect')
			->with($this->identicalTo(array('name', 'entity_id')))
			->will($this->returnSelf());
		$collection->expects($this->once())
			->method('addAttributeToFilter')
			->with($this->identicalTo(array(array('attribute' => 'entity_id', 'in' => $entityIds))))
			->will($this->returnSelf());
		$collection->expects($this->once())
			->method('load')
			->will($this->returnSelf());
		$this->replaceByMock('resource_model', 'catalog/category_collection', $collection);

		$mapProduct = $this->getHelperMock('pepperjam_network/map_product', array());

		$this->assertSame($collection, EcomDev_Utils_Reflection::invokeRestrictedMethod(
			$mapProduct,
			'_getCategoriesByIds',
			array($entityIds)
		));
	}
	/**
	 * Test that Pepperjam_Network_Helper_Map_Product::getInStockYesNo will
	 * be called by this test passing an array with key item map to a
	 * Mage_Catalog_Model_Product object and expects it to return the string value 'yes'.
	 */
	public function testGetInStockYesNo()
	{
		$result = 'yes';
		$isInStock = '1';
		$params = array('item' => Mage::getModel('catalog/product'));

		$stockItem = $this->getModelMock('cataloginventory/stock_item', array('loadByProduct'));
		$stockItem->expects($this->once())
			->method('loadByProduct')
			->with($this->identicalTo($params['item']))
			->will($this->returnSelf());
		$stockItem->setIsInstock($isInStock);
		$this->replaceByMock('model', 'cataloginventory/stock_item', $stockItem);

		$this->assertSame($result, Mage::helper('pepperjam_network/map_product')->getInStockYesNo($params));
	}
}
