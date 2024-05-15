<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

/*
Plugin Name: Incomaker
Plugin URI: https://www.incomaker.com/woocommerce
Description: Marketing automation with artificial intelligence
Version: 2.1.8
Author: Incomaker
Author URI: https://www.incomaker.com
License: GPL v3
*/

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

use Incomaker\Options;
use Incomaker\Tracking;
use Incomaker\Events;

require_once __DIR__ . '/vendor/woocommerce/action-scheduler/action-scheduler.php';

if (!defined('INCOMAKER_MIN_PHP_VERSION')) {
	define('INCOMAKER_MIN_PHP_VERSION', '5.6.0');
}

function incomaker_activate($w)
{
	register_uninstall_hook(__FILE__, 'incomaker_uninstall');
}

function incomaker_deactivate($w)
{
}

function incomaker_uninstall()
{
}

class Incomaker
{

	public function php_upgrade_notice()
	{
		$info = get_plugin_data(__FILE__);

		?>
			<div class="error notice">
				<p>
					<?php
						printf(
							esc_html__('The minimum required PHP version is %s. Your current version is %s.', 'incomaker' ),
							esc_html(INCOMAKER_MIN_PHP_VERSION),
							esc_html(PHP_VERSION)
						);
					?>
				</p>
				<p>
					<?php esc_html_e('Please, update your PHP (or contact you hosting to do so).', 'incomaker'); ?>
				</p>
			</div>
		<?php
	}

	public function woocommerce_not_active()
	{
		?>
		<div class="error notice">
			<p><?php esc_html_e('Incomaker plugin requires WooCommerce. Please install and activate WooCommerce plugin first.', 'incomaker'); ?></p>
		</div>
		<?php
	}

	public function register_rest_controller()
	{
		$controller = new Incomaker\Feed();
		$controller->registerRoutes();
	}

	function feed_handler($served, $result, $request, $server)
	{
		if ('/' . \Incomaker\Feed::ROUTE . \Incomaker\Feed::COMMAND !== $request->get_route() ||
			'execute' !== $request->get_attributes()['callback'][1])
			return $served;

		if ($result->is_error()) {
			echo esc_html($result->as_error()->get_error_message());
		} else {
			$server->send_header('Content-Type', 'application/xml; Charset=UTF-8');
			echo $result->get_data();
		}
		return true;
	}

	function woocommerce_plugin_active() {
		return in_array('woocommerce/woocommerce.php', (array)get_option('active_plugins', array()), true);
	}

	public function execute()
	{
		if (!function_exists('is_plugin_active_for_network')) {
			require_once(ABSPATH . '/wp-admin/includes/plugin.php');
		}
		if (version_compare(PHP_VERSION, INCOMAKER_MIN_PHP_VERSION) < 0) {
			add_action('admin_notices', array($this, 'php_upgrade_notice'));
		} elseif (!$this->woocommerce_plugin_active()) {
			add_action('admin_notices', array($this, 'woocommerce_not_active'));
		} else {
			load_plugin_textdomain('incomaker', false, dirname(plugin_basename(__FILE__)) . '/languages');
			include_once __DIR__ . '/vendor/autoload.php';

			Options::getInstance();
			Tracking::getInstance();
			Events::getInstance();
			register_activation_hook(__FILE__, 'incomaker_activate');
			register_deactivation_hook(__FILE__, 'incomaker_deactivate');
			add_action('rest_api_init', array($this, 'register_rest_controller'));
			add_filter('rest_pre_serve_request', array($this, 'feed_handler'), 10, 4);
		}
	}
}

(new Incomaker())->execute();
