<?php

namespace App\Controllers;

class UserController extends BaseController
{
    private $db;
    public function __construct()
    {
        helper(['url','form']);
        $this->db = db_connect();
    }

    public function dashboard()
    {
        return view('user/index');
    }
}