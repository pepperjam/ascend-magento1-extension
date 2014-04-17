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
		return array('1' => $helper->__('Sale'), '2' => $helper->__('Lead'));
	}
}
