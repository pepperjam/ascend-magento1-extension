<?php

class EbayEnterprise_Affiliate_Helper_Map
{
	/**
	 * Get the program id using the store passed in params. Pass thru to the
	 * config helper using the store included in the $params array as the store
	 * context to get the config value from.
	 * @param  array $params
	 * @return string
	 */
	public function getProgramId($params)
	{
		return Mage::helper('eems_affiliate/config')->getProgramId($params['store']);
	}
	/**
	 * Get data from the "item" object in the params at the "key" in the params
	 * array. Method is highly dependent upon the $params array meeting some base
	 * requirements: $params["item"] is a Varien_Object subclass and
	 * $params["key"] is set.
	 * @param  array $params
	 * @return mixed
	 */
	public function getDataValue($params)
	{
		if (!$params['item'] instanceof Varien_Object) {
			throw new Mage_Core_Exception(
				sprintf(
					'Item of type %s not compatible with %s',
					get_class($params['item']), __METHOD__
				)
			);
		}
		if (!isset($params['key'])) {
			throw new Mage_Core_Exception(
				'The data "key" must be provided in the configured params for this callback.'
			);
		}
		$helper = Mage::helper('core');
		return sprintf(
			isset($params['format']) ? $params['format'] : '%s',
			$helper->stripTag($params['item']->getDataUsingMethod($params['key']))
		);
	}
	/**
	 * Simply return the "value" included in the params.
	 * @param  array $params
	 * @return string
	 */
	public function passStatic($params)
	{
		if (!isset($params['value'])) {
			throw new Mage_Core_Exception(sprintf(
				'No value provided to return from %s', __METHOD__
			));
		}
		return $params['value'];
	}
}
