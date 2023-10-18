<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    private $db;
    public function __construct()
    {
        $this->db = db_connect();
    }

    public function listSupplier()
    {
        $builder = $this->db->table('tblsupplier a');
        $builder->select('a.supplierName,FORMAT(count(inventID),0)total');
        $builder->join('tblinventory b','b.supplierID=a.supplierID','LEFT');
        $builder->groupBy('a.supplierID')->orderby('total','DESC')->limit(10);
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            ?>
            <li class="d-flex align-items-center justify-content-between">
                <div class="name-avatar d-flex align-items-center pr-2">
                    <div class="txt">
                        <div class="font-14 weight-600"><?php echo $row->supplierName ?></div>
                    </div>
                </div>
                <div class="cta flex-shrink-0">
                    <a href="#" class="btn btn-sm btn-outline-primary"><?php echo $row->total ?></a>
                </div>
            </li>
            <?php
        }
    }

    public function outofStock()
    {
        $builder = $this->db->table('tblinventory a');
        $builder->select('a.*,b.categoryName');
        $builder->join('tblcategory b','b.categoryID=a.categoryID','LEFT');
        $builder->WHERE('a.Qty',0);
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            ?>
            <li class="d-flex align-items-center justify-content-between">
                <div class="name-avatar d-flex align-items-center pr-2">
                    <div class="txt">
                        <div class="font-14 weight-600"><?php echo $row->productName ?></div>
                        <div class="font-12 weight-500" data-color="#b2b1b6"><?php echo $row->Description ?></div>
                    </div>
                </div>
                <div class="cta flex-shrink-0">
                    <a href="#" class="btn btn-sm btn-outline-primary"><?php echo $row->categoryName ?></a>
                </div>
            </li>
            <?php
        }
    }

    public function totalVoid()
    {
        $builder = $this->db->table('tblinventory');
        $builder->select('FORMAT(COUNT(*),0)total');
        $builder->WHERE('Qty',0);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            echo $row->total;
        }
    }

    public function totalStocks()
    {
        $builder = $this->db->table('tblinventory');
        $builder->select('FORMAT(COUNT(*),0)total');
        $builder->WHERE('Qty<>',0);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            echo $row->total;
        }
    }

    public function totalReserved()
    {
        $builder = $this->db->table('tblreserved');
        $builder->select('FORMAT(COUNT(*),0)total');
        $builder->WHERE('Status','Hold');
        $data = $builder->get();
        if($row = $data->getRow())
        {
            echo $row->total;
        }
    }

    public function totalItem()
    {
        $onhand=0;
        $builder = $this->db->table('tblinventory');
        $builder->select('COUNT(*)total');
        $builder->WHERE('Qty<>',0);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $onhand =  $row->total;
        }
        //reserved
        $reserve=0;
        $builder = $this->db->table('tblreserved');
        $builder->select('COUNT(*)total');
        $builder->WHERE('Status','Hold');
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $reserve =  $row->total;
        }
        $total = $onhand + $reserve;
        echo number_format($total,0);
    }
}