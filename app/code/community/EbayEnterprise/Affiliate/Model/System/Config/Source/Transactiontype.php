<?php

class EbayEnterprise_Affiliate_Model_System_Config_Source_Transactiontype
{
	/**
	 * Get available Transaction Type options
	 * @return array
	 */
	public function toOptionArray()
	{
		$helper = Mage::helper('eems_affiliate');
		return array('lead' => $helper->__('Lead'), 'sale' => $helper->__('Sale'));
	}
}
