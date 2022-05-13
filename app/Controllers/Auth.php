<?php

namespace App\Controllers;

use App\Models\AuthModel;
use App\Controllers\BaseController;

class Auth extends BaseController
{
    public $user;

//make constructor for easier work with table data
    public function __construct()
    {
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
            //with method request, we call the getPost method which is return post values from input
            $email = $this->request->getPost('email');
            //method will pull from $_REQUEST, so will return any data from $_GET, $POST, or $_COOKIE.
            $password = $this->request->getVar('password');
            //key is a name of input, $email is an input value, method first returning an array of results
            $user = $this->user-> where('email', $email)->where('password', md5($password))->first();
            if (!$user) {
                $this->validator->setError('email', 'Bad password');
            } else {
                //remove user password from session
                unset($user['password']);
                //add user data to the session
                $this->session->set('user', $user);
                switch ($user['type']) {
                    case 'director':
                        $route = '/director/index';
                        break;
                    case 'teacher':
                        $route = '/teacher/index';
                        break;
                    case 'student':
                        $route = '/student/index';
                        break;
                }

                return redirect()->to(base_url($route));
            }
        }

        return view('login', ['errors' => $this->validator->listErrors()]);
    }
}
