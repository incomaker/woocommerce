<?php

namespace Incomaker\Api\Data;

/**
 * Contact struct
 */
class Contact {

    private $permId;
    private $clientContactId;
    private $firstName;
    private $lastName;
    private $degreeBefore;
    private $degreeAfter;
    private $salutation;
    private $companyPosition;
    private $companyName;
    private $personalId;
    private $consentDate;
    private $consentTitle;
    private $email;
    private $phoneNumber1;
    private $phoneNumber2;
    private $street;
    private $street2;
    private $city;
    private $zipCode;
    private $region;
    private $country;
    private $language;
    private $birthday;
    private $latitude;
    private $longitude;
    private $sex;
    private $web;
    private $remarks;
    private $facebook;
    private $skype;
    private $linkedIn;
    private $twitter;
    private $vk;
    private $customFields = array();
    private $segments = array();

    function __construct($clientContactId) {
        $this->clientContactId = $clientContactId;
    }

    function addSegment($segment) {
        if (!in_array($segment, $this->segments)) {
            array_push($this->segments, $segment);
        }
    }

    public function removeSegment($segment) {
        if (($key = array_search($segment, $this->segments)) !== false) {
            unset($this->segments[$key]);
        }
    }

    public function addCustomField($customField, $value) {
        if (!array_key_exists($customField, $this->customFields)) {
            $this->customFields[$customField] = $value;
        }
    }

    public function removeCustomField($customField) {
        if (($key = array_search($customField, $this->customFields)) !== false) {
            unset($this->customFields[$key]);
        }
    }

    public function getData() {
        return json_encode($this->getArrayData());
    }

    public function getArrayData() {
        $arrayData = array();
        if (!is_null($this->clientContactId)) {
            $arrayData["clientContactId"] = $this->clientContactId;
        }
        if (!is_null($this->permId)) {
            $arrayData["permId"] = $this->permId;
        }
        if (!is_null($this->firstName)) {
            $arrayData["firstName"] = $this->firstName;
        }
        if (!is_null($this->lastName)) {
            $arrayData["lastName"] = $this->lastName;
        }
        if (!is_null($this->degreeBefore)) {
            $arrayData["degreeBefore"] = $this->degreeBefore;
        }
        if (!is_null($this->degreeAfter)) {
            $arrayData["degreeAfter"] = $this->degreeAfter;
        }
        if (!is_null($this->salutation)) {
            $arrayData["salutation"] = $this->salutation;
        }
        if (!is_null($this->companyPosition)) {
            $arrayData["companyPosition"] = $this->companyPosition;
        }
        if (!is_null($this->companyName)) {
            $arrayData["companyName"] = $this->companyName;
        }
        if (!is_null($this->personalId)) {
            $arrayData["personalId"] = $this->personalId;
        }
        if (!is_null($this->consentDate)) {
            $arrayData["consentDate"] = $this->consentDate;
        }
        if (!is_null($this->consentTitle)) {
            $arrayData["consentTitle"] = $this->consentTitle;
        }
        if (!is_null($this->email)) {
            $arrayData["email"] = $this->email;
        }
        if (!is_null($this->phoneNumber1)) {
            $arrayData["phoneNumber1"] = $this->phoneNumber1;
        }
        if (!is_null($this->phoneNumber2)) {
            $arrayData["phoneNumber2"] = $this->phoneNumber2;
        }
        if (!is_null($this->street)) {
            $arrayData["street"] = $this->street;
        }
        if (!is_null($this->street2)) {
            $arrayData["street2"] = $this->street2;
        }
        if (!is_null($this->city)) {
            $arrayData["city"] = $this->city;
        }
        if (!is_null($this->zipCode)) {
            $arrayData["zipCode"] = $this->zipCode;
        }
        if (!is_null($this->region)) {
            $arrayData["region"] = $this->region;
        }
        if (!is_null($this->country)) {
            $arrayData["country"] = $this->country;
        }
        if (!is_null($this->language)) {
            $arrayData["language"] = $this->language;
        }
        if (!is_null($this->birthday)) {
            $arrayData["birthday"] = $this->birthday;
        }
        if (!is_null($this->latitude)) {
            $arrayData["latitude"] = $this->latitude;
        }
        if (!is_null($this->longitude)) {
            $arrayData["longitude"] = $this->longitude;
        }
        if (!is_null($this->sex)) {
            $arrayData["sex"] = $this->sex;
        }
        if (!is_null($this->web)) {
            $arrayData["web"] = $this->web;
        }
        if (!is_null($this->remarks)) {
            $arrayData["remarks"] = $this->remarks;
        }
        if (!is_null($this->facebook)) {
            $arrayData["facebook"] = $this->facebook;
        }
        if (!is_null($this->skype)) {
            $arrayData["skype"] = $this->skype;
        }
        if (!is_null($this->linkedIn)) {
            $arrayData["linkedIn"] = $this->linkedIn;
        }
        if (!is_null($this->twitter)) {
            $arrayData["twitter"] = $this->twitter;
        }
        if (!is_null($this->vk)) {
            $arrayData["vk"] = $this->vk;
        }
        if (!empty($this->segments)) {
            $arrayData["segments"] = $this->segments;
        }
        if (!empty($this->customFields)) {
            $arrayData["customFields"] = $this->customFields;
        }
        return $arrayData;
    }

