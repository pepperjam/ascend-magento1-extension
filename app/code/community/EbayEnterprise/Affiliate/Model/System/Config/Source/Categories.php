<?php

class EbayEnterprise_Affiliate_Model_System_Config_Source_Categories
{
	const CATEGORY_VALUE = 'category';
	const CATEGORY_LABEL = 'Product Categories';
	/**
	 * Get category attributes
	 * @return array
	 */
	public function toOptionArray()
	{
		return array(
			array('value' => '', 'label' => ''),
			array(
				'value' => static::CATEGORY_VALUE,
				'label' => Mage::helper('catalog')->__(static::CATEGORY_LABEL)
			)
		);
	}
}
