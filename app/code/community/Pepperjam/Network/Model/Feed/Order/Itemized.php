<?php
/**
 * Copyright (c) 2016 Pepperjam Network.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Pepperjam Network
 * Magento Extensions End User License Agreement
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://assets.pepperjam.com/legal/magento-connect-extension-eula.pdf
 *
 * @copyright   Copyright (c) 2016 Pepperjam Network. (http://www.pepperjam.com/)
 * @license     http://assets.pepperjam.com/legal/magento-connect-extension-eula.pdf  Pepperjam Network Magento Extensions End User License Agreement
 */

class Pepperjam_Network_Model_Feed_Order_Itemized extends Pepperjam_Network_Model_Feed_Order_Abstract
{
	/**
	 * @see parent::_getItems
	 */
	protected function _getItems()
	{
		return $this->_getItemizedItems();
	}
	/**
	 * @see parent::_getFeedFields
	 */
	protected function _getFeedFields()
	{
		if ($this->_feedType == self::ITEMS_NEW) {
			$fields = Mage::helper('pepperjam_network/config')->getItemizedOrderFeedFields();
		} else {
			$fields = Mage::helper('pepperjam_network/config')->getItemizedOrderCorrectionFeedFields();
		}

		return explode(',', $fields);
	}
}
