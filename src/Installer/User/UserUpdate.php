<?php

namespace Geekcow\Fony\Installer\User;
use Geekcow\FonyCore\CoreModel\ApiUser;

class UserUpdate{
  private $user;

  public function __construct(){
    $this->user = new ApiUser();
  }

  public function updateUser($email, $password){
    $username = md5($email);
    $password = sha1($password);
    if (!$this->user->fetch_id(array('username'=>$username))){
      exit ('ERROR: User does not exist');
      return false;
    }
    $this->user->columns['password'] = $password;
    $this->user->columns['type'] = $this->user->columns['type']['id'];
    $this->user->columns['updated_at'] = date("Y-m-d H:i:s");

    if (!$this->user->update()){
      exit ('ERROR: Could not change password');
      return false;
    }
    echo 'Password changed';
    return true;
  }
}

?>
