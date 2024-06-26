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

use BadFunctionCallException;
use Exception;
use Incomaker;

class Feed
{
	protected $manager;

	protected $xmlExport;

	public function __construct()
	{
		$this->manager = new ExportManager();
	}

	const ROUTE = 'incomaker/v210';
	const COMMAND = '/feed';

	public function registerRoutes()
	{
		register_rest_route(Feed::ROUTE, Feed::COMMAND, array(
				array(
					'methods' => array('GET'),
					'callback' => array($this, 'execute'),
					'permission_callback' => '__return_true'
				)
			)
		);
	}

	function execute(\WP_REST_Request $request)
	{
		if (!Incomaker::woocommerce_plugin_active()) {
			return new \WP_Error("WOO_MISSING", "Incomaker plugin requires WooCommerce plugin to generate XML feeds!", array('status' => 500));
		}
		try {
			$xmlExport = $this->manager->getExport($request->get_param('type'));
			if ($xmlExport == NULL) throw new BadFunctionCallException();
		} catch (Exception $e) {
			return new \WP_Error("UNKNOWNTYPE", "Unknown feed type! Use URL query to specify feed type (product, contact, category, order or coupon).", array('status' => 400));
		}
		$apiKey = get_option("incomaker_option")['incomaker_api_key'];
		if (strlen($apiKey) === 0) {
			return new \WP_Error("APIKEYNOTSET", "API key not set! Incomaker API key must be configured on plugin Settings page.", array('status' => 403));
		}
		if ($apiKey !== $request->get_param('key')) {
			return new \WP_Error("APIKEYINVALID", "Invalid API key! Valid Incomaker API key must be configured on plugin Settings page.", array('status' => 401));
		}

		try {
			$xmlExport->setLimit($request->get_param('limit'));
			$xmlExport->setOffset($request->get_param('offset'));
			$xmlExport->setId($request->get_param('id'));
			$xmlExport->setSince($request->get_param('since'));

			$downloadCount = $request->get_param('downloadCount');
			if (!empty($downloadCount)) {
				$count = intval($downloadCount);
				if ($count > 0 && $xmlExport->supportsCreation()) {
					$xmlExport->createItems($count);
				}
			}
		} catch (InvalidArgumentException $e) {
			return new \WP_Error("ERROR", $e->getMessage(), array('status' => 400));
		}

		try {
			return $xmlExport->createXmlFeed();
		} catch (Exception $e) {
			return new \WP_Error("ERROR", $e->getMessage(), array('status' => 500));
		}
	}
}
