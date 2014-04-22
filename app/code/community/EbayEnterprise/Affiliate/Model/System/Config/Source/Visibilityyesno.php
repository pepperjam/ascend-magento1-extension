<?php

class EbayEnterprise_Affiliate_Model_System_Config_Source_Visibilityyesno
{
	const VISIBILITY_VALUE = 'visibility';
	const VISIBILITY_LABEL = 'Visibility';
	/**
	 * Get product visibility list
	 * @return array
	 */
	public function toOptionArray()
	{
		return array(
			array('value' => '', 'label' => ''),
			array(
				'value' => static::VISIBILITY_VALUE,
				'label' => Mage::helper('catalog')->__(static::VISIBILITY_LABEL)
			)
		);
	}
}
