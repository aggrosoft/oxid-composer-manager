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

    public function updatepackage() {
        $out = ComposerUtil::updatePackage(oxRegistry::getConfig()->getRequestParameter('package'));
        echo $out;
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