<?php

/* Fony Setup Tool
 * Developed by OSCAR LECHE
 * V.1.0
 * DESCRIPTION: Setup tool for Fony PHP
 */

namespace Geekcow\Fony\Installer;

define('MY_DOC_ROOT', __DIR__);

use Composer\Factory;
use Composer\Installer\PackageEvent;
use Composer\IO\NullIO;
use Composer\Script\Event;
use Geekcow\Fony\Installer\Builder\PathBuilder;
use Geekcow\Fony\Installer\Tools\SetupTools;
use Geekcow\Fony\Installer\User\DatabaseMaintenance;
use Geekcow\Fony\Installer\User\UserCreation;
use Geekcow\FonyAuth\Utils\ConfigurationUtils;

class Setup
{


    public static function init(Event $event)
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
        echo 'Installation script' . PHP_EOL;
        echo '====================' . PHP_EOL;
        echo PHP_EOL;

        echo 'Name of your project: [fony-project]: ';
        $project_name = SetupTools::getInput("fony-project");
        $project_name_fixed = SetupTools::dashesToCamelCase($project_name, true);
        echo PHP_EOL;

        echo 'Name of your organizatoin: [fony-organization]: ';
        $organization_name = SetupTools::getInput("fony-organization");
        $organization_name_fixed = SetupTools::dashesToCamelCase($organization_name, true);
        echo PHP_EOL;

        $vendorDir = dirname(realpath(Factory::getComposerFile()));
        echo 'Path for your fony project: [' . $vendorDir . ']: ';
        $rootPath = SetupTools::getInput($vendorDir);
        echo PHP_EOL;

        echo 'Path for your fony configuration file: [' . $vendorDir . '/src/config/config.ini]: ';
        $config = SetupTools::getInput($vendorDir . "/src/config/config.ini");
        echo PHP_EOL;

        $configurer = new ConfigurationConfigurer($config);
        $configurer->changeGroup('fony');
        $configurer->setField('fony.app_name', $project_name);

        echo 'URL of your API: [api.test.com]: ';
        $site_url = SetupTools::getInput("api.test.com");
        $configurer->changeGroup('fony');
        $configurer->setField('fony.site_url', $site_url);
        echo PHP_EOL;

        echo 'PATH of your API: ' . $site_url . '[/v1/]: ';
        $site_internal_path = SetupTools::getInput('/v1/');
        echo PHP_EOL;

        echo 'URL of your API Assets: [assets.test.com]: ';
        $file_url = SetupTools::getInput("assets.test.com");
        $configurer->changeGroup('fony');
        $configurer->setField('fony.file_url', $file_url);
        echo PHP_EOL;

        echo 'Internal path of your API assets: [' . $vendorDir . '/assets/]: ';
        $file_path = SetupTools::getInput($vendorDir . '/assets/');
        $configurer->changeGroup('fony');
        $configurer->setField('fony.file_path', $file_path);
        echo PHP_EOL;

        echo 'Database information' . PHP_EOL;
        echo 'Currently, fony-php only supports MySQL. Soon more databases will be supported' . PHP_EOL;
        echo '====================' . PHP_EOL;
        echo 'Server location: [localhost]: ';
        $db_server = SetupTools::getInput("localhost");
        echo PHP_EOL;
        echo 'DB username: [root]: ';
        $db_user = SetupTools::getInput("root");
        echo PHP_EOL;
        echo 'DB password: [r00t]: ';
        $db_password = SetupTools::getInput("r00t");
        echo PHP_EOL;
        echo 'database: [' . $project_name_fixed . ']: ';
        $database = SetupTools::getInput($project_name_fixed);
        echo PHP_EOL;
        $configurer->changeGroup('dbcore');
        $configurer->setField('dbcore.server', $db_server);
        $configurer->setField('dbcore.db_user', $db_user);
        $configurer->setField('dbcore.db_pass', $db_password);
        $configurer->setField('dbcore.database', $database);
        $configurer->setField('dbcore.ipp', 25);

        echo 'Application information' . PHP_EOL;
        echo '====================' . PHP_EOL;
        echo 'Is this an authentication app (y/Y/n/N): [n]: ';
        $auth_app = SetupTools::getInput("n");
        $auth_app = trim(strtolower($auth_app));
        echo PHP_EOL;

