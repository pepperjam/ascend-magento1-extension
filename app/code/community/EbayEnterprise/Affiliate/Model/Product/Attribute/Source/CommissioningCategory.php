<?php

class EbayEnterprise_Affiliate_Model_Product_Attribute_Source_CommissioningCategory extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
	public function getAllOptions()
	{
		$rootCategoryId = Mage::app()->getStore()->getRootCategoryId();
		$rootCategories = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('name')->addFieldToFilter('parent_id', $rootCategoryId);

		$categoryOptions = array(array('value' => '', 'label' => ''));

		$this->_subcategories($categoryOptions, $rootCategories, 0);

		return $categoryOptions;
	}

	protected function _subcategories(&$options, $categories, $level)
	{
		if (!$categories->count())
			return;

		foreach($categories as $category) {
			$options[] = array('value' => $category->getId(), 'label' => str_repeat('-', $level) . $category->getName());

			$subcategories = $category->getChildrenCategories();
			$this->_subcategories($options, $subcategories, $level+1);
		}
	}
}
