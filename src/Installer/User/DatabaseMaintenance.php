<?php

namespace Geekcow\Fony\Installer\User;

use Geekcow\FonyAuth\Model\ApiScope;
use Geekcow\FonyAuth\Model\ApiUserType;
use Geekcow\FonyCore\CoreModel\ApiAssetType;
use Geekcow\FonyCore\CoreModel\ApiFieldType;
use Geekcow\FonyCore\CoreModel\ApiForm;

class DatabaseMaintenance
{
    private $user_type;
    private const DEFAULT_USER_TYPES = array(
        "1" => array("id"=> 1, "name" => "system", "priority" => 10, "scope" => "system,administrator"),
        "2" => array("id"=> 2, "name" => "administrator", "priority" => 5, "scope" => "visitor,administrator"),
        "3" => array("id"=> 3, "name" => "user", "priority" => 1, "scope" => "visitor,user")
    );

    private $scope;
    private const DEFAULT_SCOPES = array(
        "system" => array("level" => 0, "priority" => 1),
        "administrator" => array("level" => 1, "priority" => 1),
        "visitor" => array("level" => 2, "priority" => 1),
        "user" => array("level" => 2, "priority" => 2)
    );

    private $field_type;
    private const DEFAULT_FIELD_TYPES = array(
        "1" => array("name" => 'string', "regex" => '/^.{1,1500}$/'),
        "2" => array("name" => 'integer', "regex" => '/^[0-9]+$/'),
        "3" => array("name" => 'float', "regex" => '!\\d+(?:\\.\\d+)?!'),
        "4" => array("name" => 'email', "regex" => '/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+\\.[a-zA-Z]{2,4}/'),
        "5" => array("name" => 'password', "regex" => '/^([0-9A-Za-z@.]{4,14})$/'),
        "6" => array(
            "name" => 'url',
            "regex" => '#((http|https|ftp)://(\\S*?\\.\\S*?))(\\s|\\;|\\)|\\]|\\[|\\{|\\}|,|\\"|\'\'|:|\\<|$|\\.\\s)#ie'
        ),
        "7" => array("name" => 'MD5', "regex" => '/^[a-f0-9]{32}$/i'),
        "8" => array("name" => 'username', "regex" => '/^[a-z0-9_-]{3,16}$/'),
        "9" => array("name" => 'boolean', "regex" => '/^[1|0]$/'),
        "10" => array("name" => 'date', "regex" => '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/'),
        "11" => array("name" => 'string and empty', "regex" => '/^$|^.{1,1500}$/'),
        "12" => array("name" => 'grant_type', "regex" => '/^(password|client_credentials|refresh_token)$/')
    );

