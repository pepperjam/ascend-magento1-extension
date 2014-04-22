<?php

class EbayEnterprise_Affiliate_Model_System_Config_Source_Stockyesno
{
	const IN_STOCK_VALUE = 'in_stock';
	const IN_STOCK_LABEL = 'Inventory Stock Availability';
	/**
	 * Get in stock list
	 * @return array
	 */
	public function toOptionArray()
	{
		return array(
			array('value' => '', 'label' => ''),
			array(
				'value' => static::IN_STOCK_VALUE,
				'label' => Mage::helper('catalog')->__(static::IN_STOCK_LABEL)
			)
		);
	}
}
