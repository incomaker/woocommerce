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

class ProductExport extends XmlExport
{

	public static $name = "product";

	private $locale;

	public function __construct() {
		$this->xml = new SimpleXMLElement('<products/>');
		$this->locale = get_locale();
		$this->setLimitKey('limit');
	}

	public function getLang() {
		return $this->shortLang($this->locale);
	}

	public function getFilteredItems() {
		return wc_get_products($this->getQuery());
	}

	private $thisProduct;

	protected function createXml($product) {
		$childXml = $this->xml->addChild('p');
		$childXml->addAttribute("id", $product->get_id() * XmlExport::PRODUCT_ATTRIBUTE);
		$imageUrl = wp_get_attachment_image_url($product->get_image_id(), 'full');
		if (!empty($imageUrl)) {
			$this->addItem($childXml, 'imageUrl', $imageUrl);
		}
		$categoriesXml = $childXml->addChild('categories');
		foreach ($product->get_category_ids() as $value) {
			$categoriesXml->addChild('c', $value);
		}
		$pricesXml = $childXml->addChild('prices');
		$pXml = $pricesXml->addChild('p');
		$pXml->addAttribute("currency", get_woocommerce_currency());

		$regular_price = wc_get_price_including_tax($product, ['price' => $product->get_regular_price()]);
		$sale_price = wc_get_price_including_tax($product, ['price' => $product->get_sale_price()]);
		$price = wc_get_price_including_tax($product);
		$this->addItem($pXml, "amount", $regular_price == "" ? $price : $regular_price);
		if ($regular_price !== $sale_price && $sale_price === $price) {
			$this->addItem($pXml, "priceAfterDiscount", $sale_price);
		}

		$tagsXml = $childXml->addChild('tags');
		foreach ($product->get_tag_ids() as $value) {
			$tagsXml->addChild('t', get_term($value)->name);
		}
		$tagsXml->addChild('t', $this->getLang());

		$this->addItem($childXml, 'stock', $product->get_stock_quantity());
		$this->addItem($childXml, 'choiceFlag', $product->get_featured() ? 1 : 0);
		$this->addItem($childXml, 'active', ($product->get_status() == "publish" ? 1 : 0));
		$this->addItem($childXml, 'updated', $product->get_date_modified());

		$languagesXml = $childXml->addChild('languages');
		$lXml = $languagesXml->addChild('l');
		$lXml->addAttribute("id", $this->getLang());
		$this->addItem($lXml, "name", self::removeXmlInvalidChars($product->get_name()));
		$this->addItem($lXml, "description", self::removeXmlInvalidChars($product->get_description()));
		$this->addItem($lXml, "shortDescription", self::removeXmlInvalidChars($product->get_short_description()));
		$this->addItem($lXml, 'url', get_permalink($product->get_id()));

		$attributes = $product->get_attributes();
		if (!empty($attributes)) {
			$attributesXml = $childXml->addChild('attributes');
			foreach ($attributes as $attribute) {
				$attribute_values = $attribute->is_taxonomy() ? wc_get_product_terms($product->get_id(), $attribute->get_name(), array('fields' => 'names')) : $attribute->get_options();
				foreach ($attribute_values as $value) {
					$aXml = $attributesXml->addChild('a', $value);
					$aXml->addAttribute("key", wc_attribute_label($attribute->get_name()));
					$tagsXml->addChild('t', $value); //use attributes as tags as well
				}
			}
		}

		$this->addItem($childXml, 'productId', $product->get_id());

		do_action('incomaker_modify_xml_product_item', $this, $childXml, $product);
	}

	protected function getItemsCount() {
		return null;
	}

}
