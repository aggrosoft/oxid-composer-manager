<?php

use Composer\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\StreamOutput;

class ComposerUtil
{
    public static function getPackageList () {
        //return self::runComposerCommand(['command' => 'show', '--direct' => false, '--format' => 'json']);
        @ini_set("memory_limit",-1);
        return self::runComposerCommand('show --direct -l --format=json');
    }

    public static function getPackageInfo ($package) {
        return self::runComposerCommand('show ' . $package . ' --format=json');
    }

    public static function updatePackage ($package) {
        @ini_set("memory_limit",-1);
        return self::runComposerCommand('update ' . $package);
    }

    public static function getSourcePath(){
        return realpath(getShopBasePath().'/../') . '/';
    }

    public static function runComposerCommand ($input) {
        // prepare env
        $cwd = getcwd();
        putenv('COMPOSER_HOME=' . self::getSourcePath() . 'vendor/bin/composer');
        putenv('COMPOSER_CACHE_DIR=' . getShopBasePath() . '/tmp/');
        chdir(self::getSourcePath());

        // Setup composer output formatter
        $stream = fopen('php://temp', 'w+');
        $output = new StreamOutput($stream);

        // Programmatically run command
        $application = new Application();
        $application->setAutoExit(false);
        $input = new StringInput($input);
        $input->setInteractive(false);
        $code = $application->run($input, $output);

        // restore env
        chdir($cwd);

        // rewind stream to read full contents
        rewind($stream);
        return stream_get_contents($stream);
    }

}