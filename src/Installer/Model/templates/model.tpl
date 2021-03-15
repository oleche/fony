<?php

namespace {PROJECTNAMESPACE};

use Geekcow\Dbcore\Entity;
use Geekcow\FonyAuth\Utils\ConfigurationUtils;

class {MODELCLASSNAME} extends Entity
{
    private ${MODELNAME};

    public function __construct($config_file = MY_DOC_ROOT . "/src/config/config.ini")
    {
        $config = ConfigurationUtils::getInstance($config_file);
        $this->{MODELNAME} = [
            {MODELDEFINITION}
        ];
        parent::__construct($this->{MODELNAME}, get_class($this), $config->getFilename());
    }
}
