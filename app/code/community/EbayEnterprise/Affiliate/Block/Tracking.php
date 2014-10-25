<?php
/**
 * Copyright (c) 2013-2014 eBay Enterprise, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright   Copyright (c) 2013-2014 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


class EbayEnterprise_Affiliate_Block_Tracking extends Mage_Core_Block_Template
{
    public function getQueryStringKeyName()
    {
        $key = Mage::helper('eems_affiliate/config')->getSourceKeyName();
        Mage::log("key: $key");
        return "ebay_enterprise_affiliate_$key";
    }

    /**
     * Whether or not to inject the javascript to set the tracking cookie
     *
     * @return bool
     */
    public function injectJavaScript()
    {
        return (Mage::helper('eems_affiliate/config')->isEnabledConditionalPixel());
    }
} 