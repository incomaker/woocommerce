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

abstract class XmlExport
{

    const PRODUCT_ATTRIBUTE = 100000;
    const MAX_LIMIT = 1000;
    const API_VERSION = "2.10";

    public static $name;

    protected $itemsCount;

    protected $xml;
    protected $limit;
    protected $offset;
    protected $id;
    protected $since;

    public function setApiKey($apiKey)
    {
        if (!isset($apiKey)) {
            throw new UnexpectedValueException();
        }
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {

        if (($limit != NULL) && (!ctype_digit($limit))) {
            throw new InvalidArgumentException("Limit must be a number.");
        }
        $this->limit = $limit;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function setOffset($offset)
    {

        if (($offset != NULL) && (!ctype_digit($offset))) {
            throw new InvalidArgumentException("Offset must be a number.");
        }

        $this->offset = $offset;

        if (empty($this->limit)) {
            $this->limit = self::MAX_LIMIT;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        if (($id != NULL) && (!ctype_digit($id))) {
            throw new InvalidArgumentException("Offset must be a number.");
        }
        $this->id = $id;
    }

    public function getSince()
    {
        return $this->since;
    }

    public function setSince($since)
    {

        if (isset($since)) {
            if (!preg_match("/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/", $since)) {
                throw new InvalidArgumentException("Date must be in YYYY-MM-DD format");
            }
        }

        $this->since = $since;
    }

    protected function addItem($object, $id, $item)
    {
        if (isset($item)) {
            return $object->addChild($id, htmlspecialchars($item));
        }
    }

    protected abstract function getItemsCount();

    public abstract function getFilteredItems();

    public function createXmlFeed()
    {
        $this->xml->addAttribute('version', $this::API_VERSION);
        $this->xml->addAttribute('totalItems', $this->getItemsCount());

        foreach ($this->getFilteredItems() as $item) {
            $this->createXml($item);
        }
        return $this->xml->asXML();
    }

    protected $limitIdentifier = 'number';

    protected function setLimitKey($limitIdentifier)
    {
        $this->limitIdentifier = $limitIdentifier;
    }

    protected function shortLang($str) {
        return substr($str,0,2);
    }

    protected function getQuery()
    {

        if (!empty($this->getId())) {
            $query = array('include' => array($this->getId()));
        } else {
            $query = array('orderby' => 'date', 'order' => 'asc');
            if (!empty($this->getSince())) {
                $query['date_query'] = array('after' => $this->getSince(), 'inclusive' => true);
            }
            $query[$this->limitIdentifier] = $this->getLimit();
            $query['offset'] = $this->getOffset();
        }
        return $query;
    }
}
