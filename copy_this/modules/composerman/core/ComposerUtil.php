<?php

use Composer\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

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

    public static function getComposerExecutable () {
        return oxRegistry::getConfig()->getShopConfVar('sComposerExecutable', null, 'module:composerman');
    }

    public static function runComposerCommand ($input) {
        try{
            if (self::useTerminal()) {
                return self::runTerminalComposerCommand($input);
            }else{
                return self::runDirectComposerCommand($input);
            }
        }catch(Exception $ex) {
            return $ex->getMessage();
        }

    }

    public static function backupPackageLicenses($package){
        $moduleDir = self::getPackageModuleDirectory($package);
        if ($moduleDir && is_dir($moduleDir.'/license/')){
            self::copyDirectory($moduleDir.'/license/', self::getLicenseBackupDirectory($package));
        }
    }

    public static function restorePackageLicenses($package){
        $moduleDir = self::getPackageModuleDirectory($package);
        if ($moduleDir && is_dir($moduleDir.'/license/')){
            $backupDir = self::getLicenseBackupDirectory($package);
            if (is_dir($backupDir)){
                self::copyDirectory($backupDir,$moduleDir.'/license/');
            }
        }
    }

    public static function purgePackage($package) {
        $moduleDir = self::getPackageModuleDirectory($package);
        if ($moduleDir && is_dir($moduleDir)){
            self::deleteDirectory($moduleDir);
        }
    }

    public static function getPackageModuleDirectory($package){
        $info = ComposerUtil::getPackageJson($package);
        if ($info && $info['type'] === 'oxideshop-module'){
            $settings = $info['extra']['oxideshop'];
            if ($settings['target-directory']) {
                return getShopBasePath() . '/modules/' . $settings['target-directory'];
            }
        }
    }

    public static function getPackageType($package) {
        $info = ComposerUtil::getPackageJson($package);
        return $info ? $info['type'] : null;
    }

    protected static function useTerminal () {
        return self::getComposerExecutable() !== '';
    }

    protected static function runTerminalComposerCommand ($input) {
        $response = \TitasGailius\Terminal\Terminal::in(self::getSourcePath())
            ->withEnvironmentVariables([
                'COMPOSER_HOME' => self::getComposerExecutable(),
                'COMPOSER_CACHE_DIR' =>  getShopBasePath() . '/tmp/'
            ])
            ->retries(3)
            ->run(self::getComposerExecutable() . ' ' . $input);

        return $response->output();
    }

    protected static function runDirectComposerCommand ($input) {
        // prepare env
        $cwd = getcwd();
        putenv('COMPOSER_HOME=' . self::getSourcePath() . 'vendor/bin/composer');
        putenv('COMPOSER_CACHE_DIR=' . getShopBasePath() . '/tmp/');
        chdir(self::getSourcePath());

        // Setup composer output formatter
        $stream = fopen('php://temp', 'w+');
        $output = new StreamOutput($stream);

        $dispatcher = new Symfony\Component\EventDispatcher\EventDispatcher();
        $dispatcher->addListener(ConsoleEvents::ERROR, function (ConsoleErrorEvent $event) {
            $output = $event->getOutput();

            $command = $event->getCommand();

            $output->writeln(sprintf('Oops, exception thrown while running command <info>%s</info>', $command->getName()));

            // gets the current exit code (the exception code or the exit code set by a ConsoleEvents::TERMINATE event)
            $exitCode = $event->getExitCode();

            // changes the exception to another one
            $event->setError(new \LogicException('Caught exception', $exitCode, $event->getError()));
        });

        // Programmatically run command
        $application = new Application();
        $application->setAutoExit(false);
        $application->setDispatcher($dispatcher);
        $input = new StringInput($input);
        $input->setInteractive(false);
        $code = $application->run($input, $output);

        // restore env
        chdir($cwd);

        // rewind stream to read full contents
        rewind($stream);
        return stream_get_contents($stream);
    }

    protected static function copyDirectory ($src, $destination)
    {
        if (!is_dir($destination)){
            mkdir($destination, 0777, true);
        }

        $files = array_diff(scandir($src), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$src/$file")) ? self::copyDirectory("$src/$file", "$destination/$file") : copy("$src/$file", "$destination/$file");
        }
    }

    protected static function deleteDirectory( $dir )
    {
        $files = array_diff(scandir($dir), array('.', '..'));

        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::deleteDirectory("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }

    protected static function getLicenseBackupDirectory($package) {
        return getShopBasePath()."/tmp/.licensebackup/$package/";
    }
}