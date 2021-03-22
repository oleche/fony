<?php

namespace Geekcow\Fony\Installer\User;

use Geekcow\FonyAuth\Model\ApiClient;
use Geekcow\FonyAuth\Model\ApiClientScope;
use Geekcow\FonyAuth\Model\ApiUser;
use Geekcow\FonyAuth\Model\ApiUserAsoc;
use Geekcow\FonyAuth\Model\ApiUserType;

class UserCreation
{
    private $user;
    private $user_type;
    private $api_client;
    private $api_client_scopes;
    private $api_user_asoc;

    public function __construct()
    {
        $this->user = new ApiUser();
        $this->user_type = new ApiUserType();
        $this->api_client = new ApiClient();
        $this->api_client_scopes = new ApiClientScope();
        $this->api_user_asoc = new ApiUserAsoc();
    }

    public function create($project, $client, $key, $email, $password, $secret)
    {
        $system_mail = "system@".$project;
        if ($this->createUser($system_mail, $password)){
            $system_username = md5($system_mail);
            if ($this->createUser($email, $password, 2)) {
                $username = md5($email);
                $the_client = sha1($username . $email . date("Y-m-d H:i:s"));
                if ($this->createPublicApi($client, $key, $system_mail, $system_username)) {
                    if ($this->associateClient($system_username, $client) &&
                        $this->associateClient($username, $client)) {
                        if ($this->createApi($the_client, $email, $username, $secret, array('administrator','visitor','system'))) {
                            if ($this->associateClient($username, $the_client)) {
                                return true;
                            }
                        }
                    }
                }
            }
        }
    }

    private function createUser($email, $password, $type = 1)
    {
        $username = md5($email);
        $password = sha1($password);
        $this->user->columns['username'] = strtolower($username);
        $this->user->columns['name'] = 'administrator';
        $this->user->columns['lastname'] = "";
        $this->user->columns['email'] = $email;
        $this->user->columns['phone'] = "";
        $this->user->columns['type'] = $type;
        $this->user->columns['avatar'] = "";
        $this->user->columns['avatar_path'] = "";
        $this->user->columns['password'] = (isset($password)) ? $password : "";
        $this->user->columns['enabled'] = 1;
        $this->user->columns['verified'] = 1;
        $this->user->columns['verification'] = "";
        $this->user->columns['created_at'] = date("Y-m-d H:i:s");
        $this->user->columns['updated_at'] = date("Y-m-d H:i:s");

        $id = $this->user->insert();
        if (is_numeric($id)) {
            echo '.';
            return true;
        }
        return false;
    }

    private function associateClient($userid, $token)
    {
        $this->api_user_asoc->columns['client_id'] = $token;
        $this->api_user_asoc->columns['username'] = $userid;
        $idx = $this->api_user_asoc->insert();
        echo '.';
    }

    private function createPublicApi($base_client, $base_secret, $email, $username)
    {
        $this->api_client = new ApiClient();
        $this->api_client->columns['client_id'] = $base_client;
        $this->api_client->columns['client_secret'] = $base_secret;
        $this->api_client->columns['email'] = $email;
        $this->api_client->columns['name'] = 'PUBLIC USER AUTHENTICATION CLIENT';
        $this->api_client->columns['user_id'] = $username;
        $this->api_client->columns['enabled'] = 1;
        $this->api_client->columns['asoc'] = 0;
        $this->api_client->columns['created_at'] = date("Y-m-d H:i:s");
        $this->api_client->columns['updated_at'] = date("Y-m-d H:i:s");

        $id = $this->api_client->insert();
        if (!is_numeric($id)) {
            return false;
        }

        $this->api_client_scopes->columns['id_client'] = $base_client;
        $this->api_client_scopes->columns['id_scope'] = 'visitor';
        $idx = $this->api_client_scopes->insert();
        if (!is_numeric($idx)) {
            return false;
        }

        return true;
    }

    private function createApi($client, $email, $username, $secret, $scopes = array())
    {
        $this->api_client = new ApiClient();
        $secret = sha1($client . $secret);
        $this->api_client->columns['client_id'] = $client;
        $this->api_client->columns['client_secret'] = $secret;
        $this->api_client->columns['email'] = $email;
        $this->api_client->columns['name'] = $email;
        $this->api_client->columns['user_id'] = $username;
        $this->api_client->columns['enabled'] = 1;
        $this->api_client->columns['asoc'] = 1;
        $this->api_client->columns['created_at'] = date("Y-m-d H:i:s");
        $this->api_client->columns['updated_at'] = date("Y-m-d H:i:s");

        $id = $this->api_client->insert();
        if (!is_numeric($id)) {
            return false;
        }

        $retval = true;
        foreach ($scopes as $scope) {
            $this->api_client_scopes = new ApiClientScope();
            $this->api_client_scopes->columns['id_client'] = $client;
            $this->api_client_scopes->columns['id_scope'] = $scope;
            $idx = $this->api_client_scopes->insert();
            if (is_numeric($idx)) {
                $retval = ($retval && true);
            }else{
                $retval = ($retval && false);
            }
            echo '.';
        }

        return $retval;
    }
}
