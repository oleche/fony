      case 'user':
        $this->action = new UserController($this->config_file);
        $this->action->setRequest($request);
        break;
      case 'client':
        $this->action = new GenericController($this->config_file);
        $this->action->setRequest($request);
        $this->action->setModel(new ApiClient());
        break;
      case 'scope':
        $this->action = new GenericController($this->config_file);
        $this->action->setRequest($request);
        $this->action->setModel(new ApiScope());
        break;
      case 'authenticate':
        $this->action = new AuthController($this->config_file);
        $this->action->setRequest($request);
        break;
      case 'validate':
        $this->action = new ValidateController($this->config_file);
        $this->action->setRequest($request);
        break;
