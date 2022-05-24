<?php

namespace App\Controllers;

use App\Models\AuthModel;
use App\Controllers\BaseController;


class Auth extends BaseController
{
    public $db;
    public $user;

//make constructor for easier work with table data
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->user = new AuthModel();
    }

//default method
    public function index()
    {
        return view('Views/login');
    }

    public function login()
    {
        //use validation rules
        if ($this->validate([
            'password' => 'required|min_length[2]',
            'email' => 'required|valid_email',
        ])) {
            //with method request, we call the getPost method which returns values from input
            $email = $this->request->getPost('email');
            //method will pull from $_REQUEST, so will return any data from $_GET, $POST, or $_COOKIE.
            $password = $this->request->getVar('password');
            //key is a name of input, $email is an input value, method first returning an array of results
            $user = $this->user->where('email', $email)->where('password', md5($password))->first();
            if (!$user) {
                $this->validator->setError('email', 'Bad password');
            } else {
                //remove user password from session
                unset($user['password']);
                //add user data to the session
                $this->session->set('user', $user);
                //The match expression which is identity check of a value
                $route = match ($user['type']) {
                    'director' => '/director/index',
                    'teacher' => '/teacher/index',
                    'student' => '/student/index',
                };

                return redirect()->to(base_url($route));
            }
        }

        return view('login', ['errors' => $this->validator->listErrors()]);
    }

    public function log_out()
    {
        $this->session->remove('user');
        return redirect()->to(base_url('/'));
    }

}
