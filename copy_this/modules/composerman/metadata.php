<?php

$sMetadataVersion = '1.1';

$aModule = array(
    'id'           => 'composerman',
    'title'        => '<img src="/modules/composerman/out/img/logo-composer.png" height="15" alt="Composer Manager" title="Composer Manager"> Composer Manager',
    'description'  => 'Manage oxid eShop composer modules through admin interface',
    'thumbnail'    => '',
    'version'      => '1.1.7',
    'author'       => 'Aggrosoft GmbH',
    'extend'      => array(

    ),
    'files'       => array(
        'ComposerUtil' => 'composerman/core/ComposerUtil.php',
        'OxidConsoleUtil' => 'composerman/core/OxidConsoleUtil.php',
        'composerman' => 'composerman/application/controllers/admin/composerman.php',
    ),
    'templates'   => array(
        'composerman.tpl' => 'composerman/application/views/admin/tpl/composerman.tpl',
    ),
    'events'       => array(

    ),
    'settings' => array(
        array('group' => 'composerman_main', 'name' => 'sComposerExecutable', 'type' => 'str',   'value' => '' ),

    ),
    'blocks' => array(

    )
);
