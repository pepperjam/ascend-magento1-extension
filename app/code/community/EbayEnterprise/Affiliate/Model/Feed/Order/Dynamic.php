<?php

class EbayEnterprise_Affiliate_Model_Feed_Order_Dynamic extends EbayEnterprise_Affiliate_Model_Feed_Order_Itemized
{
	protected function _getFeedFields()
	{
		return explode(',', Mage::helper('eems_affiliate/config')->getDynamicOrderFeedFields());
	}

	protected function _getFileNameFormat()
	{
		return Mage::helper('eems_affiliate/config')->getDynamicOrderFeedFileFormat();
	}
}
