<?php

class EbayEnterprise_Affiliate_Model_Product_Attribute_Source_CommissioningCategory extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
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