    function getClientContactId() {
        return $this->clientContactId;
    }

    function getPermId() {
        return $this->permId;
    }

    function getFirstName() {
        return $this->firstName;
    }

    function getLastName() {
        return $this->lastName;
    }

    function getDegreeBefore() {
        return $this->degreeBefore;
    }

    function getDegreeAfter() {
        return $this->degreeAfter;
    }

    function getSalutation() {
        return $this->salutation;
    }

    function getCompanyPosition() {
        return $this->companyPosition;
    }

    function getCompanyName() {
        return $this->companyName;
    }

    function getPersonalId() {
        return $this->personalId;
    }

    function getConsentDate() {
        return $this->consentDate;
    }

    function getConsentTitle() {
        return $this->consentTitle;
    }

    function getEmail() {
        return $this->email;
    }

    function getPhoneNumber1() {
        return $this->phoneNumber1;
    }

    function getPhoneNumber2() {
        return $this->phoneNumber2;
    }

    function getStreet() {
        return $this->street;
    }

    function getStreet2() {
        return $this->street2;
    }

    function getCity() {
        return $this->city;
    }

    function getZipCode() {
        return $this->zipCode;
    }

    function getRegion() {
        return $this->region;
    }

    function getCountry() {
        return $this->country;
    }

    function getLanguage() {
        return $this->language;
    }

    function getBirthday() {
        return $this->birthday;
    }

    function getLatitude() {
        return $this->latitude;
    }

    function getLongitude() {
        return $this->longitude;
    }

    function getWeb() {
        return $this->web;
    }

    function getSex() {
        return $this->sex;
    }

    function getRemarks() {
        return $this->remarks;
    }

    function getFacebook() {
        return $this->facebook;
    }

    function getSkype() {
        return $this->skype;
    }

    function getLinkedIn() {
        return $this->linkedIn;
    }

    function getTwitter() {
        return $this->twitter;
    }

    function getVk() {
        return $this->vk;
    }

    function setClientContactId($clientContactId) {
        $this->clientContactId = $clientContactId;
    }

    function setPermId($permId) {
        $this->permId = $permId;
    }

    function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    function setDegreeBefore($degreeBefore) {
        $this->degreeBefore = $degreeBefore;
    }

    function setDegreeAfter($degreeAfter) {
        $this->degreeAfter = $degreeAfter;
    }

    function setSalutation($salutation) {
        $this->salutation = $salutation;
    }

    function setCompanyPosition($companyPosition) {
        $this->companyPosition = $companyPosition;
    }

    function setCompanyName($companyName) {
        $this->companyName = $companyName;
    }

    function setPersonalId($personalId) {
        $this->personalId = $personalId;
    }

    function setConsentDate($consentDate) {
        $this->consentDate = $consentDate;
    }

    function setConsentTitle($consentTitle) {
        $this->consentTitle = $consentTitle;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setPhoneNumber1($phoneNumber1) {
        $this->phoneNumber1 = $phoneNumber1;
    }

    function setPhoneNumber2($phoneNumber2) {
        $this->phoneNumber2 = $phoneNumber2;
    }

    function setStreet($street) {
        $this->street = $street;
    }

    function setStreet2($street2) {
        $this->street2 = $street2;
    }

    function setCity($city) {
        $this->city = $city;
    }

    function setZipCode($zipCode) {
        $this->zipCode = $zipCode;
    }

    function setRegion($region) {
        $this->region = $region;
    }

    function setCountry($country) {
        $this->country = $country;
    }

    function setLanguage($language) {
        $this->language = $language;
    }

    function setBirthday($birthday) {
        $date = new \DateTime($birthday);
        $this->birthday = $date->format('Y-m-d\T00:00:00+00:00');
    }

    function setLatitude($latitude) {
        $this->latitude = $latitude;
    }

    function setLongitude($longitude) {
        $this->longitude = $longitude;
    }

    function setWeb($web) {
        $this->web = $web;
    }

    function setSex($sex) {
        $this->sex = $sex;
    }

    function setRemarks($remarks) {
        $this->remarks = $remarks;
    }

    function setFacebook($facebook) {
        $this->facebook = $facebook;
    }

    function setSkype($skype) {
        $this->skype = $skype;
    }

    function setLinkedIn($linkedIn) {
        $this->linkedIn = $linkedIn;
    }

    function setTwitter($twitter) {
        $this->twitter = $twitter;
    }

    function setVk($vk) {
        $this->vk = $vk;
    }

}
