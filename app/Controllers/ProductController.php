<?php

namespace App\Controllers;

class ProductController extends BaseController
{
    private $db;
    public function __construct()
    {
        $this->db = db_connect();
    }

    public function productInfo()
    {
        $val = $this->request->getGet('value');
        $productInfo = new \App\Models\inventoryModel();
        $product = $productInfo->WHERE('inventID',$val)->first();
        echo $product['productName'];
    }

    public function saveReport()
    {
        $damageReport = new \App\Models\damageModel();
        $validation = $this->validate([
            'productName'=>'required',
            'dateReport'=>'required',
            'qty'=>'required',
            'details'=>'required',
            'recommendation'=>'required',
            'file'=>'uploaded[file]'
        ]);
        //datas
        $dateCreated = date('Y-m-d');
        $itemID = $this->request->getPost('itemID');
        $productName = $this->request->getPost('productName');
        $dateReport = $this->request->getPost('dateReport');
        $qty = $this->request->getPost('qty');
        $details = $this->request->getPost('details');
        $remarks = $this->request->getPost('recommendation');
        $file = $this->request->getFile('file');
        $originalName = $file->getClientName();
        $user = session()->get('loggedUser');
        if(!$validation)
        {
            echo "Invalid! Please fill in the form to continue";
        }
        else
        {
            if($file->isValid() && ! $file->hasMoved())
            {
                $file->move('Damage_Files/',$originalName);
                $values = [
                    'DateCreated'=>$dateCreated,'inventID'=>$itemID,'Qty'=>$qty,
                    'Details'=>$details,'DateReport'=>$dateReport,'Image'=>$originalName,'Remarks'=>$remarks,'accountID'=>$user
                    ];
                $damageReport->save($values);
                echo "success";
            }
            else
            {
                echo "File already uploaded";
            }
        }
    }
}