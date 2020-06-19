<?php
namespace Geekcow\Fony\Installer\Builder;

class PathBuilder{
  private $rootPath;
  private $basePath;
  private $namespace;
  private $config;

  public function __construct($rootPath, $path, $namespace, $config){
    $this->rootPath = $rootPath;
    $this->basePath = $path;
    $this->namespace = $namespace;
    $this->config = $config;

    //Create root
    if (!is_dir($this->rootPath)) {
      // dir doesn't exist, make it
      mkdir($this->rootPath);
    }
    //Create src
    if (!is_dir($this->rootPath."/src")) {
      // dir doesn't exist, make it
      mkdir($this->rootPath."/src");
    }
  }

  public function buildInitialTree($demo = false){
    /*
    Initial tree:
    .htaccess
    api.php
    /str
    -> router.php
    -> .htaccess
    */
    $this->buildHtaccess();
    $this->buildApi();
    if ($demo){
      $this->buildRouter();
    }else{
      $this->buildBasicRouter();
    }
  }

  private function buildDemoTree(){

  }

  private function buildHtaccess(){
    $filename = $this->rootPath."/.htaccess";
    $file = file_get_contents(dirname(__FILE__) . 'templates/htaccess.tpl');
    $file = str_replace("{PROJECTBASEPATH}", $this->basePath, $file);
    file_put_contents($filename,$file);
  }

  private function buildApi(){
    $filename = $this->rootPath."/api.php";
    $file = file_get_contents(dirname(__FILE__) . 'templates/api.tpl');
    $file = str_replace("{PROJECTNAMESPACE}", $this->namespace, $file);
    $file = str_replace("{PROJECTCONFIGFILE}", $this->config, $file);
    file_put_contents($filename,$file);
  }

  private function buildBasicRouter(){
    $filename = $this->rootPath."/src/router.php";
    $file = file_get_contents(dirname(__FILE__) . 'templates/router.tpl');
    $file = str_replace("{PROJECTNAMESPACE}", $this->namespace, $file);
    $file = str_replace("{CUSTOM_ACTIONS}", '', $file);
    $file = str_replace("{CUSTOM_ENDPOINTS}", '', $file);
    file_put_contents($filename,$file);
  }

  private function buildRouter(){

  }
}
?>
