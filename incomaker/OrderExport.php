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
use WC_Product;

class OrderExport extends XmlExport {

	public static $name = "order";

	public function __construct() {
		$this->xml = new SimpleXMLElement('<orders/>');
		$this->setLimitKey('limit');
	}

	public function getItemsCount() {
		return null;
	}

	public function getFilteredItems() {
		return wc_get_orders($this->getQuery());
	}

	private function isValidOrderClass($order) {
		if (empty($order)) return false;
		$class_name = get_class($order);
		if (strpos($class_name, "OrderRefund") != false) return false;
		if (strpos($class_name, "WC_Order_Refund") != false) return false;
		return method_exists($order, 'get_order_number');
	}

	protected function createXml($order) {
        $childXml = $this->xml->addChild('o');

        // leave only an empty order tag for invalid orders to keep number of items per page
        // worker will silently ignore these while paging will still work
		if (!$this->isValidOrderClass($order)) {
            $childXml->addAttribute("id", "refund/invalid");
            return;
        };

		$childXml->addAttribute("id", $order->get_order_number());
		if (($order->get_customer_id() == 0) || (is_multisite())) {
			$contact = $childXml->addChild('contact');
			$this->addItem($contact, 'firstName', htmlspecialchars($order->get_billing_first_name()));
			$this->addItem($contact, 'lastName', htmlspecialchars($order->get_billing_last_name()));
			$this->addItem($contact, 'email', $order->get_billing_email());
			$this->addItem($contact, 'street', htmlspecialchars($order->get_billing_address_1()));
			$this->addItem($contact, 'city', htmlspecialchars($order->get_billing_city()));
			$this->addItem($contact, 'zipCode', $order->get_billing_postcode());
			$this->addItem($contact, 'phoneNumber1', $order->get_billing_phone());
			$this->addItem($contact, 'country', strtolower($order->get_billing_country()));
			$userLocale = $this->getUserLocale($order->get_customer_id());
			if (!empty($userLocale)) {
				$this->addItem($contact, 'language', $this->shortLang($userLocale));
			}
		} else {
			$this->addItem($childXml, 'contactId', $order->get_customer_id());
		}

		$this->addItem($childXml, 'created', $order->get_date_created());
		$this->addItem($childXml, 'state', $order->get_status());
		$items = $childXml->addChild('items');
		if ($order->get_items() != null) {
			foreach ($order->get_items() as $itm) {
				$item = $items->addChild('i');
				$item->addAttribute("id", $itm->get_product_id() * XmlExport::PRODUCT_ATTRIBUTE + $itm->get_variation_id());
				$this->addItem($item, "quantity", $itm->get_quantity());
				$product = $itm->get_product();
				if ($product instanceof WC_Product) {
					$price = $this->addItem($item, "price", wc_get_price_including_tax($product));
					$price->addAttribute("currency", $order->get_currency());
					$priceWithoutTax = $this->addItem($item, "priceWithoutTax", wc_get_price_excluding_tax($product));
					$priceWithoutTax->addAttribute("currency", $order->get_currency());
				} else {
					$price = $this->addItem($item, "price", 0);
					$price->addAttribute("currency", get_woocommerce_currency());
				}
			}
		}

		do_action('incomaker_modify_xml_order_item', $this, $childXml, $order);
	}
}
