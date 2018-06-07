<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $_loginInfo = [];

    public function __construct()
    {
        $this->_loginInfo = $_SERVER['userInfo'] ?? [];
    }
}
