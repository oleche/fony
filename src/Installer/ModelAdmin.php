<?php

/* Auth Utils
 * Developed by OSCAR LECHE
 * V.1.0
 * DESCRIPTION: Authentication support for token generation and general authentication
 */

namespace Geekcow\Fony\Installer;

define('MY_DOC_ROOT', __DIR__);

use Composer\Factory;
use Composer\Installer\PackageEvent;
use Composer\IO\NullIO;
use Composer\Script\Event;
use Geekcow\Fony\Installer\Model\ModelMaintenance;
use Geekcow\Fony\Installer\Tools\SetupTools;
use Geekcow\Fony\Installer\User\DatabaseMaintenance;
use Geekcow\Fony\Installer\User\UserUpdate;
use Geekcow\FonyAuth\Utils\ConfigurationUtils;


class ModelAdmin
{


    public static function createModel(Event $event)
    {
        $composer = Factory::create(new NullIo(), './composer.json', false);

        $logo = "  __
 / _| ___  _ __  _   _        ___ ___  _ __ ___
| |_ / _ \| '_ \| | | |_____ / __/ _ \| '__/ _ \
|  _| (_) | | | | |_| |_____| (_| (_) | | |  __/
|_|  \___/|_| |_|\__, |      \___\___/|_|  \___|
                 |___/                          ";

        echo $logo . PHP_EOL;
        echo $composer->getPackage()->getName() . PHP_EOL;
        echo "version: " . $composer->getPackage()->getVersion() . PHP_EOL;
        echo 'Database Recreation tool' . PHP_EOL;
        echo '====================' . PHP_EOL;
        echo PHP_EOL;

        $vendorDir = dirname(realpath(Factory::getComposerFile()));
        echo 'Write the location of your fony configuration file: [' . $vendorDir . '/src/config/config.ini]: ';
        $config = SetupTools::getInput($vendorDir . "/src/config/config.ini");
        echo PHP_EOL;

        if (realpath(dirname($config)) === false) {
            exit('ERROR: Cannot continue, the configuration file does not exists');
        } else {
            $configurer = new ConfigurationConfigurer($config);
            $dummy = ConfigurationUtils::getInstance($config);

            echo 'Write the location of the model yml file: ';
            $model_file = SetupTools::getInput("");
            echo PHP_EOL;

            if ($model_file != "") {
                $dbmaintenance = new ModelMaintenance();
                $dbmaintenance->createModel();
            }
        }

        //var_dump($event->getArguments());
        echo PHP_EOL;
        echo 'DONE - Have fun using Fony-PHP. Please submit issues to: https://github.com/oleche/fony/issues';
        echo PHP_EOL;
        echo 'For documentation go to: https://github.com/oleche/fony/wiki';
        echo PHP_EOL;
        echo 'Please support if you like to: https://ko-fi.com/geekcow';
        exit();
        //var_dump($event->getArguments());
    }
}
