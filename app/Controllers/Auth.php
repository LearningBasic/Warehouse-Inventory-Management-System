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
        $accountModel = new \App\Models\accountModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $validation = $this->validate([
            'username'=>[
                'rules'=>'is_not_unique[tblaccount.username]',
                'errors'=>[
                    'is_not_unique'=>'This account is not registered!'
                ]
            ],
            'password'=>[
                'rules'=>'min_length[8]|max_length[12]',
                'errors'=>
                [
                    'min_length'=>'Password must have atleast 8 characters in length',
                    'max_length'=>'Password must have atleast 12 characters in length',
                ]
            ]
        ]);
        if(!$validation)
        {
            return view('welcome_message',['validation'=>$this->validator]);
        }
        else
        {
            
        }
    }
}
