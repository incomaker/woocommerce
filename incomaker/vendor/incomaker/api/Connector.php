<?php
namespace Incomaker\Api;

use Incomaker\Api\Controller\ContactController;
use Incomaker\Api\Controller\CategoryController;
use Incomaker\Api\Controller\EventController;
use Incomaker\Api\Controller\OrderController;
use Incomaker\Api\Controller\PluginController;
use Incomaker\Api\Controller\SegmentController;
use Incomaker\Api\Controller\ImageController;
use Incomaker\Api\Controller\ProductController;

class Connector {

    const INCOMAKER_API_KEY = "INCOMAKER_API_KEY";
    const INCOMAKER_ACCOUNT_ID = "INCOMAKER_ACCOUNT_ID";
    const INCOMAKER_PLUGIN_ID = "INCOMAKER_PLUGIN_ID";
    const INTERNAL_SERVER_ERROR = "Internal server error. Please, contact support.";

    /**
     * Incomaker API endpoint
     * @version 2.2.0
     *
     * @var string
     */
    private static $server = "https://api.incomaker.com/commons/v3/";
    private static $tracking = "https://dg.incomaker.com/tracking/resources/js/INlib.js";

    protected $driver;

    function __construct(DriverInterface $driver) {
        $this->driver = $driver;
    }

    public function createContactController() {
        $control = new ContactController();
        $control->setupController($this->driver->getSetting(self::INCOMAKER_API_KEY), self::$server);
        return $control;
    }

    public function createProductController() {
        $control = new ProductController();
        $control->setupController($this->driver->getSetting(self::INCOMAKER_API_KEY), self::$server);
        return $control;
    }

    public function createImageController() {
        $control = new ImageController();
        $control->setupController($this->driver->getSetting(self::INCOMAKER_API_KEY), self::$server);
        return $control;
    }

    public function createSegmentController() {
        $control = new SegmentController();
        $control->setupController($this->driver->getSetting(self::INCOMAKER_API_KEY), self::$server);
        return $control;
    }

    public function createPluginController() {
        $control = new PluginController();
        $control->setupController($this->driver->getSetting(self::INCOMAKER_API_KEY), self::$server);
        return $control;
    }

    public function createOrderController() {
        $control = new OrderController();
        $control->setupController($this->driver->getSetting(self::INCOMAKER_API_KEY), self::$server);
        return $control;
    }

    public function createCategoryController() {
        $control = new CategoryController();
        $control->setupController($this->driver->getSetting(self::INCOMAKER_API_KEY), self::$server);
        return $control;
    }

    public function createEventController() {
        $control = new EventController();
        $control->setupController($this->driver->getSetting(self::INCOMAKER_API_KEY), self::$server);
        return $control;
    }

    public function getApiKey() {
        return $this->driver->getSetting(self::INCOMAKER_API_KEY);
    }

    public function setApiKey($key) {
        $this->driver->updateSetting(self::INCOMAKER_API_KEY, $key);
    }

    public function getAccountKey() {
        return $this->driver->getSetting(self::INCOMAKER_ACCOUNT_ID);
    }

    public function setAccountKey($key) {
        $this->driver->updateSetting(self::INCOMAKER_ACCOUNT_ID, $key);
    }

    public function getTrackingCode() {
        if (!empty($this->getAccountKey()) && !empty($this->getPluginKey())) {
            return self::$tracking . '?accountUuid=' . $this->getAccountKey() . '&pluginUuid=' . $this->getPluginKey();
        } else {
            return "";
        }
    }

    public function getPluginKey() {
        return $this->driver->getSetting(self::INCOMAKER_PLUGIN_ID);
    }

    public function setPluginKey($key) {
        $this->driver->updateSetting(self::INCOMAKER_PLUGIN_ID, $key);
    }

    public function ping($apiKey) {
        $pluginController = $this->createPluginController();
        return $pluginController->ping();
    }

    public function login($login, $password, $palpUrl, $url, $timezone, $platformName = '', $platformVersion = '', $pluginVersion = '', $currency = '', $language = '') {
        $this->driver->updateSetting(self::INCOMAKER_API_KEY, "");
        $pluginController = $this->createPluginController();
        $json = $pluginController->login($login, $password, $palpUrl, $url, $timezone, $platformName, $platformVersion, $pluginVersion, $currency, $language);
        if (!isset($json->error)) {
            if (!empty($json->apiKey)) {
                $this->driver->updateSetting(self::INCOMAKER_API_KEY, $json->apiKey);
                $this->driver->updateSetting(self::INCOMAKER_ACCOUNT_ID, $json->accountId);
                $this->driver->updateSetting(self::INCOMAKER_PLUGIN_ID, $json->pluginId);
                return "";
            } else {
                return self::INTERNAL_SERVER_ERROR;
            }
        } else {
            return $json->error;
        }
    }

    public function logout() {
        $this->updateSetting(self::INCOMAKER_API_KEY, "");
    }

    public function updateSetting($key, $value) {
        $this->driver->updateSetting($key, $value);
    }

    public function getSetting($key) {
        $this->driver->getSetting($key);
    }
}