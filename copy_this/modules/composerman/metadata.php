<?php
$aModule = array(
    'id'           => 'composerman',
    'title'        => '<img src=' . oxRegistry::getConfig()->getShopUrl() . 'modules/composerman/out/img/logo-composer.png" height="15" alt="Composer Manager" title="Composer Manager"> Composer Manager',
    'description'  => 'Manage oxid eShop composer modules through admin interface',
    'thumbnail'    => '',
    'version'      => '1.0.0',
    'author'       => 'Aggrosoft GmbH',
    'extend'      => array(

    ),
    'files'       => array(
        'ComposerUtil' => 'composerman/core/ComposerUtil.php',
        'composerman' => 'composerman/application/controllers/admin/composerman.php',
    ),
    'templates'   => array(

    ),
    'events'       => array(

    ),
    'settings' => array(

    ),
    'blocks' => array(

    )
);
