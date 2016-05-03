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

class Pepperjam_Network_Model_Product_Attribute_Source_CommissioningCategory extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
	public function getAllOptions()
	{
		$rootCategoryId = Mage::app()->getStore()->getRootCategoryId();
		$rootCategories = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('name')->addFieldToFilter('parent_id', $rootCategoryId);

		$categoryOptions = array(array('value' => '', 'label' => ''));

		$this->_addChildren($categoryOptions, $rootCategories->getFirstItem()->getChildrenCategories(), 0);

		return $categoryOptions;
	}

	protected function _addChildren(&$options, $categories, $level)
	{
		if (!$categories->count())
			return;

		foreach($categories as $category) {
			$options[] = array('value' => $category->getId(), 'label' => str_repeat('-', $level) . $category->getName());

			$subcategories = $category->getChildrenCategories();
			$this->_addChildren($options, $subcategories, $level+1);
		}
	}
}
