<?php

class EbayEnterprise_Affiliate_Model_Observer
{
	public function createProductFeed()
	{
		Mage::log('Generating Product feed');
		Mage::getModel('eems_affiliate/feed_product')->generateFeed();
	}
}