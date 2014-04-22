<?php

class EbayEnterprise_Affiliate_Model_System_Config_Source_Stockquantity
{
	const STOCK_QTY_VALUE = 'qty';
	const STOCK_QTY_LABEL = 'Quantity';
	/**
	 * Get quantity in stock list
	 * @return array
	 */
	public function toOptionArray()
	{
		return array(
			array('value' => '', 'label' => ''),
			array(
				'value' => static::STOCK_QTY_VALUE,
				'label' => Mage::helper('catalog')->__(static::STOCK_QTY_LABEL)
			)
		);
	}
}
