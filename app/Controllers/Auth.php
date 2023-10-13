<?php

namespace App\Controllers;
use App\Libraries\Hash;

class Auth extends BaseController
{
    private $db;
    public function __construct()
    {
        helper(['url','form']);
        $this->db = db_connect();
    }
	public function index()
	{
		return view('welcome_message');
	}

    public function check()
    {

    }
}
