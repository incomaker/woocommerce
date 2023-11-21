<?php
/*
 * Incomaker for Woocommerce
 * Copyright (C) 2021-2023 Incomaker s.r.o.
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

class Tracking implements Singletonable
{
	public function __construct()
	{
		add_action('wp_enqueue_scripts', array($this, 'incomaker_js_register'));
		add_filter('clean_url', array($this, 'add_async_forscript'));
	}

	public function incomaker_js_register()
	{
		$opts = get_option("incomaker_option");
		if (isset($opts["incomaker_account_id"]) && isset($opts["incomaker_plugin_id"])) {
			wp_enqueue_script('incomaker', 'https://dg.incomaker.com/tracking/resources/js/INlib.js?accountUuid=' . $opts["incomaker_account_id"] . '&pluginUuid=' . $opts["incomaker_plugin_id"] . '#asyncload');
		}
	}

	public function add_async_forscript($url)
	{
		if (strpos($url, '#asyncload') === false)
			return $url;
		else if (is_admin())
			return str_replace('#asyncload', '', $url);
		else
			return str_replace('#asyncload', '', $url) . "' async='async";
	}

	private static $singleton = null;

	public static function getInstance()
	{
		if (self::$singleton == null) {
			self::$singleton = new Tracking();
		}
		return self::$singleton;
	}
}
