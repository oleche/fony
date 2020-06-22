<?php
/* Fony Setup Tool
 * Developed by OSCAR LECHE
 * V.1.0
 * DESCRIPTION: Setup tool for Fony PHP
 */
namespace Geekcow\Fony\Installer\Tools;

class SetupTools {
  public static function getInput($default = ""){
    $response = "";
    $stdin = fopen('php://stdin', 'r');
    $response = fgets($stdin);
    fclose($stdin);
    if (trim($response) == ""){
      $response = $default;
    }
    return trim($response);
  }

  public static function dashesToCamelCase($string, $capitalizeFirstCharacter = false)
  {

      $str = str_replace('-', '', ucwords($string, '-'));
      $str = str_replace(' ', '', ucwords($str, ' '));
      $str = str_replace('.', '', ucwords($str, '.'));

      if (!$capitalizeFirstCharacter) {
          $str = lcfirst($str);
      }

      return $str;
  }
}
