<?php
/* Fony Setup Tool
 * Developed by OSCAR LECHE
 * V.1.0
 * DESCRIPTION: Setup tool for Fony PHP
 */
namespace Geekcow\Fony\Installer;

define('MY_DOC_ROOT', __DIR__);

use Composer\Script\Event;
use Composer\Installer\PackageEvent;
use Composer\Factory;
use Composer\IO\NullIO;
use Geekcow\Fony\Installer\User\UserCreation;
use Geekcow\Fony\Installer\Builder\PathBuilder;
use Geekcow\Fony\Installer\User\DatabaseMaintenance;
use Geekcow\Fony\Installer\Tools\SetupTools;
use Geekcow\Fony\Installer\ConfigurationConfigurer;

class Setup {


  public static function init(Event $event){
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
    echo 'Path for your fony project: ['.$vendorDir.']: ';
    $rootPath = SetupTools::getInput($vendorDir);
    echo PHP_EOL;

    echo 'Path for your fony configuration file: ['.$vendorDir.'/src/config/config.ini]: ';
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

    echo 'PATH of your API: '.$site_url.'[/v1/]: ';
    $site_internal_path = SetupTools::getInput('/v1/');
    echo PHP_EOL;

    echo 'URL of your API Assets: [assets.test.com]: ';
    $file_url = SetupTools::getInput("assets.test.com");
    $configurer->changeGroup('fony');
    $configurer->setField('fony.file_url', $file_url);
    echo PHP_EOL;

    echo 'Internal path of your API assets: ['.$vendorDir.'/assets/]: ';
    $file_path = SetupTools::getInput($vendorDir.'/assets/');
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
    echo 'database: ['.$project_name_fixed.']: ';
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
    $secret = "";
    while ($secret == ""){
      echo 'Write the application secret word: ';
      $secret = SetupTools::getInput();
      $configurer->changeGroup('fony');
      $configurer->setField('fony.app_secret', $secret);
    }

    echo 'Write the administrator email: [admin@test.com]: ';
    $username = SetupTools::getInput("admin@test.com");
    echo PHP_EOL;

    $encodedUser = md5($username);
    $client = sha1($encodedUser.$username.date("Y-m-d H:i:s"));
		$secret_key = sha1($client.$secret);
    $configurer->changeGroup('fony');
    $configurer->setField('fony.user_client', $client);
    $configurer->setField('fony.user_secret', $secret_key);

    $configurer->export();

    $password = "";
    while ($password == ""){
      echo 'Write the administrator password: ';
      $password = SetupTools::getInput();
    }
    echo PHP_EOL;

    echo 'Build default database (y/Y/n/N): [y]: ';
    $build_default = SetupTools::getInput("y");
    $build_default = trim(strtolower($build_default));
    echo PHP_EOL;

    if ($build_default == "y"){
      //create the default values in the database
      $dbmaintenance = new DatabaseMaintenance();
      $dbmaintenance->create();
    }
    echo PHP_EOL;

    $namespace = $organization_name_fixed.'\\'.$project_name_fixed;
    echo 'Write your application namespace: ['.$namespace.']: ';
    $namespace = SetupTools::getInput($namespace);
    echo PHP_EOL;

    //Create user
    $userMgmt = new UserCreation();
    $userMgmt->create($client, $secret_key, $username, $password, $secret);

    //Creatre folder structure
    $builder = new PathBuilder($rootPath, $site_internal_path, $namespace, $config);
    $builder->buildInitialTree(false);

    //Update the composer.json file
    $jsonString = file_get_contents(realpath(Factory::getComposerFile()));
    $data = json_decode($jsonString, true);
    if (isset($data['scripts'])){
      if (isset($data['scripts']['setup-fony'])){
        $data['scripts']['setup-fony'] = "echo 'You have already installed Fony'";
      }
      $data['scripts']['fony:update-user-password'] = "Geekcow\\Fony\\Installer\\UserPassword::updateCore";
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

?>
