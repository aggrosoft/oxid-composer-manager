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

}