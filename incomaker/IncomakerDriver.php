<?php

namespace Incomaker;

use \Incomaker\Api\Connector;

class IncomakerDriver implements \Incomaker\Api\DriverInterface {
	private $value;

	public function __construct($apiKey = null) {
		$opts = get_option("incomaker_option");

		$this->value[Connector::INCOMAKER_API_KEY] = isset($apiKey) ? $apiKey : (isset($opts["incomaker_api_key"]) ? $opts["incomaker_api_key"] : "");
		$this->value[Connector::INCOMAKER_ACCOUNT_ID] = isset($opts["incomaker_account_id"]) ? $opts["incomaker_account_id"] : "";
		$this->value[Connector::INCOMAKER_PLUGIN_ID] = isset($opts["incomaker_plugin_id"]) ? $opts["incomaker_plugin_id"] : "";
	}

	public function getSetting($key) {
		return $this->value[$key];
	}

	public function updateSetting($key, $value) {
		$this->value[$key] = $value;
	}

	public function isModuleEnabled() {
		return true;
	}
}