        if ($auth_app == "y") {
            $secret = "";
            while ($secret == "") {
                echo 'Write the application secret word: ';
                $secret = SetupTools::getInput();
                $configurer->changeGroup('fony');
                $configurer->setField('fony.app_secret', $secret);
            }

            echo 'Write the administrator email: [admin@test.com]: ';
            $username = SetupTools::getInput("admin@test.com");
            echo PHP_EOL;

            $encodedUser = md5($username);
            $client = sha1($encodedUser . $username . date("Y-m-d H:i:s"));
            $secret_key = sha1($client . $secret);
            $configurer->changeGroup('fony');
            $configurer->setField('fony.user_client', $client);
            $configurer->setField('fony.user_secret', $secret_key);

            $configurer->export();
            //dummy needs to be created in order to initialize the configuration in the setup instance
            $dummy = ConfigurationUtils::getInstance($config);

            $password = "";
            while ($password == "") {
                echo 'Write the administrator password: ';
                $password = SetupTools::getInput();
            }
            echo PHP_EOL;

            echo 'Build default database (y/Y/n/N): [y]: ';
            $build_default = SetupTools::getInput("y");
            $build_default = trim(strtolower($build_default));
            echo PHP_EOL;

            if ($build_default == "y") {
                //create the default values in the database
                $dbmaintenance = new DatabaseMaintenance();
                $dbmaintenance->createAuthenticated();
            }
            echo PHP_EOL;
        } else {
            echo 'Write the oauth2 server location: []: ';
            $authenticationServer = SetupTools::getInput("");
            echo PHP_EOL;

            echo 'Write the oauth2 server location port: [80]: ';
            $authenticationServerPort = SetupTools::getInput("80");
            echo PHP_EOL;

            echo 'Write the oauth2 token introspection endpoint: [/validate]: ';
            $authenticationEndpoint = SetupTools::getInput("/validate");
            echo PHP_EOL;

            echo 'Write the oauth2 token refresh endpoint: [/refresh]: ';
            $refreshEndpoint = SetupTools::getInput("/refresh");
            echo PHP_EOL;

            echo 'Write the application client: []: ';
            $authenticationClient = SetupTools::getInput("");
            echo PHP_EOL;

            echo 'Write the application secret: []: ';
            $authenticationSecret = SetupTools::getInput("");
            echo PHP_EOL;

            $configurer->changeGroup('fony');
            $configurer->setField('fony.auth_method', 'Oauth');
            $configurer->setField('fony.auth_url', $authenticationServer);
            $configurer->setField('fony.auth_url_port', $authenticationServerPort);
            $configurer->setField('fony.auth_refresh', $refreshEndpoint);
            $configurer->setField('fony.auth_validate', $authenticationEndpoint);
            $configurer->setField('fony.user_client', $authenticationClient);
            $configurer->setField('fony.user_secret', $authenticationSecret);

            $configurer->export();
            //dummy needs to be created in order to initialize the configuration in the setup instance
            $dummy = ConfigurationUtils::getInstance($config);

            echo 'Build default database (y/Y/n/N): [y]: ';
            $build_default = SetupTools::getInput("y");
            $build_default = trim(strtolower($build_default));
            echo PHP_EOL;

            if ($build_default == "y") {
                //create the default values in the database
                $dbmaintenance = new DatabaseMaintenance();
                $dbmaintenance->create();
            }
            echo PHP_EOL;
        }

        $namespace = $organization_name_fixed . '\\' . $project_name_fixed;
        echo 'Write your application namespace: [' . $namespace . ']: ';
        $namespace = SetupTools::getInput($namespace);
        echo PHP_EOL;


        $builder = new PathBuilder($rootPath, $site_internal_path, $namespace, $config);

        if ($auth_app == "y") {
            //Create user
            $userMgmt = new UserCreation();
            $userMgmt->create($project_name_fixed, $client, $secret_key, $username, $password, $secret);

            //Creatre folder structure
            $builder->buildInitialTree(true);
        } else {
            $builder->buildInitialTree(false);
        }

        //Update the composer.json file
        $jsonString = file_get_contents(realpath(Factory::getComposerFile()));
        $data = json_decode($jsonString, true);
        if (!isset($data['autoload'])) {
            $data['autoload'] = array();
            $data['autoload']['psr-4'] = array();
            $data['autoload']['psr-4'][$namespace] = 'src/';
        }else{
            $data['autoload']['psr-4'] = array();
            $data['autoload']['psr-4'][$namespace] = 'src/';
        }
        if (isset($data['scripts'])) {
            if (isset($data['scripts']['setup-fony'])) {
                $data['scripts']['setup-fony'] = "echo 'You have already installed Fony'";
            }
            if ($auth_app == "y") {
                $data['scripts']['fony:update-user-password'] = "Geekcow\\Fony\\Installer\\UserPassword::updateCore";
                $data['scripts']['fony:recreate-database'] = "Geekcow\\Fony\\Installer\\DatabaseAdmin::recreateDatabase";
            }else{
                $data['scripts']['fony:create-model'] = "Geekcow\\Fony\\Installer\\DatabaseAdmin::createModel";
            }
            $newJsonString = json_encode($data, JSON_PRETTY_PRINT);
            file_put_contents(realpath(Factory::getComposerFile()), $newJsonString);
        }

        //var_dump($event->getArguments());
        echo PHP_EOL;
        echo 'DONE - Have fun using Fony-PHP. Please submit issues to: https://github.com/oleche/fony/issues';
        echo PHP_EOL;
        echo 'For documentation go to: https://github.com/oleche/fony/wiki';
        echo PHP_EOL;
        echo 'Please support if you like to: https://ko-fi.com/geekcow';
        exit();
    }
}
