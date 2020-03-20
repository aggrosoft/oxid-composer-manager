<?php

class composerman extends oxAdminDetails {

    protected $_sThisTemplate = 'composerman.tpl';

    public function render() {
        $sTemplate = parent::render();
        $this->_aViewData['token'] = oxRegistry::getSession()->getSessionChallengeToken();
        return $sTemplate;
    }

    public function getpackages() {
        $packages = ComposerUtil::getPackageList();
        header('Content-Type: application/json');
        echo $packages;
        exit();
    }

    public function addpackage() {
        $package = oxRegistry::getConfig()->getRequestParameter('package');
        $out = ComposerUtil::addPackage($package);
        echo $out;
        exit();
    }

    public function updatepackage() {
        $package = oxRegistry::getConfig()->getRequestParameter('package');
        ComposerUtil::purgePackage($package);
        $out = ComposerUtil::updatePackage($package);
        echo $out;
        exit();
    }

    public function removepackage() {
        $package = oxRegistry::getConfig()->getRequestParameter('package');
        if (ComposerUtil::getPackageType($package) === 'oxideshop-module') {
            ComposerUtil::purgePackage($package);
            $out = ComposerUtil::removePackage($package);
            echo $out;
        }
        exit();
    }

    public function getcomposerjson() {
        header('Content-Type: application/json');
        echo ComposerUtil::getComposerJson();
        exit();
    }

    public function savecomposerjson() {
        ComposerUtil::setComposerJson(oxRegistry::getConfig()->getRequestParameter('contents'));
        header('Content-Type: application/json');
        echo ComposerUtil::getComposerJson();
        exit();
    }

    public function runcommand() {
        $cmd = oxRegistry::getConfig()->getRequestParameter('cmd');
        $cmd = preg_replace('/^composer/', '', $cmd);
        $out = ComposerUtil::runComposerCommand(trim($cmd));
        echo $out;
        exit();
    }

}