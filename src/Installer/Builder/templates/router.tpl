<?php

namespace {PROJECTNAMESPACE};

use Geekcow\FonyCore\FonyApi;
use {PROJECTNAMESPACE}\Helpers\Allow;
{CUSTOM_USES}

class Router extends FonyApi{

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

    //WELCOME MESSAGE
    protected function welcome() {
      if ($this->method == 'GET') {
        return "WELCOME TO FONY PHP";
      } else {
        return "Invalid Method";
      }
    }

    {CUSTOM_ENDPOINTS}
}
