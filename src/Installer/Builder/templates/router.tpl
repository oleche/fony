<?php
namespace {PROJECTNAMESPACE};

use Geekcow\FonyCore\FonyApi;
use Geekcow\FonyCore\Helpers\AllowCore;
{CUSTOM_USES}

class Router extends FonyApi{
  public function __construct($request, $origin, $config_file) {
    {CUSTOM_PRESTAGING}

    parent::__construct($request, $origin, $config_file);


    {CUSTOM_ACTIONS}
    $this->setAllowedCoreRoles(AllowCore::ADMINISTRATOR());
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

?>
