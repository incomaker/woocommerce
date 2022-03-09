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

class CategoryExport extends XmlExport
{

    public static $name = "category";

    private $locale;

    public function __construct()
    {
        $this->xml = new \SimpleXMLElement('<categories/>');
        $this->locale = get_locale();
        $this->setLimitKey('number');
    }

    protected function getItemsCount()
    {
        return count(get_terms(array('taxonomy' => 'product_cat')));
    }

    public function getFilteredItems()
    {
        return get_terms(array_merge($this->getQuery(), array('taxonomy' => 'product_cat')));
    }

    protected function createXml($category)
    {
        $childXml = $this->xml->addChild('c');
        $childXml->addAttribute("id", $category->term_id);
        $this->addItem($childXml, 'parentCategoryId', $category->parent);
        $languagesXml = $childXml->addChild('languages');
        $lXml = $languagesXml->addChild('l');
        $lXml->addAttribute("id", $this->shortLang($this->locale));
        $this->addItem($lXml, "name", $category->name);
        $this->addItem($lXml, "url", get_permalink(get_page_by_path($category->slug)));
    }
}