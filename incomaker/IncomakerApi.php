<?php

namespace Incomaker;

include_once __DIR__ . '/vendor/incomaker/api/Connector.php';

class IncomakerApi
{
	private $incomaker;
	private $contactController;
	private $eventController;
	private $pluginController;

	public function __construct($apiKey = null)
	{
		$this->incomaker = new \Incomaker\Api\Connector(new IncomakerDriver($apiKey));
	}

	private function getSanitizedCookie($key, $default = null) {
		if (!isset($_COOKIE[$key])) return $default;
		$value = sanitize_text_field(strval($_COOKIE[$key]));
		if (empty($value)) return $default;
		return $value;
	}

	public function getPermId() {
		return $this->getSanitizedCookie('incomaker_p', $this->getSanitizedCookie('permId', ''));
	}

	public function getCampaignId()	{
		return $this->getSanitizedCookie('incomaker_c', '');
	}

	public function getSessionId() {
		return $this->getSanitizedCookie('inco_session_temp_browser', '');
	}

	public function postProductEvent($event, $customer, $product, $session, $permId)
	{

		$event = new \Incomaker\Api\Data\Event($event, $permId);

		if (isset($customer)) {
			$event->setClientContactId($customer);
		}
		if (!empty($product)) {
			$event->setRelatedId($product);
		}
		if (!empty($session)) {
			$event->setSessionId($session);
		}
		if (!isset($this->eventController)) {
			$this->eventController = $this->incomaker->createEventController();
		}
		$this->eventController->addEvent($event);
	}

	public function postEvent($event, $customer, $permId, $email = null)
	{
		$event = new \Incomaker\Api\Data\Event($event, $permId);

		if (isset($customer)) {
			$event->setClientContactId($customer);
		}
		if (isset($email)) {
			$event->setEmail($email);
		}
		if (!isset($this->eventController)) {
			$this->eventController = $this->incomaker->createEventController();
		}
		$this->eventController->addEvent($event);
	}

	public function postOrderEvent($event, $customer, $order_id, $session, $permId, $email = null)
	{
		$event = new \Incomaker\Api\Data\Event($event, $permId);

		if (isset($customer)) {
			$event->setClientContactId($customer);
		}
		if (isset($email)) {
			$event->setEmail($email);
		}
		$event->setRelatedId($order_id);
		if (!empty($session)) {
			$event->setSessionId($session);
		}
		if (!isset($this->eventController)) {
			$this->eventController = $this->incomaker->createEventController();
		}
		$this->eventController->addEvent($event);
	}

	public function updateContact($user_id, $customer, $permId)
	{

		if (!isset($this->contactController)) {
			$this->contactController = $this->incomaker->createContactController();
		}
		$contact = new \Incomaker\Api\Data\Contact($user_id);
		$contact->setPermId($permId);

		$contact->setFirstName($customer->get_billing_first_name() != "" ? $customer->get_billing_first_name() : $customer->get_first_name());
		$contact->setLastName($customer->get_billing_last_name() != "" ? $customer->get_billing_last_name() : $customer->get_last_name());
		$contact->setCompanyName($customer->get_billing_company());
		$contact->setStreet($customer->get_billing_address_1());
		$contact->setStreet2($customer->get_billing_address_2());
		$contact->setCity($customer->get_billing_city());
		$contact->setZipCode($customer->get_billing_postcode());
		$contact->setPhoneNumber1($customer->get_billing_phone());
		$contact->setCountry($customer->get_billing_country());

		$this->contactController->updateContact($contact);

	}

	public function addContact($contact, $permId)
	{
		if (!isset($this->contactController)) {
			$this->contactController = $this->incomaker->createContactController();
		}
		$contact->setPermId($permId);
		return $this->contactController->addContact($contact);

	}

	/**
	 * @return Api\Controller\PluginController
	 */
	private function getPluginController()
	{
		if (!isset($this->pluginController)) {
			$this->pluginController = $this->incomaker->createPluginController();
		}
		return $this->pluginController;
	}

	/**
	 * Load Account and Plugin UUIDs for current API key.
	 *
	 * @return Object { accountUuid; pluginUuid; }
	 */
	public function getPluginInfo()
	{
		return $this
			->getPluginController()
			->getInfo();
	}
}
