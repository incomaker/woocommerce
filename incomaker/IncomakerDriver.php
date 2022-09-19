<?php
namespace Incomaker;

use \Incomaker\Api\Connector;

class IncomakerDriver implements \Incomaker\Api\DriverInterface
{
    private $value;

    public function __construct()
    {
        $opts = get_option("incomaker_option");

        $this->value[Connector::INCOMAKER_API_KEY] = $opts["api_key"];
        $this->value[Connector::INCOMAKER_ACCOUNT_ID] = $opts["account_id"];
        $this->value[Connector::INCOMAKER_PLUGIN_ID] = $opts["plugin_id"];
    }

    public function getSetting($key) {
        return $this->value[$key];
    }

    public function updateSetting($key, $value) {
        $this->value[$key] = $value;
    }

}