<?php
class EbayEnterprise_Affiliate_Test_Helper_Map_ProductTest
	extends EcomDev_PHPUnit_Test_Case
{
	/**
	 * Test that EbayEnterprise_Affiliate_Helper_Map_Product::getCategory will
	 * return a string of root category separated with the symbol '>' with inner
	 * category to the the inner most category.
	 * @test
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

		$productCategoryColl = Mage::getResourceModel('catalog/category_collection');;
		$productCategoryColl->addItem($categoryObjects[2]);
		$product = $this->getModelMock('catalog/product', array('getCategoryCollection'));
		$product->expects($this->once())
			->method('getCategoryCollection')
			->will($this->returnValue($productCategoryColl));

		$params = array('item' => $product);

		$result = implode(' > ', $categories);

		$mapProduct = $this->getHelperMock('eems_affiliate/map_product', array('_getCategoriesByIds'));
		$mapProduct->expects($this->once())
			->method('_getCategoriesByIds')
			->with($this->identicalTo($categoryIds))
			->will($this->returnValue($collection));

		$this->assertSame($result, $mapProduct->getCategory($params));
	}
	/**
	 * Test that EbayEnterprise_Affiliate_Helper_Map_Product::getImageUrl will
	 * return an absolute URL to an image view.
	 * @test
	 */
	public function testGetImageUrl()
	{
		$key = 'image';
		$image = '/k/o/some-product-image.jpg';
		$mediaUrl = 'http://www.example.com/' .
			EbayEnterprise_Affiliate_Helper_Map_Product::MEDIA_PATH . $image;

		$product = $this->getModelMockBuilder('catalog/product')
			->disableOriginalConstructor()
			->setMethods(array('getDataUsingMethod'))
			->getMock();
		$product->expects($this->once())
			->method('getDataUsingMethod')
			->with($this->identicalTo($key))
			->will($this->returnValue($image));

		$params = array('item' => $product, 'key' => $key);

		$helperProduct = $this->getHelperMock('eems_affiliate/map_product', array(
			'_getAbsoluteImagePath'
		));

		$helperProduct->expects($this->once())
			->method('_getAbsoluteImagePath')
			->with($this->identicalTo($image))
			->will($this->returnValue($mediaUrl));

		$this->assertSame($mediaUrl, $helperProduct->getImageUrl($params));
	}
	/**
	 * Test that EbayEnterprise_Affiliate_Helper_Map_Product::getInStockQty will
	 * return an integer value representing the manage stock qty of a product.
	 * @test
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

		$this->assertSame($qty, Mage::helper('eems_affiliate/map_product')->getInStockQty($params));
	}
}
