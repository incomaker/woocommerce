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

class OrderExport extends XmlExport
{

    public static $name = "order";

    public function __construct()
    {

        $this->xml = new SimpleXMLElement('<orders/>');
        $this->setLimitKey('limit');
    }

    public function getItemsCount()
    {
        $orders = get_object_vars(wp_count_posts( 'shop_order' ));
        return $orders["wc-pending"] + $orders["wc-processing"] + $orders["wc-on-hold"] + $orders["wc-completed"] + $orders["wc-cancelled"] + $orders["wc-refunded"] + $orders["wc-failed"];
    }

    public function getFilteredItems()
    {
        return wc_get_orders($this->getQuery());
    }

    protected function createXml($order)
    {
        if (strpos(get_class($order),"OrderRefund") != false) return;

        $childXml = $this->xml->addChild('o');
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

        $this->addItem($childXml, 'created', $order->get_date_created()->date("c"));
        $this->addItem($childXml, 'state', $order->get_status());
        $items = $childXml->addChild('items');
        if ($order->get_items() != null) {
            foreach ($order->get_items() as $itm) {
                $item = $items->addChild('i');
                $item->addAttribute("id", $itm->get_product_id() * XmlExport::PRODUCT_ATTRIBUTE + $itm->get_variation_id());
                $this->addItem($item, "variantId", $itm->get_product_id() * XmlExport::PRODUCT_ATTRIBUTE + $itm->get_variation_id());     //TODO Deprecated: will be removed in next version
                $this->addItem($item, "quantity", $itm->get_quantity());
                $product = $itm->get_product();
                if ($product instanceof WC_Product) {
                    $price = $this->addItem($item, "price", $product->get_price());
                    $price->addAttribute("currency", $order->get_currency());
                } else {
                    $price = $this->addItem($item, "price", 0);
                    $price->addAttribute("currency", get_woocommerce_currency());
                }
            }
        }

        do_action( 'incomaker_modify_xml_order_item', $this, $childXml, $order );
    }
}