<?php

namespace PHPUnitRetry\Util;

final class ConfigFile
{
    public static function getConfigFilename(): string
    {
        $cwd = getcwd() . \DIRECTORY_SEPARATOR;

        if (file_exists($cwd . 'phpunit-retry.xml')) {
            $configFilename = $cwd . 'phpunit-retry.xml';
        } else {
            return dirname(__FILE__) . \DIRECTORY_SEPARATOR . 'phpunit-retry.xml';
        }

        return $configFilename;
    }
}
