<?php

namespace {PROJECTNAMESPACE};

use Geekcow\FonyCore\FonyRouter;
use {PROJECTNAMESPACE}\Helpers\Allow;
use {PROJECTNAMESPACE}\Controller\WelcomeController;

{CUSTOM_USES}

class Router extends FonyRouter{

    public function __construct($config_file)
    {
        parent::__construct($config_file);
        {CUSTOM_PRESTAGING}
    }

    public function prestageEndpoints($endpoint, $request)
    {
        parent::prestageEndpoints($endpoint, $request);

        {CUSTOM_ACTIONS}
    }

    {CUSTOM_ENDPOINTS}
}
