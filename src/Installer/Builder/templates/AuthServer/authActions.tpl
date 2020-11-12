      case 'user':
        $this->core_action = new UserController($this->config_file);
        $this->core_action->setRequest($request);
        break;
      case 'client':
        $this->core_action = new GenericController($this->config_file);
        $this->core_action->setRequest($request);
        $this->core_action->setModel(new ApiClient());
        break;
      case 'scope':
        $this->core_action = new GenericController($this->config_file);
        $this->core_action->setRequest($request);
        $this->core_action->setModel(new ApiScope());
        break;
      case 'authenticate':
        $this->core_action = new AuthController($this->config_file);
        $this->core_action->setRequest($request);
        break;
      case 'validate':
        $this->core_action = new ValidateController($this->config_file);
        $this->core_action->setRequest($request);
        break;
