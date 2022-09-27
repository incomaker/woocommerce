<?php

namespace Incomaker\Api\Controller;

/**
 * Description of PluginController
 */
class PluginController extends Controller {

    /**
     * Ping function.
     * 
     * @param integer $apiKey
     */
    public function ping() {
        return $this->curlGetQuery("plugin/");
    }

    public function login($login, $password, $palpUrl, $url, $timezone, $platformName, $platformVersion, $pluginVersion, $currency, $language) {

        return $this->curlGetQuery("plugin/", $this->formatGetRequest(array(
                            "login" => $login,
                            "password" => $password,
                            "serverPalpUrl" => $palpUrl,
                            "url" => $url,
                            "timeZone" => $timezone,
                            "platformName" => $platformName,
                            "platformVersion" => $platformVersion,
                            "pluginVersion" => $pluginVersion,
                            "currency" => $currency,
                            "language" => $language
        )));
    }

    /**
     * Sends data about your plugin to our servers. We can plan instructions than.
     * 
     * @param integer $clientsCount
     * @param integer $productsCount
     * @param integer $eventsCount
     * @param integer $imagesCount
     * @param integer $categoriesCount
     * @return string
     */
    public function summaryInfo($clientsCount, $productsCount, $eventsCount, $imagesCount, $categoriesCount) {
        return $this->curlPostQuery("plugin/", "", array("clientsCount" => $clientsCount,
                    "productsCount" => $productsCount,
                    "eventsCount" => $eventsCount,
                    "imagesCount" => $imagesCount,
                    "categoriesCount" => $categoriesCount));
    }

    /**
     * Returns planed instruction.
     * 
     * @return string
     */
    public function getInstruction() {
        return $this->curlGetQuery("plugin/instruction/");
    }

    /**
     * Returns new perm ID for tracking. E.g. need for events.
     * 
     * @return string
     */
    public function getNewPermId() {
        return $this->curlGetQuery("plugin/id/");
    }

}
