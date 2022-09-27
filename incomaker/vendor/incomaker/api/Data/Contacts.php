<?php

namespace Incomaker\Api\Data;

/**
 * Contact struct
 */
class Contacts {

    private $contacts = array();

    public function addContact(Contact $data) {
        array_push($this->contacts, $data->getArrayData());
    }

    public function getContact($index) {
        return $this->contacts[$index];
    }

    public function getData() {
        return json_encode($this->getArrayData());
    }

    public function getArrayData() {
        return $this->contacts;
    }

}
