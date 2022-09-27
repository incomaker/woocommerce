<?php
namespace Incomaker\Api;

interface DriverInterface
{
    public function getSetting($key);

    public function updateSetting($key, $value);
}