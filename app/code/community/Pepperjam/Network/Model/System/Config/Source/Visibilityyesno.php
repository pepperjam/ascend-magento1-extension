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

class Pepperjam_Network_Model_System_Config_Source_Visibilityyesno
{
	const VISIBILITY_VALUE = 'visibility';
	const VISIBILITY_LABEL = 'Visibility';
	/**
	 * Get product visibility list
	 * @return array
	 */
	public function toOptionArray()
	{
		return array(
			array('value' => '', 'label' => ''),
			array(
				'value' => static::VISIBILITY_VALUE,
				'label' => Mage::helper('catalog')->__(static::VISIBILITY_LABEL)
			)
		);
	}
}
