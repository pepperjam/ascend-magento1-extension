<?php
/**
 * Copyright (c) 2019 Pepperjam Network.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Pepperjam Network
 * Magento Extensions End User License Agreement
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://assets.pepperjam.com/legal/magento-connect-extension-eula.pdf
 *
 * @copyright   Copyright (c) 2019 Pepperjam Network. (http://www.pepperjam.com/)
 * @license     http://assets.pepperjam.com/legal/magento-connect-extension-eula.pdf  Pepperjam Network Magento Extensions End User License Agreement
 *
 */

class Pepperjam_Network_Block_Tag extends Mage_Core_Block_Template
{
    /**
     * @return string
     */
    public function getIdentifier()
    {
        return Mage::helper('pepperjam_network/config')->getTagIdentifier();
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        $isEnabled = Mage::helper('pepperjam_network/config')->isTagEnabled();
        return $isEnabled && !empty($this->getIdentifier());
    }

    public function getNoJsEndpoint()
    {
        return Mage::helper('pepperjam_network/config')->getNoJsEndpoint();
    }

    public function getJsEndpoint()
    {
        return Mage::helper('pepperjam_network/config')->getJsEndpoint();
    }
}
