    case 'welcome':
        $this->action = new WelcomeController();
        $this->action->setRequest($this->request);
        break;