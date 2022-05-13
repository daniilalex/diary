<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Director extends BaseController
{
    public function __construct()
    {
        //take from session user key and compare it with array element
        if(session()->user['type'] != 'director'){
            dd((array)'can enter only the director');
        }
    }

    public function index()
    {
        return view('users/director');
    }
}
