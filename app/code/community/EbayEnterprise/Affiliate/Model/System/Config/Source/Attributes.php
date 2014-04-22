<?php

class EbayEnterprise_Affiliate_Model_System_Config_Source_Attributes
{
	const PRODUCT_URL_VALUE = 'product_url';
	const PRODUCT_URL_LABEL = 'Product url';
	/**
	 * Get product attributes
	 * @return array
	 */
	public function toOptionArray()
	{
		$helper = Mage::helper('catalog');
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
		$attributes[] = array(
			'value' => static::PRODUCT_URL_VALUE,
			'label' => $helper->__(static::PRODUCT_URL_LABEL)
		);
		return $attributes;
	}
}
