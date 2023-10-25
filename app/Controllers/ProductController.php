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
        $inventoryModel = new \App\Models\inventoryModel();
        //validate
        $validation = $this->validate([
            'productName'=>'required',
            'dateReport'=>'required',
            'defectType'=>'required',
            'qty'=>'required',
            'details'=>'required',
            'recommendation'=>'required',
            'file'=>'uploaded[file]'
        ]);
        //datas
        $dateCreated = date('Y-m-d');
        $itemID = $this->request->getPost('itemID');
        $productName = $this->request->getPost('productName');
        $defectType = $this->request->getPost('defectType');
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
            $totalQty = $inventoryModel->WHERE('inventID',$itemID)->first();
            if($qty>$totalQty['Qty'])
            {
                echo "Invalid! Insufficient number of stocks";
            }
            else
            {
                if($file->isValid() && ! $file->hasMoved())
                {
                    $file->move('Damage_Files/',$originalName);
                    $values = [
                        'DateCreated'=>$dateCreated,'inventID'=>$itemID,'Qty'=>$qty,
                        'Details'=>$details,'DamageRate'=>$defectType,'DateReport'=>$dateReport,'Image'=>$originalName,'Remarks'=>$remarks,'Status'=>0,'accountID'=>$user
                        ];
                    $damageReport->save($values);
                    //deduct the number of stocks vs damage stock
                    $newQty = $totalQty['Qty']-$qty;
                    $values = ['Qty'=>$newQty,];
                    $inventoryModel->update($itemID,$values);
                    echo "success";
                }
                else
                {
                    echo "File already uploaded";
                }
            }
        }
    }
}