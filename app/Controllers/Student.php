<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Student extends BaseController
{
    public function __construct()
    {
        //take from session user key and compare it with array element
        if(session()->user['type'] != 'student'){
            dd((array)'can enter only a student');
        }
    }

    public function index()
    {
        return view('users/student');
    }
}
