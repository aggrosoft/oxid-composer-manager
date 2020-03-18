<?php

class composerman extends oxAdminDetails {

    protected $_sThisTemplate = 'composerman.tpl';

    public function render() {
        $sTemplate = parent::render();
        $packages = json_decode(ComposerUtil::getPackageList());
        $this->_aViewData['packages'] = $packages;
        return $sTemplate;
    }

}