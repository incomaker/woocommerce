<?php
/*
Plugin Name: Incomaker
Plugin URI: https://www.incomaker.com/woocommerce
Description: Marketing automation with artificial intelligence
Version: 0.8.7
Author: Incomaker
Author URI: https://www.incomaker.com
License: GPL v3
*/

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


use Incomaker\Options;
use Incomaker\Tracking;
use Incomaker\Events;

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('MIN_PHP_VERSION')) {
    define('MIN_PHP_VERSION', '5.6.0');
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
        _e(
            sprintf(
                '
      <div class="error notice">
        <p>
          The minimum required PHP version is %s. Your current version is %s.
          Please, update your PHP (or contact you hosting to do so).
        </p>
      </div>
      ', MIN_PHP_VERSION, PHP_VERSION
            )
        );
    }

    public function woocommerce_not_active()
    {
        ?>
        <div class="error notice">
            <p><?php
                _e('This plugin requires WooCommerce. Please install and activate it first.', 'incomaker'); ?></p>
        </div>
        <?php
    }

    public function execute()
    {

        if (version_compare(PHP_VERSION, MIN_PHP_VERSION) < 0) {
            add_action('admin_notices', array($this, 'php_upgrade_notice'));
        } elseif (!in_array('woocommerce/woocommerce.php', (array)get_option('active_plugins', array()), true)) {
            add_action('admin_notices', array($this, 'woocommerce_not_active'));
        } else {
            load_plugin_textdomain('incomaker', false, dirname(plugin_basename(__FILE__)) . '/languages');
            include_once __DIR__ . '/vendor/autoload.php';

            Options::getInstance();
            Tracking::getInstance();
            Events::getInstance();
            register_activation_hook(__FILE__, 'incomaker_activate');
            register_deactivation_hook(__FILE__, 'incomaker_deactivate');
        }
    }

}

(new Incomaker())->execute();