<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        if (session()->has('loggedUser'))
        {
            return redirect()->back();
        }
        else
        {
		  return view('welcome_message');
        }
    }

    public function dashboard()
    {
        return view('dashboard');
    }
}
