<?php

namespace App\Controllers;

class Purchase extends BaseController
{
    private $db;
    public function __construct()
    {
        helper(['url','form']);
        $this->db = db_connect();
    }

    public function saveOrder()
    {
        $OrderItemModel = new \App\Models\OrderItemModel();
        //datas
        $user = session()->get('loggedUser');
        $datePrepared = $this->request->getPost('datePrepared');
        $dept = $this->request->getPost('department');
        $dateNeeded = $this->request->getPost('dateNeeded');
        $reason = $this->request->getPost('reason');
        //array
        $qty = $this->request->getPost('qty');
        $item = $this->request->getPost('item');
        $item_name = $this->request->getPost('item_name');
        $spec = $this->request->getPost('specification');
        
        $validation = $this->validate([
            'datePrepared'=>'required','department'=>'required','dateNeeded'=>'required','reason'=>'required'
        ]);

        if(!$validation)
        {
            session()->setFlashdata('fail','Invalid! Please fill in the form');
            return redirect()->to('/orders')->withInput();
        }
        else
        {
            $count = count($item_name);
            for($i=0;$i<$count;$i++)
            {
                $values = [
                    'accountID'=>$user, 'Qty'=>$qty[$i],'ItemUnit'=>$item[$i],'Item_Name'=>$item_name[$i],
                    'Specification'=>$spec[$i],'OrderNo'=>'','DateCreated'=>date('Y-m-d')
                ];
                $OrderItemModel->save($values);
            }
            session()->setFlashdata('success','Great! Successfully submitted for review');
            return redirect()->to('/orders')->withInput();
        }
    }
}