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

$installer = $this;
$installer->startSetup();

$keys = array(
	'/active',
	'/program_id',
	'/order_type',
	'/transaction_type',
	'/export_path',
	'/conditional_pixel_enabled',
	'/source_key_name',
	'/order_correction_feed_enabled',
	'/product_feed_enabled',
	'_product_attribute_map/age_range',
	'_product_attribute_map/artist',
	'_product_attribute_map/aspect_ratio',
	'_product_attribute_map/author',
	'_product_attribute_map/battery_life',
	'_product_attribute_map/binding',
	'_product_attribute_map/buy_url',
	'_product_attribute_map/category_network',
	'_product_attribute_map/category_program',
	'_product_attribute_map/color',
	'_product_attribute_map/color_output',
	'_product_attribute_map/condition',
	'_product_attribute_map/description_long',
	'_product_attribute_map/description_short',
	'_product_attribute_map/director',
	'_product_attribute_map/discontinued',
	'_product_attribute_map/display_type',
	'_product_attribute_map/edition',
	'_product_attribute_map/expiration_date',
	'_product_attribute_map/features',
	'_product_attribute_map/focus_type',
	'_product_attribute_map/format',
	'_product_attribute_map/functions',
	'_product_attribute_map/genre',
	'_product_attribute_map/heel_height',
	'_product_attribute_map/height',
	'_product_attribute_map/image_thumb_url',
	'_product_attribute_map/image_url',
	'_product_attribute_map/installation',
	'_product_attribute_map/in_stock',
	'_product_attribute_map/isbn',
	'_product_attribute_map/keywords',
	'_product_attribute_map/length',
	'_product_attribute_map/load_type',
	'_product_attribute_map/location',
	'_product_attribute_map/made_in',
	'_product_attribute_map/manufacturer',
	'_product_attribute_map/material',
	'_product_attribute_map/megapixels',
	'_product_attribute_map/memory_capacity',
	'_product_attribute_map/memory_card_slot',
	'_product_attribute_map/memory_type',
	'_product_attribute_map/model_number',
	'_product_attribute_map/mpn',
	'_product_attribute_map/name',
	'_product_attribute_map/occasion',
	'_product_attribute_map/operating_system',
	'_product_attribute_map/optical_drive',
	'_product_attribute_map/pages',
	'_product_attribute_map/payment_accepted',
	'_product_attribute_map/payment_notes',
	'_product_attribute_map/platform',
	'_product_attribute_map/price',
	'_product_attribute_map/price_retail',
	'_product_attribute_map/price_sale',
	'_product_attribute_map/price_shipping',
	'_product_attribute_map/processor',
	'_product_attribute_map/publisher',
	'_product_attribute_map/quantity_in_stock',
	'_product_attribute_map/rating',
	'_product_attribute_map/recommended_usage',
	'_product_attribute_map/resolution',
	'_product_attribute_map/screen_size',
	'_product_attribute_map/shipping_method',
	'_product_attribute_map/shoe_size',
	'_product_attribute_map/shoe_width',
	'_product_attribute_map/size',
	'_product_attribute_map/sku',
	'_product_attribute_map/staring',
	'_product_attribute_map/style',
	'_product_attribute_map/tech_spec_url',
	'_product_attribute_map/tracks',
	'_product_attribute_map/upc',
	'_product_attribute_map/weight',
	'_product_attribute_map/width',
	'_product_attribute_map/wireless_interface',
	'_product_attribute_map/year',
	'_product_attribute_map/zoom',
);

foreach($keys AS $key) {
	if (!is_null(Mage::getStoreConfig('marketing_solutions/eems_affiliate' . $key))) {
		$installer->setConfigData('pepperjam/pepperjam_network' . $key, Mage::getStoreConfig('marketing_solutions/eems_affiliate' . $key));
	}
}

$installer->endSetup();
