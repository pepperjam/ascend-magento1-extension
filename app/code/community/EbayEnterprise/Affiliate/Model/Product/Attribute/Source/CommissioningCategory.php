<?php

class EbayEnterprise_Affiliate_Model_Product_Attribute_Source_CommissioningCategory extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
	public function getAllOptions()
	{
		$categories = Mage::helper('catalog/category')->getStoreCategories();

		$categoryOptions = array('' => '');
		$this->_subcategories($categoryOptions, $categories);

		natcasesort($categoryOptions);

		return $categoryOptions;
	}

	protected function _subcategories(&$options, $categories)
	{
		if (count($categories) == 0)
			return;

		foreach($categories as $category) {
			$options[$category->getId()] = $category->getName();

			$subcategories = $category->getChildrenCategories();
			$this->_subcategories($options, $subcategories);
		}
	}
}
