<?php

class EbayEnterprise_Affiliate_Helper_Map_Product
{
	const MEDIA_PATH = 'catalog/product';
	const NO_SELECTION = 'no_selection';
	const CATEGORY_FORMAT = '%.256s';
	/**
	 * Get a product first chained categories meaning that a product can be long to many chained of categories.
	 * Chained of categories here mean that a category root to it's inner most leaf child.
	 * @param  array $params
	 * @return string
	 */
	public function getCategory($params)
	{
		$categories = $params['item']->getCategoryIds();
		return !empty($categories)?
			sprintf(static::CATEGORY_FORMAT, $this->_buildCategoryTree($categories[count($categories) - 1])) : null;
	}
	/**
	 * given a category id build a category tree from the child leaf to the root
	 * of the category (root > inner child > inner most child)
	 * @param int $categoryId the inner most child
	 * @return string
	 */
	protected function _buildCategoryTree($categoryId)
	{
		$category = Mage::getModel('catalog/category');
		$path = $category->load($categoryId)->getPath();
		return implode(' > ', array_filter(array_map(
			function($id) use ($category) {
				return $category->load($id)->getName();
			},
			explode('/', $path)
		)));
	}
	/**
	 * get a product image view URL
	 * @param  array $params
	 * @return string
	 */
	public function getImageUrl($params)
	{
		$item = $params['item'];
		$image = trim($item->getDataUsingMethod($params['key']));
		$format = isset($params['format']) ? $params['format'] : '%s';
		return ($image !== '' && $image !== static::NO_SELECTION)?
			sprintf($format, $this->_getAbsoluteImagePath($image)) : null;
	}
	/**
	 * get the absolute URL product media path
	 * @param string $image the relative image
	 * @return string
	 */
	protected function _getAbsoluteImagePath($image)
	{
		return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) .
			static::MEDIA_PATH . $image;
	}
	/**
	 * get a product manage stock quantity
	 * @param  array $params
	 * @return string
	 */
	public function getInStockQty($params)
	{
		return (int) Mage::getModel('cataloginventory/stock_item')
			->loadByProduct($params['item'])
			->getQty();
	}
}
