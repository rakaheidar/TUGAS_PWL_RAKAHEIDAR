<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
{
    public function profil()
    {
        $data = [
            'username' => session()->get('username'),
            'role' => session()->get('role'),
            'email' => session()->get('email'),
            'login_time' => session()->get('login_time'),
            'status' => 'Aktif'
        ];

        return view('v_profil', $data);
    }
}