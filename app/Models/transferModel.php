<?php

namespace App\Models;

use CodeIgniter\Model;

class transferModel extends Model
{
    protected $table      = 'tbltransferitem';
    protected $primaryKey = 'transferID';

    protected $useAutoIncrement  = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $userSoftDelete = false;
    protected $protectFields = true;
    protected $allowedFields = ['inventID','productID','productName','Code',
    'Description','Qty','ItemUnit','unitPrice','datePrepared','dateEffective',
    'warehouseID','categoryID','supplierID','ExpirationDate','Status','Department',
    'cargo_type','Driver','Plate_number','TrackingNumber','accountID'];

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