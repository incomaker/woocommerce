<?php

namespace Incomaker\Api\Controller;

use Incomaker\Api\Data\Contact;
use Incomaker\Api\Data\Contacts;

/**
 * Description of ContactsController
 */
class ContactController extends Controller {

    /**
     * 
     * @param $productId
     * @return Contact
     */
    public function createContactObject($clientContactId) {
        return new Contact($clientContactId);
    }

    /**
     * 
     * @return Contacts
     */
    public function createContactsObject() {
        return new Contacts();
    }
    
    /**
     * Add more than one contact. 
     * 
     * Supports batch job.
     * 
     * @param string $data
     * @param $requestId
     * @return string
     */
    public function addContacts(Contacts $data, $requestId = 0) {
        return $this->curlPostQuery("contact/batch/", "?requestId=" . $requestId, json_encode(array("data" => $data->getArrayData())));
    }

    /**
     * Adds one contact
     * 
     * @param Contact $contactObject
     * @return
     */
    public function addContact(Contact $contactObject) {
        return $this->curlPostQuery("contact/", "", $contactObject->getData());
    }

    /**
     * Returns contact.
     * 
     * @param string $contactId
     * @param string $clientContactId
     * @param string $personalId
     * @param string $email
     * @param string $phoneNumber1
     * @return string
     */
    public function getContact($contactId = null, $clientContactId = null, $personalId = null, $email = null, $phoneNumber1 = null) {
        return $this->curlGetQuery("contact/",
            "?contactId=" . urlencode($contactId) .
            "&clientContactId=" . urlencode($clientContactId) .
            "&personalId=" . urlencode($personalId) .
            "&email=" . urlencode($email) .
            "&phoneNumber1=" . urlencode($phoneNumber1));
    }

    /**
     * Returns contact's custom columns
     * 
     * @return string
     */
    public function contactCustomColumns() {
        return $this->curlGetQuery("contact/customcolumns/", "");
    }

    /**
     * Updates contact
     * 
     * @param Contact $contactObject
     * @return
     */
    public function updateContact(Contact $contactObject) {
        return $this->curlPostQuery("contact/", "", $contactObject->getData());
    }

    /**
     * Unsubscribes contact from all subscription segments.
     * 
     * @param string $contactId
     * @param string $clientContactId
     * @return string
     */
    public function unsubscribeContactByContactId($contactId) {
        return $this->curlPutQuery("contact/unsubscribe/", "?contactId=" . urlencode($contactId));
    }

    /**
     * Unsubscribes contact from all subscription segments.
     * 
     * @param string $contactId
     * @param string $clientContactId
     * @return string
     */
    public function unsubscribeContactByClientContactId($clientContactId) {
        return $this->curlPutQuery("contact/unsubscribe/", "&clientContactId=" . urlencode($clientContactId));
    }
}
