<?php

namespace App\Controllers;

class Report extends BaseController
{
    private $db;
    public function __construct()
    {
        $this->db = db_connect();
    }

    public function searchStockReport()
    {
        $location = $this->request->getGet('location');
        
    }
}
