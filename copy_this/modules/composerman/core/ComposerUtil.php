<?php

use Composer\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\StreamOutput;

class ComposerUtil
{
    public static function getComposerJson () {
        return file_get_contents(self::getSourcePath().'composer.json');
    }

    public static function setComposerJson ($contents) {
        @copy(self::getSourcePath().'composer.json', self::getSourcePath().'composer.json.backup-composerman');
        return @file_put_contents(self::getSourcePath().'composer.json', $contents);
    }

    public static function getPackageList () {
        //return self::runComposerCommand(['command' => 'show', '--direct' => false, '--format' => 'json']);
        @ini_set("memory_limit",-1);
        return self::runComposerCommand('show --direct -l --format=json');
    }

    public static function getPackageInfo ($package) {
        return self::runComposerCommand('show ' . $package . ' --format=json');
    }

    public static function getPackageJson ($package) {
        $packageFile = self::getSourcePath().'vendor/'.$package.'/composer.json';
        if (file_exists($packageFile)){
            return json_decode(file_get_contents($packageFile), true);
        }
    }

    public static function addPackage ($package) {
        @ini_set("memory_limit",-1);
        return self::runComposerCommand('require ' . $package);
    }

    public static function updatePackage ($package) {
        @ini_set("memory_limit",-1);
        return self::runComposerCommand('update ' . $package);
    }

    public static function removePackage ($package) {
        @ini_set("memory_limit",-1);
        return self::runComposerCommand('remove ' . $package);
    }

    public static function dumpAutoloader () {
        @ini_set("memory_limit",-1);
        return self::runComposerCommand('dumpautoload');
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

    public static function purgePackage($package) {
        $info = ComposerUtil::getPackageJson($package);
        if ($info && $info['type'] === 'oxideshop-module'){
            $settings = $info['extra']['oxideshop'];
            if ($settings['target-directory']){
                $moduleDir = getShopBasePath().'/modules/'.$settings['target-directory'];
                if (is_dir($moduleDir)){
                    self::deleteDirectory($moduleDir);
                }
            }

        }
    }

    public static function getPackageType($package) {
        $info = ComposerUtil::getPackageJson($package);
        return $info ? $info['type'] : null;
    }

    protected static function deleteDirectory( $dir )
    {
        $files = array_diff(scandir($dir), array('.', '..'));

        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::deleteDirectory("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }

}