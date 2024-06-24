<?php

namespace Incomaker;

use SimpleXMLElement;

class CouponExport extends XmlExport
{

	public static $name = "coupon";

	public function __construct()
	{
		$this->xml = new SimpleXMLElement('<coupons/>');
		$this->setLimitKey('numberposts');
	}

	protected function getItemsCount() {
		return null;
	}

	public function getFilteredItems()
	{
		$query = $this->getQuery();
		$query['post_type'] = 'shop_coupon';
		return get_posts($query);
	}

	protected function createXml(\WP_Post $post) {
		$coupon = new \WC_Coupon($post->post_name);
		//var_dump($coupon);
		$childXml = $this->xml->addChild('c');
		$childXml->addAttribute("id", $coupon->get_id());
		$this->addItem($childXml, 'reusable', $coupon->get_usage_limit() > 1 ? 1 : 0);
		$this->addItem($childXml, 'discountType', $coupon->is_type('percent') ? 'PERCENTUAL' : 'MONETARY');
		$this->addItem($childXml, 'discount', $coupon->get_amount());
		$this->addItem($childXml, 'validTo', $coupon->get_date_expires());
		$values = $childXml->addChild('values');
		$this->addItem($values, 'v', $coupon->get_code());
	}

}
