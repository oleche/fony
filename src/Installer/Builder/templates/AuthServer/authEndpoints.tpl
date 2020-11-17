/**
 * Executes the authentication endpoint.
 *
 * @return JSON Authenticated response with token
 *
 */
protected function authenticate(){
  switch ($this->method) {
   case 'POST':
     $this->core_action->doPost($_SERVER['HTTP_Authorization'], $_POST, $this->verb);
     $this->response_code = $this->core_action->response['code'];
     return $this->core_action->response;
     break;
   case 'OPTIONS':
     exit(0);
     break;
   default:
     $this->response_code = 405;
     return "Invalid method";
     break;
  }
}

/**
* Executes the token validation endpoint.
*
* @return JSON Authenticated response with token
*
*/
protected function validate(){
switch ($this->method) {
 case 'POST':
   $this->core_action->doPost($_SERVER['HTTP_Authorization'], $_POST);
   $this->response_code = $this->core_action->response['code'];
   return $this->core_action->response;
   break;
 case 'OPTIONS':
   exit(0);
   break;
 default:
   $this->response_code = 405;
   return "Invalid method";
   break;
}
}

/**
 * Executes the user endpoint.
 *
 * @return JSON User response
 *
 */
protected function user(){
  if ($this->exclude_core_actions){
    $this->response_code = 405;
    return "Invalid method";
  }
  return $this->_executesCall(true);
}

/**
 * Executes the client endpoint.
 *
 * @return JSON Client response
 *
 */
protected function client(){
  if ($this->exclude_core_actions){
    $this->response_code = 405;
    return "Invalid method";
  }
  return $this->_executesCall(true);
}

/**
 * Executes the scope endpoint.
 *
 * @return JSON Scope response
 *
 */
protected function scope(){
  if ($this->exclude_core_actions){
    $this->response_code = 405;
    return "Invalid method";
  }
  return $this->_executesCall(true);
}
