<?php

namespace {PROJECTNAMESPACE};

use Geekcow\FonyCore\Controller\ApiMethods;
use Geekcow\FonyCore\Controller\BaseController;

class WelcomeController extends BaseController implements ApiMethods
{

    public function doPOST()
    {
        $this->response['code'] = 501;
        $this->response['msg'] = "Not Implemented";
    }

    public function doGET()
    {
        $this->response = [];
        $this->response['message'] = "WELCOME TO FONY PHP";
    }

    public function doPUT()
    {
        $this->response['code'] = 501;
        $this->response['msg'] = "Not Implemented";
    }

    public function doDELETE()
    {
        $this->response['code'] = 501;
        $this->response['msg'] = "Not Implemented";
    }
}