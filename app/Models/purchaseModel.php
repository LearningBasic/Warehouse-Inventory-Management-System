<?php

namespace App\Models;

use CodeIgniter\Model;

class purchaseModel extends Model
{
    protected $table      = 'tblprf';
    protected $primaryKey = 'prfID';

    protected $useAutoIncrement  = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $userSoftDelete = false;
    protected $protectFields = true;
    protected $allowedFields = ['OrderNo','accountID', 'DatePrepared','Department','DateNeeded','Reason','Status','DateCreated','PurchaseType'];

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