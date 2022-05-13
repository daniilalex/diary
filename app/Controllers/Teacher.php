<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Teacher extends BaseController
{
    public function __construct()
    {
        //take from session user key and compare it with array element
        if(session()->user['type'] != 'teacher'){
            dd('can enter only the teacher');
        }
    }

    public function index()
    {
        return view('users/teacher');
    }
}
