<?php

namespace App\Models;

use CodeIgniter\Model;

class damageReportModel extends Model
{
    protected $table      = 'tbldamagereport';
    protected $primaryKey = 'reportID';

    protected $useAutoIncrement  = true;
    protected $insertID = 0;
    protected $returnType = 'array';
    protected $userSoftDelete = false;
    protected $protectFields = true;
    protected $allowedFields = ['DateCreated','inventID','Qty','Details','DamageRate','DateReport','Image','Remarks','Status','DateApproved','accountID'];

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