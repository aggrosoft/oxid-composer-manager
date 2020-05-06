<?php

class OxidConsoleUtil {

    public static function installConfiguration ($modulePath) {
        return self::runCommand('oe:module:install-configuration ' . $modulePath);
    }

    public static function applyConfiguration ($modulePath) {
        return self::runCommand('oe:module:apply-configuration ' . $modulePath);
    }

    public static function resetConfigurations () {
        return self::runCommand('oe:module:reset-configurations');
    }

    public static function deleteModuleDataFromDatabase () {
        return self::runCommand('oe:oxideshop-update-component:delete-module-data-from-database');
    }

    public static function installAllModules () {
        return self::runCommand('oe:oxideshop-update-component:install-all-modules');
    }

    public static function transferModuleData () {
        return self::runCommand('oe:oxideshop-update-component:transfer-module-data');
    }

    public static function runCommand ($input) {
        $response = \TitasGailius\Terminal\Terminal::in(self::getSourcePath())
            ->run('vendor/bin/oe-console ' . $input);

        return $response->output();
    }

    public static function getSourcePath(){
        return realpath(getShopBasePath().'/../') . '/';
    }

}