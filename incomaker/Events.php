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

require_once __DIR__ . '/vendor/woocommerce/action-scheduler/action-scheduler.php';

const EVENT_GROUP_NAME = 'incomaker-sync-events';

class Events implements Singletonable
{
	private $incomakerApi;

	public function __construct()
	{
		$this->incomakerApi = new IncomakerApi();

		add_action('user_register', array($this, 'incomaker_user_register'), 1, 1);
		add_filter('profile_update', array($this, 'incomaker_profile_update'), 2, 2);
		add_action('wp_login', array($this, 'incomaker_user_login'));
		add_action('wp_logout', array($this, 'incomaker_user_logout'));
		add_action('woocommerce_add_to_cart', array($this, 'incomaker_add_to_cart'), 5, 6);
		add_filter('woocommerce_cart_item_removed', array($this, 'incomaker_add_to_cart'), 6, 6);
		add_action('woocommerce_checkout_order_processed', array($this, 'incomaker_order_add'), 10, 1);

		add_action('register', array($this, 'incomaker_async_register'), 1, 2);
		add_action('post_event', array($this, 'incomaker_async_post_event'), 2, 3);
		add_action('update', array($this, 'incomaker_async_update'), 3, 3);
		add_action('post_product_event', array($this, 'incomaker_async_post_product_event'), 10, 5);
		add_action('post_order_event', array($this, 'incomaker_async_post_order_event'), 10, 6);
	}

	private function getSanitizedPost($key) {
		if (!isset($_POST[$key])) return null;
		return sanitize_text_field(strval($_POST[$key]));
	}

	public function incomaker_order_add($order_id)
	{

		$order = new \WC_Order($order_id);
		if ($order->get_user_id() == 0) {
			$this->incomaker_user_register(null);
		}
		as_enqueue_async_action(
			'post_order_event',
			array(
				'order_add',
				$order->get_user_id() != 0 ? $order->get_user_id() : null,
				$order->get_order_number(),
				$this->incomakerApi->getSessionId(),
				$this->incomakerApi->getPermId(),
				$order->get_billing_email()
			),
			EVENT_GROUP_NAME,
			false,
			6
		);

		$this->sessionStart();
		WC()->session->__unset('old_cart');
	}

	protected function sessionStart()
	{
		if (isset(WC()->session) && !WC()->session->has_session()) {
			WC()->session->set_customer_session_cookie(true);
		}
	}

	public function incomaker_add_to_cart()
	{
		$this->sessionStart();
		$customer_id = get_current_user_id();
		if ($customer_id == 0) {
			$customer_id = null;
		}
		$new = array();

		foreach (WC()->cart->get_cart() as $item => $values) {
			$_product = $values['data'];
			$new[] = $_product->get_id() * XmlExport::PRODUCT_ATTRIBUTE;
		}

		$old = unserialize(WC()->session->get('old_cart'));
		if (empty($old)) $old = array();
		$diff = array_diff($new, $old);

		if (!empty($diff)) {
			as_enqueue_async_action(
				'post_product_event',
				array('cart_add', $customer_id, current($diff), $this->incomakerApi->getSessionId(), $this->incomakerApi->getPermId()),
				EVENT_GROUP_NAME,
				false,
				4
			);
		} else {
			$diff = array_diff($old, $new);
			if (!empty($diff)) {
				as_enqueue_async_action(
					'post_product_event',
					array('cart_remove', $customer_id, current($diff), $this->incomakerApi->getSessionId(), $this->incomakerApi->getPermId()),
					EVENT_GROUP_NAME,
					false,
					5
				);
			}
		}
		WC()->session->set('old_cart', serialize($new));
	}

	public function incomaker_profile_update($user_id, $customer)
	{
		as_enqueue_async_action(
			'update',
			array($user_id, $customer, $this->incomakerApi->getPermId()),
			EVENT_GROUP_NAME,
			false,
			8
		);
	}

	public function incomaker_async_update($user_id, $customer, $permId)
	{
		$customer = new \WC_Customer($user_id);
		$this->incomakerApi->updateContact($user_id, $customer, $permId);
		$this->incomakerApi->postEvent("contact_update", $user_id, $permId);
	}

	public function incomaker_user_register($user_id)
	{
		$contact = new \Incomaker\Api\Data\Contact($user_id);
		$contact->setEmail(sanitize_email($this->getSanitizedPost('billing_email')));
		$contact->setFirstName($this->getSanitizedPost('billing_first_name'));
		$contact->setLastName($this->getSanitizedPost('billing_last_name'));
		$contact->setCompanyName($this->getSanitizedPost('billing_company'));
		$contact->setCountry($this->getSanitizedPost('billing_country'));
		$contact->setStreet($this->getSanitizedPost('billing_address_1'));
		$contact->setStreet2($this->getSanitizedPost('billing_address_2'));
		$contact->setCity($this->getSanitizedPost('billing_city'));
		$contact->setZipCode($this->getSanitizedPost('billing_postcode'));
		$contact->setPhoneNumber1($this->getSanitizedPost('billing_phone'));

		as_enqueue_async_action(
			'register',
			array(serialize($contact), $this->incomakerApi->getPermId()),
			EVENT_GROUP_NAME,
			false,
			1
		);
	}

	public function incomaker_async_register($contact, $permId)
	{
		$contact = unserialize($contact);

		$this->incomakerApi->addContact($contact, $permId);
		$this->incomakerApi->postEvent("register", $contact->getClientContactId(), $permId, $contact->getEmail());
	}

	public function incomaker_user_login($user)
	{
		as_enqueue_async_action(
			'post_event',
			array("login", get_userdatabylogin($user)->ID, $this->incomakerApi->getPermId()),
			EVENT_GROUP_NAME,
			false,
			2
		);
	}

	public function incomaker_async_post_event($event, $param, $permId)
	{
		$this->incomakerApi->postEvent($event, $param, $permId);
	}

	public function incomaker_async_post_product_event($event, $customer_id, $product, $sessionId, $permId)
	{
		$this->incomakerApi->postProductEvent($event, $customer_id, $product, $sessionId, $permId);
	}

	public function incomaker_async_post_order_event($event, $customer_id, $order_id, $sessionId, $permId, $contactEmail)
	{
		$this->incomakerApi->postOrderEvent($event, $customer_id, $order_id, $sessionId, $permId, $contactEmail);
	}

	public function incomaker_user_logout($user_id)
	{
		as_enqueue_async_action(
			'post_event',
			array("logout", $user_id, $this->incomakerApi->getPermId()),
			EVENT_GROUP_NAME,
			false,
			15
		);
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
