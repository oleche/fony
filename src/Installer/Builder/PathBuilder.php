<?php
namespace Geekcow\Fony\Installer\Builder;

class PathBuilder{

  public function __construct(){

  }

  public function buildInitialTree(){

  }

  private function buildBaseTree(){

  }

  private function buildHtaccess(){
    $file = file_get_contents('templates/htaccess.tpl');
    $file = str_replace("{USERNAME}", $USER, $file);
    file_put_contents($filename,$file);
  }
}
?>
