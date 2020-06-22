<?php
/* Auth Utils
 * Developed by OSCAR LECHE
 * V.1.0
 * DESCRIPTION: Authentication support for token generation and general authentication
 */
namespace Geekcow\Fony\Installer;

use Composer\Script\Event;
use Composer\Installer\PackageEvent;
use Composer\Factory;
use Composer\IO\NullIO;
use Geekcow\FonyCore\Utils\ConfigurationUtils;

class UserUpdate {


  public static function updateCore(Event $event){
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
    echo 'User credentials reconfiguration Tool' . PHP_EOL;
    echo '====================' . PHP_EOL;
    echo PHP_EOL;

    $vendorDir = dirname(realpath(Factory::getComposerFile()));
    echo 'Write the location of your fony configuration file: ['.$vendorDir.'/src/config/config.ini]: ';
    $config = UserUpdate::getInput($vendorDir . "/src/config/config.ini");
    echo PHP_EOL;

    if (file_exists($config)){
      $configuration = ConfigurationUtils::getInstance($config);

      echo 'Write the user email: [admin@test.com]: ';
      $username = UserUpdate::getInput("admin@test.com");
      echo PHP_EOL;

      $password = "";
      while ($password == ""){
        echo 'Write the new password: ';
        $password = UserUpdate::getInput();
      }
      echo PHP_EOL;

      $userMgmt = new UserUpdate();
      $userMgmt->updateUser($username, $password);

    } else {
      exit ('ERROR: Cannot continue, the configuration file does not exists');
    }


    //var_dump($event->getArguments());
  }

  public static function getInput($default = ""){
    $response = "";
    $stdin = fopen('php://stdin', 'r');
    $response = fgets($stdin);
    fclose($stdin);
    if (trim($response) == ""){
      $response = $default;
    }
    return $response;
  }
}

?>