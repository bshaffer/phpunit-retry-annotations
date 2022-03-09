<?php

namespace PHPUnitRetry\Util;

final class ConfigFile
{
    public static function getConfigFilename(): ?string
    {
        $cwd = getcwd() . \DIRECTORY_SEPARATOR;

        if (file_exists($cwd . 'phpunit-retry.xml')) {
            $configFilename = $cwd . 'phpunit-retry.xml';
        } elseif (file_exists($cwd . 'phpunit-retry.xml.dist')) {
            $configFilename = $cwd . 'phpunit-retry.xml.dist';
        } else {
            return null;
        }
        
        return $configFilename;
    }
}
