<?php
/*
 * Incomaker for Woocommerce
 * Copyright (C) 2021 Incomaker s.r.o.
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
use WC_Customer;
use WP_User;

class ContactExport extends XmlExport
{

    public static $name = "contact";

    public function __construct()
    {
        $this->xml = new SimpleXMLElement('<contacts/>');
        $this->setLimitKey('number');
    }

    protected function getItemsCount()
    {
        $users = count_users();
        return $users["total_users"];
    }

    public function getFilteredItems()
    {
        return get_users($this->getQuery());
    }

    protected function addIfNotEmpty($customer, $childXml, $key, $value)
    {
        if (!empty(trim($value))) $this->addItem($childXml, $key, $value);
    }

    protected function createXml(WP_User $customer)
    {

        $metaData = get_user_meta($customer->ID);
        $custom = new WC_Customer($customer->ID);

        if (isset($custom) && !empty($custom->get_billing_email())) {

            $childXml = $this->xml->addChild('c');
            $childXml->addAttribute("id", $customer->data->ID);
            $this->addItem($childXml, 'language', $this->shortLang(get_user_locale($customer->data->ID)));
            $this->addIfNotEmpty($custom, $childXml, 'firstName', htmlspecialchars($custom->get_billing_first_name()));
            $this->addIfNotEmpty($custom, $childXml, 'lastName', htmlspecialchars($custom->get_billing_last_name()));
            $this->addIfNotEmpty($custom, $childXml, 'email', htmlspecialchars($custom->get_billing_email()));
            $this->addIfNotEmpty($custom, $childXml, 'street', htmlspecialchars($custom->get_billing_address_1()));
            $this->addIfNotEmpty($custom, $childXml, 'city', htmlspecialchars($custom->get_billing_city()));
            $this->addIfNotEmpty($custom, $childXml, 'zipCode', htmlspecialchars($custom->get_billing_postcode()));
            $this->addIfNotEmpty($custom, $childXml, 'phoneNumber1', htmlspecialchars($custom->get_billing_phone()));
            $this->addIfNotEmpty($custom, $childXml, 'country', strtolower($custom->get_billing_country()));
            $this->addIfNotEmpty($custom, $childXml, 'companyName', htmlspecialchars($custom->get_billing_company()));
            $this->addItem($childXml, 'consentTitle', 'Woocommerce');
        }
    }
}