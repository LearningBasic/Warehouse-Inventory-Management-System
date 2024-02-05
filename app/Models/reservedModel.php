<?php

namespace App\Models;

use CodeIgniter\Model;

class reservedModel extends Model
{
    protected $table      = 'tblreserved';
    protected $primaryKey = 'reservedID';
    protected $useAutoIncrement  = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $userSoftDelete = false;
    protected $protectFields = true;
    protected $allowedFields = ['Date','OrderNo','purchaseNumber','InvoiceNo','InvoiceAmount','supplierID','Remarks',
    'productName','Qty','Available','ItemUnit','UnitPrice','Description','Condition','Receiver','warehouseID'];

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    
    
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];
}