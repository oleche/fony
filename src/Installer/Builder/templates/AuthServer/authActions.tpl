    case 'user':
        $this->action = new UserController();
        $this->action->setRequest($this->request);
        $this->setAllowedRoles(Allow::MANAGEUSER());
        break;
    case 'client':
        $this->action = new GenericActionController();
        $this->action->setRequest($this->request);
        $this->action->setPostAction(new ClientPostActions());
        $this->action->setModel(new ApiClient());
        $this->action->setFilter(['password', 'user_id', 'asoc', 'email']);
        $this->action->setUsernameKey('user_id');
        $this->setAllowedRoles(Allow::MANAGEUSER());
        break;
    case 'scope':
        $this->action = new GenericController();
        $this->action->setRequest($this->request);
        $this->action->setModel(new ApiScope());
        $this->setAllowedRoles(Allow::ADMINISTRATOR());
        break;
    case 'authenticate':
        $this->action = new AuthController();
        $this->action->setRequest($this->request);
        $this->setAllowedRoles(Allow::ADMINISTRATOR());
        break;
    case 'validate':
        $this->action = new ValidateController();
        $this->action->setRequest($this->request);
        $this->setAllowedRoles(Allow::ADMINISTRATOR());
        break;
