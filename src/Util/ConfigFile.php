<?php

namespace PHPUnitRetry\Util;

final class ConfigFile
{
    public static function getConfigFilename(): string
    {
        $segments = explode(DIRECTORY_SEPARATOR, __DIR__);
        $rootDirectory = DIRECTORY_SEPARATOR . $segments[1];

        if (file_exists($rootDirectory . \DIRECTORY_SEPARATOR . 'phpunit-retry.xml')) {
            $configFilename = $rootDirectory . \DIRECTORY_SEPARATOR . 'phpunit-retry.xml';
        } else {
            return dirname(__FILE__) . \DIRECTORY_SEPARATOR . 'phpunit-retry.xml';
        }

        return $configFilename;
    }
}
