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
        $systemLogsModel = new \App\Models\systemLogsModel();

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
            $user_info = $accountModel->where('username', $username)->WHERE('Status',1)->first();
            $check_password = Hash::check($password, $user_info['password']);
            if(!$check_password || empty($check_password))
            {
                session()->setFlashdata('fail','Invalid Username or Password!');
                return redirect()->to('/auth')->withInput();
            }
            else
            {
                session()->set('loggedUser', $user_info['accountID']);
                session()->set('fullname', $user_info['Fullname']);
                session()->set('role',$user_info['systemRole']);
                session()->set('assignment',$user_info['warehouseID']);
                //save the logs
                $values = ['accountID'=>$user_info['accountID'],'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Logged-In'];
                $systemLogsModel->save($values);
                return redirect()->to('/dashboard');
            }
        }
    }

    public function logout()
    {
        if(session()->has('loggedUser'))
        {
            session()->remove('loggedUser');
            return redirect()->to('/?access=out')->with('fail', 'You are logged out!');
        }
    }
}
