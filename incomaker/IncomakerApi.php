<?php

namespace Incomaker;

include_once __DIR__ . '/vendor/incomaker/api/Connector.php';

class IncomakerApi
{
    private $incomaker;
    private $contactController;
    private $eventController;

    public function __construct() {

        $this->incomaker = new \Incomaker\Api\Connector(new IncomakerDriver());
    }

    public function getPermId()
    {

        if (isset($_COOKIE["incomaker_p"])) {
            return $_COOKIE["incomaker_p"];
        }
        return $_COOKIE["permId"];
    }

    public function postProductEvent($event, $customer, $product, $session) {

        $event = new \Incomaker\Api\Data\Event($event, $this->getPermId());

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

    public function postEvent($event, $customer)
    {
        $event = new \Incomaker\Api\Data\Event($event, $this->getPermId());

        if (isset($customer)) {
            $event->setClientContactId($customer);
        }
        if (!isset($this->eventController)) {
            $this->eventController = $this->incomaker->createEventController();
        }
        $this->eventController->addEvent($event);
    }

    public function getCampaignId() {
        if (isset($_COOKIE["incomaker_c"])) {
            return $_COOKIE["incomaker_c"];
        } else {
            return "";
        }
    }

    public function postOrderEvent($event, $customer, $total, $session)
    {
        $event = new \Incomaker\Api\Data\Event($event, $this->getPermId());

        if (isset($customer)) {
            $event->setClientContactId($customer);
        }
        $event->setCampaignId($this->getCampaignId());  //TODO remove passing campaignId this way
        $event->addCustomField("total", $total);
        if (!empty($session)) {
            $event->setSessionId($session);
        }
        if (!isset($this->eventController)) {
            $this->eventController = $this->incomaker->createEventController();
        }
        $this->eventController->addEvent($event);
    }

    public function updateContact($user_id, $customer) {

        if (!isset($this->contactController)) {
            $this->contactController = $this->incomaker->createContactController();
        }
        $contact = new \Incomaker\Api\Data\Contact($user_id);
        $contact->setPermId($this->getPermId());

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


    public function addContact($user_id, $email)
    {

        if (!isset($this->contactController)) {
            $this->contactController = $this->incomaker->createContactController();
        }
        $contact = new \Incomaker\Api\Data\Contact($user_id);
        $contact->setPermId($this->getPermId());
        $contact->setEmail($email);

        $this->contactController->addContact($contact);

    }
}