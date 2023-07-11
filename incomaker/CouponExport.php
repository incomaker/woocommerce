<?php
/*
 * Incomaker for Woocommerce
 * Copyright (C) 2021-2022 Incomaker s.r.o.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Incomaker;

use SimpleXMLElement;

class CouponExport extends XmlExport
{

	public static $name = "coupon";

	const COUPON_CODE_LENGTH = 10;

	public function __construct()
	{
		$this->xml = new SimpleXMLElement('<coupons/>');
		$this->setLimitKey('numberposts');
	}

	protected function getItemsCount()
	{
		$count = wp_count_posts('shop_coupon');
		return $count->publish;
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

	/**
	 * Return random number between 0 and 9.
	 */
	static function getRandomNumber() {
		return rand(0,9);
	}

	/**
	 * Return random lowercase character.
	 */
	static function getRandomLowercase() {
		return chr(rand(97,122));
	}

	/**
	 * Return random uppercase character.
	 */
	static function getRandomUppercase() {
		return strtoupper(self::getRandomLowercase());
	}

	/**
	 * Return string of random numbers, uppercase and lowercase characters.
	 */
	static function generateRandomToken($len) {
		$s = '';
		for ($i = 0; $i < $len; $i++) {
			$case = rand(0,2);
			if ($case == 0) {
				$s .= self::getRandomNumber();
			} elseif ($case == 1) {
				$s .= self::getRandomUppercase();
			} else {
				$s .= self::getRandomLowercase();
			}
		}
		return $s;
	}

	public function createCoupon() {
		$code = null;
		while ($code === null) {
			$code = self::generateRandomToken(self::COUPON_CODE_LENGTH);
			if (wc_get_coupon_id_by_code($code) > 0) {
				$code = null;
			}
		}
		$coupon = new \WC_Coupon($code);
		$coupon->set_amount(99);
		$coupon->save();
		return $coupon;
	}

	public function supportsCreation(): bool {
		return true;
	}

	public function createItems(int $count) {
		for ($i = 0; $i < $count; $i++) {
			$coupon = $this->createCoupon();
		}
	}
}
