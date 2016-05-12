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
 *
 */

class Pepperjam_Network_Block_Tracking extends Mage_Core_Block_Template
{
	/**
	 * @return string | null
	 */
	public function getSourceCookieName()
	{
		return Mage::helper('pepperjam_network')->getSourceCookieName();
	}

	/**
	 * @return string | null
	 */
	public function getSourceKeyName()
	{
		return Mage::helper('pepperjam_network/config')->getSourceKeyName();
	}

	/**
	 * @return string | null
	 */
	public function getClickCookieName()
	{
		return Mage::helper('pepperjam_network')->getClickCookieName();
	}

	/**
	 * @return string | null
	 */
	public function getClickKeyName()
	{
		return Mage::helper('pepperjam_network/config')->getClickKeyName();
	}

	/**
	 * @return string | null
	 */
	public function getPublisherCookieName()
	{
		return Mage::helper('pepperjam_network')->getPublisherCookieName();
	}

	/**
	 * @return string | null
	 */
	public function getPublisherKeyName()
	{
		return Mage::helper('pepperjam_network/config')->getPublisherKeyName();
	}

	/**
	 * Whether or not to inject the javascript to set the tracking cookie
	 *
	 * @return bool
	 */
	public function injectJavaScript()
	{
		return (Mage::helper('pepperjam_network/config')->isAttributionEnabled());
	}
}
