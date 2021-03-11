<?php

namespace Geekcow\Fony\Installer\Builder;

class PathBuilder
{
    private $rootPath;
    private $basePath;
    private $namespace;
    private $config;

    public function __construct($rootPath, $path, $namespace, $config)
    {
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
        if (!is_dir($this->rootPath . "/src")) {
            // dir doesn't exist, make it
            mkdir($this->rootPath . "/src");
        }
        //create src/Helpers
        //Create src
        if (!is_dir($this->rootPath . "/src/Helpers")) {
            // dir doesn't exist, make it
            mkdir($this->rootPath . "/src/Helpers");
        }
    }

    public function buildInitialTree($auth = false)
    {
        /*
        Initial tree:
        .htaccess
        api.php
        /src
        -> router.php
        -> .htaccess
        */
        $this->buildHtaccess();
        $this->buildApi();
        if (!$auth) {
            $this->buildBasicRouter();
        } else {
            $this->buildAuthenticationRouter();
        }
        $this->buildInternalHtaccess();
        $this->buildAllow();
    }

    private function buildDemoTree()
    {
    }

    private function buildHtaccess()
    {
        $filename = $this->rootPath . "/.htaccess";
        $file = file_get_contents(dirname(__FILE__) . '/templates/htaccess.tpl');
        $file = str_replace("{PROJECTBASEPATH}", $this->basePath, $file);
        file_put_contents($filename, $file);
    }

    private function buildApi()
    {
        $filename = $this->rootPath . "/api.php";
        $file = file_get_contents(dirname(__FILE__) . '/templates/api.tpl');
        $file = str_replace("{PROJECTNAMESPACE}", $this->namespace, $file);
        $file = str_replace("{PROJECTCONFIGFILE}", $this->config, $file);
        file_put_contents($filename, $file);
    }

    private function buildBasicRouter()
    {
        $filename = $this->rootPath . "/src/router.php";
        $file = file_get_contents(dirname(__FILE__) . '/templates/router.tpl');
        $file = str_replace("{PROJECTNAMESPACE}", $this->namespace, $file);
        $file = str_replace("{CUSTOM_USES}", '', $file);
        $file = str_replace("{CUSTOM_ACTIONS}", '', $file);
        $file = str_replace("{CUSTOM_ENDPOINTS}", '', $file);
        $file = str_replace("{CUSTOM_PRESTAGING}", '', $file);
        file_put_contents($filename, $file);
    }

    private function buildAllow()
    {
        $filename = $this->rootPath . "/src/Helpers/Allow.php";
        $file = file_get_contents(dirname(__FILE__) . '/templates/helpers/allow.tpl');
        $file = str_replace("{PROJECTNAMESPACE}", $this->namespace, $file);
        file_put_contents($filename, $file);
    }

    private function buildAuthenticationRouter()
    {
        $filename = $this->rootPath . "/src/router.php";
        $file = file_get_contents(dirname(__FILE__) . '/templates/router.tpl');
        $actions = file_get_contents(dirname(__FILE__) . '/templates/AuthServer/authActions.tpl');
        $actions = 'switch($this->endpoint){' . PHP_EOL . $actions . PHP_EOL . '}';
        $endpoints = file_get_contents(dirname(__FILE__) . '/templates/AuthServer/authEndpoints.tpl');
        $prestaging = file_get_contents(dirname(__FILE__) . '/templates/AuthServer/authPrestaging.tpl');
        $uses = file_get_contents(dirname(__FILE__) . '/templates/AuthServer/authUses.tpl');
        $file = str_replace("{PROJECTNAMESPACE}", $this->namespace, $file);
        $file = str_replace("{CUSTOM_USES}", $uses, $file);
        $file = str_replace("{CUSTOM_ACTIONS}", $actions, $file);
        $file = str_replace("{CUSTOM_ENDPOINTS}", $endpoints, $file);
        $file = str_replace("{CUSTOM_PRESTAGING}", $prestaging, $file);
        file_put_contents($filename, $file);
    }

    private function buildInternalHtaccess()
    {
        $filename = $this->rootPath . "/src/.htaccess";
        $file = file_get_contents(dirname(__FILE__) . '/templates/htaccess-internal.tpl');
        file_put_contents($filename, $file);
    }

    private function buildRouter()
    {
    }
}
