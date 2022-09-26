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

require_once __DIR__ . '/vendor/woocommerce/action-scheduler/action-scheduler.php';

class Events implements Singletonable
{
    private $incomakerApi;

    public function __construct()
    {
        $this->incomakerApi = new IncomakerApi();

        add_action('user_register', array($this, 'incomaker_user_register'));
        add_action('wp_login', array($this, 'incomaker_user_login'));
        add_action('wp_logout', array($this, 'incomaker_user_logout'));
        add_filter('profile_update', array($this, 'incomaker_profile_update'), 10, 2);
        add_action('woocommerce_add_to_cart', array($this, 'incomaker_add_to_cart'), 10, 6);
        add_filter('woocommerce_cart_item_removed', array($this, 'incomaker_add_to_cart'), 10, 6);
        add_action('woocommerce_checkout_order_processed', array($this, 'incomaker_order_add'), 10, 1);

        add_action( 'post_event', array($this, 'incomaker_async_post_event' ), 10, 2);
        add_action( 'register', array($this, 'incomaker_async_register' ), 10, 2);
        add_action( 'update', array($this, 'incomaker_async_update' ), 10, 2);
        add_action( 'post_product_event', array($this, 'incomaker_async_post_product_event' ), 10, 4);
    }

    public function incomaker_order_add($order_id) {

        $order = new \WC_Order( $order_id );

        as_enqueue_async_action( 'post_product_event', array('order_add', $order->get_user_id(), $order->get_order_item_totals(), $order->get_user_id()));

        $this->sessionStart();
        WC()->session->__unset('old_cart');
    }

    protected function sessionStart() {
        if ( isset(WC()->session) && ! WC()->session->has_session() ) {
            WC()->session->set_customer_session_cookie( true );
        }
    }

    public function incomaker_add_to_cart()
    {
        $this->sessionStart();
        $customer_id = get_current_user_id();
        $new = array();

        foreach (WC()->cart->get_cart() as $item => $values) {
            $_product = $values['data'];
            $new[] = $_product->get_id() * XmlExport::PRODUCT_ATTRIBUTE;
        }

        $old = unserialize(WC()->session->get( 'old_cart' ));

        if (empty($old)) $old = array();
        $diff = array_diff($new, $old);

        if (!empty($diff)) {
            as_enqueue_async_action( 'post_product_event', array('cart_add', $customer_id, current($diff), $customer_id));
        } else {
            $diff = array_diff($old, $new);
            if (!empty($diff)) {
                as_enqueue_async_action( 'post_product_event', array('cart_remove', $customer_id, current($diff), $customer_id));
            }
        }
        WC()->session->set( 'old_cart', serialize($new));
    }

    public function incomaker_profile_update($user_id, $customer) {
        as_enqueue_async_action( 'update', array($user_id, $customer));
    }

    public function incomaker_async_update($user_id, $customer) {

        $customer = new \WC_Customer( $user_id );
        $this->incomakerApi->updateContact($user_id, $customer);
        $this->incomakerApi->postEvent("contact_update", $user_id);
    }

    public function incomaker_user_register($user_id)
    {
        as_enqueue_async_action( 'register', array($user_id, $_POST['email']));
    }

    public function incomaker_async_register($user_id, $email) {

        $this->incomakerApi->addContact($user_id, $email);
        $this->incomakerApi->postEvent("register", $user_id);
    }

    public function incomaker_user_login($user)
    {
        as_enqueue_async_action( 'post_event', array("login", get_userdatabylogin($user)->ID));
    }

    public function incomaker_async_post_event($event, $param) {

        $this->incomakerApi->postEvent($event, $param);
    }

    public function incomaker_async_post_product_event($event, $customer_id, $product, $campaign_id) {

        $this->incomakerApi->postProductEvent($event, $customer_id, $product, $campaign_id);
    }

    public function incomaker_user_logout($user_id)
    {
        as_enqueue_async_action( 'post_event', array("logout", $user_id ));
    }

    private static $singleton = null;

    public static function getInstance()
    {
        if (self::$singleton == null) {
            self::$singleton = new Events();
        }
        return self::$singleton;
    }
}