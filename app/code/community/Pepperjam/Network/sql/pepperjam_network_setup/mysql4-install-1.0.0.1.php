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

/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

// Set the last run time of the corrected orders feed to the time when the
// extension is installed. This should help to reduce the number of untracked
// orders included in the initial run of the feed.
$installer->setConfigData('pepperjam/pepperjam_network/feed/last_run_time', time());
