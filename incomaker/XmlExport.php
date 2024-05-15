<?php

namespace Incomaker;

use http\Exception\BadMethodCallException;
use Incomaker\Api\Helper\JsonHelper;

abstract class XmlExport {

	const PRODUCT_ATTRIBUTE = 100000;

	const MAX_LIMIT = 1000;

	const API_VERSION = "2.18";

	public static $name;

	protected $itemsCount;

	protected $xml;

	protected $limit;

	protected $offset;

	protected $id;

	protected $since;

	public function getPluginVersion() {
		return JsonHelper::getValue(__DIR__ . '/composer.json', 'version');
	}

	/**
	 * Strips characters that are invalid for encoding into XML entities.
	 * @param $text
	 * @return string
	 */
	public static function removeXmlInvalidChars($text) {
		return preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $text);
	}

	public function setApiKey($apiKey) {
		if (!isset($apiKey)) {
			throw new UnexpectedValueException();
		}
	}

	public function getLimit() {
		return $this->limit;
	}

	public function setLimit($limit) {
		if (($limit != NULL) && (!ctype_digit($limit))) {
			throw new InvalidArgumentException("Limit must be a number.");
		}
		$this->limit = $limit;
	}

	public function getOffset() {
		return $this->offset;
	}

	public function setOffset($offset) {

		if (($offset != NULL) && (!ctype_digit($offset))) {
			throw new InvalidArgumentException("Offset must be a number.");
		}

		$this->offset = $offset;

		if (empty($this->limit)) {
			$this->limit = self::MAX_LIMIT;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		if (($id != NULL) && (!ctype_digit($id))) {
			throw new InvalidArgumentException("Offset must be a number.");
		}
		$this->id = $id;
	}

	public function getSince() {
		return $this->since;
	}

	public function setSince($since) {

		if (isset($since)) {
			if (!preg_match("/^((((19|[2-9]\d)\d{2})\-(0[13578]|1[02])\-(0[1-9]|[12]\d|3[01]))|(((19|[2-9]\d)\d{2})\-(0[13456789]|1[012])\-(0[1-9]|[12]\d|30))|(((19|[2-9]\d)\d{2})\-02\-(0[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))\-02\-29))$/", $since)) {
				throw new InvalidArgumentException("Date must be in YYYY-MM-DD format");
			}
		}

		$this->since = $since;
	}

	protected function addItem($object, $id, $item) {
		if (isset($item)) {
			return $object->addChild($id, htmlspecialchars($item));
		}
	}

	protected abstract function getItemsCount();

	public abstract function getFilteredItems();

	public function createXmlFeed() {
		$this->xml->addAttribute('version', $this::API_VERSION);
		$this->xml->addAttribute('totalItems', $this->getItemsCount());
		$this->xml->addAttribute('generator', 'IncomakerWoocommercePlugin:' . $this->getPluginVersion());

		foreach ($this->getFilteredItems() as $item) {
			$this->createXml($item);
		}

		return apply_filters("incomaker_output_xml_feed_filter", $this->xml->asXML(), $this::$name);
	}

	protected $limitIdentifier = 'number';

	protected function setLimitKey($limitIdentifier) {
		$this->limitIdentifier = $limitIdentifier;
	}

	protected function shortLang($str) {
		return substr($str, 0, 2);
	}

	public function getUserLocale($user) {
		$locale = null;
		$user_object = get_user_by('id', $user);
		if (!empty($user_object)) {
			$locale = $user_object->locale;
		}
		return $locale;
	}

	protected function getQuery() {
		if (!empty($this->getId())) {
			$query = array('include' => array($this->getId()));
		} else {
			$query = array('orderby' => 'date', 'order' => 'asc');
			if (!empty($this->getSince())) {
				$today = date("Y-m-j");
				$query['date_modified'] = sprintf('%s...%s', $this->getSince(), $today);
			}
			$query[$this->limitIdentifier] = $this->getLimit();
			$query['offset'] = $this->getOffset();
		}
		return $query;
	}

	public function supportsCreation(): bool {
		return false;
	}

	public function createItems(int $count) {
		if (!$this->supportsCreation()) {
			throw new BadMethodCallException("This export does not support item creation!");
		}
	}
}
