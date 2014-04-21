<?php

class EbayEnterprise_Affiliate_Model_System_Config_Source_Attributes
{
	/**
	 * Get product attributes
	 * @return array
	 */
	public function toOptionArray()
	{
		$helper = Mage::helper('eems_affiliate');
		$collection = Mage::getSingleton('eav/config')
			->getEntityType(Mage_Catalog_Model_Product::ENTITY)->getAttributeCollection();
		$attributes = array(array('value' => '', 'label' => ''));
		foreach ($collection as $attribute) {
			$attributes[] = array(
				'value' => $attribute->getAttributeCode(),
				'label' => (trim($attribute->getFrontendLabel()) !== '')?
					$helper->__($attribute->getFrontendLabel()) :
					$attribute->getAttributeCode()
			);
		}
		return array_merge($attributes, array(
			array('value' => 'product_url', 'label' => 'Product url')
		));
	}
}