    private const DEFAULT_API_FORMS = array(
        "1" => array(
            "endpoint" => '/api-forms',
            "field" => 'endpoint',
            "id_type" => 1,
            "sample" => '/url/:id/verb',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "2" => array(
            "endpoint" => '/api-forms',
            "field" => 'field',
            "id_type" => 1,
            "sample" => '',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "3" => array(
            "endpoint" => '/api-forms',
            "field" => 'id_type',
            "id_type" => 2,
            "sample" => 'Go to https://github.com/oleche/fony-core/wiki/Api-Form#field-types for more information',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "4" => array(
            "endpoint" => '/api-forms',
            "field" => 'sample',
            "id_type" => 1,
            "sample" => 'A sample of the field entry',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "5" => array(
            "endpoint" => '/api-forms',
            "field" => 'internal',
            "id_type" => 9,
            "sample" => 'SET AS VALUE 0',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "6" => array(
            "endpoint" => '/api-forms',
            "field" => 'required',
            "id_type" => 9,
            "sample" => 'If its required set as 1 otherwise 0',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "7" => array(
            "endpoint" => '/api-forms',
            "field" => 'blank',
            "id_type" => 9,
            "sample" => 'If it does not to be empty set 1 otherwise 0' ,
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "8" => array(
            "endpoint" => '/api-forms',
            "field" => 'scopes',
            "id_type" => 1,
            "sample" => 'the scope the field will be used, the implementation is pending',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "9" => array(
            "endpoint" => '/api-forms',
            "field" => 'method',
            "id_type" => 1,
            "sample" => 'POST, PUT, GET, DELETE',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "10" => array(
            "endpoint" => '/api-forms/:id',
            "field" => 'endpoint',
            "id_type" => 1,
            "sample" => '/url/:id/verb',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'PUT'
        ),
        "11" => array(
            "endpoint" => '/api-forms/:id',
            "field" => 'field',
            "id_type" => 1,
            "sample" => '',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'PUT'
        ),
        "12" => array(
            "endpoint" => '/api-forms/:id',
            "field" => 'id_type',
            "id_type" => 2,
            "sample" => 'Go to https://github.com/oleche/fony-core/wiki/Api-Form#field-types for more information',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'PUT'
        ),
        "13" => array(
            "endpoint" => '/api-forms/:id',
            "field" => 'sample',
            "id_type" => 1,
            "sample" => 'A sample of the field entry',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'PUT'
        ),
        "14" => array(
            "endpoint" => '/api-forms/:id',
            "field" => 'internal',
            "id_type" => 9,
            "sample" => 'SET AS VALUE 0',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'PUT'
        ),
        "15" => array(
            "endpoint" => '/api-forms/:id',
            "field" => 'required',
            "id_type" => 9,
            "sample" => 'If its required set as 1 otherwise 0',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'PUT'
        ),
        "16" => array(
            "endpoint" => '/api-forms/:id',
            "field" => 'blank',
            "id_type" => 9,
            "sample" => 'If it does not to be empty set 1 otherwise 0' ,
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'PUT'
        ),
        "17" => array(
            "endpoint" => '/api-forms/:id',
            "field" => 'scopes',
            "id_type" => 1,
            "sample" => 'the scope the field will be used, the implementation is pending',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'PUT'
        ),
        "18" => array(
            "endpoint" => '/api-forms/:id',
            "field" => 'method',
            "id_type" => 1,
            "sample" => 'POST, PUT, GET, DELETE',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'PUT'
        )
    );

    private $form;
    private const DEFAULT_FORMS = array(
        "19" => array(
            "endpoint" => 'v1/authenticate',
            "field" => 'username',
            "id_type" => 4,
            "sample" => '',
            "internal" => 0,
            "required" => 0,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "20" => array(
            "endpoint" => 'v1/authenticate',
            "field" => 'password',
            "id_type" => 1,
            "sample" => '',
            "internal" => 0,
            "required" => 0,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "21" => array(
            "endpoint" => '/user',
            "field" => 'name',
            "id_type" => 1,
            "sample" => '',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "22" => array(
            "endpoint" => '/user',
            "field" => 'lastname',
            "id_type" => 11,
            "sample" => '',
            "internal" => 0,
            "required" => 1,
            "blank" => 0,
            "scopes" => '',
            "method" => 'POST'
        ),
        "23" => array(
            "endpoint" => '/user',
            "field" => 'type',
            "id_type" => 2,
            "sample" => '',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "24" => array(
            "endpoint" => '/user',
            "field" => 'email',
            "id_type" => 4,
            "sample" => '',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "25" => array(
            "endpoint" => '/user',
            "field" => 'phone',
            "id_type" => 1,
            "sample" => '',
            "internal" => 0,
            "required" => 0,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "26" => array(
            "endpoint" => '/user',
            "field" => 'password',
            "id_type" => 1,
            "sample" => '',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "27" => array(
            "endpoint" => '/user',
            "field" => 'fbid',
            "id_type" => 1,
            "sample" => '',
            "internal" => 0,
            "required" => 0,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "28" => array(
            "endpoint" => '/user',
            "field" => 'googleid',
            "id_type" => 1,
            "sample" => '',
            "internal" => 0,
            "required" => 0,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "29" => array(
            "endpoint" => '/user',
            "field" => 'is_developer',
            "id_type" => 9,
            "sample" => '1 or 0',
            "internal" => 0,
            "required" => 0,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "30" => array(
            "endpoint" => '/user/:id',
            "field" => 'name',
            "id_type" => 1,
            "sample" => '',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "sample" => '',
            "scopes" => '',
            "method" => 'PUT'
        ),
        "31" => array(
            "endpoint" => '/user/:id',
            "field" => 'lastname',
            "id_type" => 1,
            "sample" => '',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'PUT'
        ),
        "32" => array(
            "endpoint" => '/user/:id',
            "field" => 'phone',
            "id_type" => 1,
            "sample" => '',
            "internal" => 0,
            "required" => 0,
            "blank" => 1,
            "scopes" => '',
            "method" => 'PUT'
        ),
        "33" => array(
            "endpoint" => '/user/:id/password',
            "field" => 'old_password',
            "id_type" => 5,
            "sample" => '',
            "internal" => 0,
            "required" => 0,
            "blank" => 1,
            "scopes" => '',
            "method" => 'PUT'
        ),
        "34" => array(
            "endpoint" => '/user/:id/password',
            "field" => 'password',
            "id_type" => 5,
            "sample" => '',
            "internal" => 0,
            "required" => 0,
            "blank" => 1,
            "scopes" => '',
            "method" => 'PUT'
        ),
        "35" => array(
            "endpoint" => 'v1/validate',
            "field" => 'token',
            "id_type" => 1,
            "sample" => '',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "36" => array(
            "endpoint" => 'v1/authenticate',
            "field" => 'grant_type',
            "id_type" => 12,
            "sample" => '',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "37" => array(
            "endpoint" => 'v1/authenticate',
            "field" => 'scope',
            "id_type" => 1,
            "sample" => '',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "38" => array(
            "endpoint" => 'v1/authenticate/refresh',
            "field" => 'grant_type',
            "id_type" => 12,
            "sample" => '',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "39" => array(
            "endpoint" => 'v1/authenticate/refresh',
            "field" => 'refresh_token',
            "id_type" => 1,
            "sample" => '',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "40" => array(
            "endpoint" => 'v1/authenticate/refresh',
            "field" => 'refresh_token',
            "id_type" => 1,
            "sample" => '',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        ),
        "41" => array(
            "endpoint" => '/client',
            "field" => 'name',
            "id_type" => 1,
            "sample" => '',
            "internal" => 0,
            "required" => 1,
            "blank" => 1,
            "scopes" => '',
            "method" => 'POST'
        )
    );

    private $asset_type;
    private const DEFAULT_ASSET_TYPES = array(
        "1" => array(
            "name" => 'JPG Image File',
            "format" => '.jpg',
            "max_size" => 5000000,
            "max_dimensions" => '300|300',
            "mime" => 'image/jpeg|image/pjpeg|',
            "type" => 'image'
        ),
        "2" => array(
            "name" => 'MP3 Audio File',
            "format" => '.mp3',
            "max_size" => 50000000,
            "max_dimensions" => '60',
            "mime" => 'audio/mpeg|audio/x-mpeg|audio/mp3|audio/x-mp3|audio/mpeg3|audio/x-mpeg3|audio/mpg|audio/x-mpg|audio/x-mpegaudio|',
            "type" => 'audio'
        ),
        "4" => array(
            "name" => 'WAV Audio File',
            "format" => '.wav',
            "max_size" => 50000000,
            "max_dimensions" => '60',
            "mime" => 'audio/wav|audio/x-wav',
            "type" => 'audio'
        ),
        "3" => array(
            "name" => 'OGG Audio File',
            "format" => '.ogg',
            "max_size" => 50000000,
            "max_dimensions" => '60',
            "mime" => 'audio/ogg|application/ogg',
            "type" => 'audio'
        ),
        "5" => array(
            "name" => 'PNG Image Type',
            "format" => '.png',
            "max_size" => 5000000,
            "max_dimensions" => '300|300',
            "mime" => 'image/png',
            "type" => 'image'
        ),
        "6" => array(
            "name" => 'BMP Image Type',
            "format" => '.bmp',
            "max_size" => 5000000,
            "max_dimensions" => '300|300',
            "mime" => 'image/bmp|image/x-windows-bmp',
            "type" => 'image'
        ),
        "7" => array(
            "name" => 'GIF Image Type',
            "format" => '.gif',
            "max_size" => 5000000,
            "max_dimensions" => '300|300',
            "mime" => 'image/gif',
            "type" => 'image'
        )
    );

    public function __construct()
    {
        $this->user_type = new ApiUserType();
        $this->scope = new ApiScope();
        $this->field_type = new ApiFieldType();
        $this->form = new ApiForm();
        $this->asset_type = new ApiAssetType();
    }

    public function create()
    {
        $this->createFieldTypes();
        echo '.';
        $this->createApiForms();
        echo '.';
        $this->createAssetTypes();
        echo '.';
    }

    public function createAuthenticated()
    {
        $this->createUserTypes();
        echo '.';
        $this->createFieldTypes();
        echo '.';
        $this->createApiForms();
        echo '.';
        $this->createForms();
        echo '.';
        $this->createAssetTypes();
        echo '.';
        $this->createScopes();
        echo '.';
    }

    private function createUserTypes()
    {
        foreach (self::DEFAULT_USER_TYPES as $key => $value) {
            $this->user_type = new ApiUserType();
            foreach ($value as $k => $v) {
                $this->user_type->columns[$k] = $v;
            }
            $this->user_type->insert();
        }
    }

    private function createFieldTypes()
    {
        foreach (self::DEFAULT_FIELD_TYPES as $key => $value) {
            $this->field_type = new ApiFieldType();
            $this->field_type->columns['id'] = $key;
            foreach ($value as $k => $v) {
                $this->field_type->columns[$k] = $v;
            }
            $this->field_type->insert();
        }
    }

    private function createApiForms()
    {
        foreach (self::DEFAULT_API_FORMS as $key => $value) {
            $this->form = new ApiForm();
            $this->form->columns['id'] = $key;
            foreach ($value as $k => $v) {
                $this->form->columns[$k] = $v;
            }
            $this->form->insert();
        }
    }

    private function createForms()
    {
        foreach (self::DEFAULT_FORMS as $key => $value) {
            $this->form = new ApiForm();
            $this->form->columns['id'] = $key;
            foreach ($value as $k => $v) {
                $this->form->columns[$k] = $v;
            }
            $this->form->insert();
        }
    }

    private function createAssetTypes()
    {
        foreach (self::DEFAULT_ASSET_TYPES as $key => $value) {
            $this->form = new ApiAssetType();
            $this->form->columns['id'] = $key;
            foreach ($value as $k => $v) {
                $this->form->columns[$k] = $v;
            }
            $this->form->insert();
        }
    }

    private function createScopes()
    {
        foreach (self::DEFAULT_SCOPES as $key => $value) {
            $this->scope = new ApiScope();
            $this->scope->columns['name'] = $key;
            foreach ($value as $k => $v) {
                $this->scope->columns[$k] = $v;
            }
            $this->scope->insert();
        }
    }
}
