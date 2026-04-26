<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Profil extends BaseController
{
    public function index()
    {
        $session = session();
        $data = [
            'username'   => $session->get('username'),
            'email'      => $session->get('email'),
            'role'       => $session->get('role'),
            'login_time' => $session->get('login_time'),
            'status'     => $session->get('isLoggedIn') ? 'Aktif' : 'Tidak Aktif'
        ];

        return view('v_profil', $data);
    }
}
