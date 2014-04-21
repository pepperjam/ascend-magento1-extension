<?php

class EbayEnterprise_Affiliate_Model_System_Config_Source_Stockquantity
{
	/**
	 * Get quantity in stock list
	 * @return array
	 */
	public function toOptionArray()
	{
		$helper = Mage::helper('eems_affiliate');
		return array(
			array('value' => '', 'label' => ''),
			array('value' => 'qty', 'label' => $helper->__('Qty'))
		);
	}
}
