<?php

namespace controllers;

use Ubiquity\controllers\admin\UbiquityMyAdminBaseController;
use Ubiquity\controllers\auth\WithAuthTrait;
use Ubiquity\controllers\auth\AuthController;
use Ubiquity\utils\http\USession;
use Ubiquity\controllers\Startup;

class Admin extends UbiquityMyAdminBaseController
{
    use WithAuthTrait;
    protected function getAuthController(): AuthController
    {
        return new BasicAuthController();
    }

    public function isValid($action)
    {
        if (USession::exists('activeUser')) {
            $sRole = (USession::get('activeUser'))->getRole();
            if ($sRole === 'Admin') return true;
        }
        
        return false;
    }

    public function onInvalidControl()
    {
        Startup::forward('/_default');
    }
}
