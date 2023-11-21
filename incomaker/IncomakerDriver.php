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

use \Incomaker\Api\Connector;

class IncomakerDriver implements \Incomaker\Api\DriverInterface
{
	private $value;

	public function __construct($apiKey = null)
	{
		$opts = get_option("incomaker_option");

		$this->value[Connector::INCOMAKER_API_KEY] = isset($apiKey) ? $apiKey : (isset($opts["incomaker_api_key"]) ? $opts["incomaker_api_key"] : "");
		$this->value[Connector::INCOMAKER_ACCOUNT_ID] = isset($opts["incomaker_account_id"]) ? $opts["incomaker_account_id"] : "";
		$this->value[Connector::INCOMAKER_PLUGIN_ID] = isset($opts["incomaker_plugin_id"]) ? $opts["incomaker_plugin_id"] : "";
	}

	public function getSetting($key)
	{
		return $this->value[$key];
	}

	public function updateSetting($key, $value)
	{
		$this->value[$key] = $value;
	}

}
