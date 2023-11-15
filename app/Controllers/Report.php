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
        $category = $this->request->getGet('category');
        if($category=="ALL")
        {
            $sql = ('Select a.*,SUM(a.Qty)actual,
            SUM(CASE WHEN b.Remarks="Replacement" THEN b.Qty ELSE 0 END)damage,
            SUM(CASE WHEN b.Remarks="For Repair" AND b.Status=1 THEN b.Qty ELSE 0 END)repair,c.categoryName from tblinventory a 
            LEFT JOIN tbldamagereport b ON b.inventID=a.inventID 
            LEFT JOIN tblcategory c ON c.categoryID=a.categoryID WHERE a.warehouseID=:location: GROUP BY a.inventID');
            $query =$this->db->query($sql,[
                "location"=>$location,
                ]);
            foreach ($query->getResult() as $row)
            {
                $total = $row->actual+$row->damage+$row->repair;
                ?>
                <tr>
                    <td><?php echo $row->productID ?></td>
                    <td><?php echo $row->productName ?></td>
                    <td><?php echo $row->categoryName ?></td>
                    <td style="text-align:right;"><?php echo number_format($row->actual,0) ?></td>
                    <td style="text-align:right;"><?php echo number_format($row->damage,0) ?></td>
                    <td style="text-align:right;"><?php echo number_format($row->repair,0) ?></td>
                    <td style="text-align:right;"><?php echo number_format($total,0) ?></td>
                </tr>
                <?php
            } 
        }
        else
        {
            $sql = ('Select a.*,SUM(a.Qty)actual,
            SUM(CASE WHEN b.Remarks="Replacement" THEN b.Qty ELSE 0 END)damage,
            SUM(CASE WHEN b.Remarks="For Repair" AND b.Status=1 THEN b.Qty ELSE 0 END)repair,c.categoryName from tblinventory a 
            LEFT JOIN tbldamagereport b ON b.inventID=a.inventID 
            LEFT JOIN tblcategory c ON c.categoryID=a.categoryID WHERE a.warehouseID=:location: AND a.categoryID=:category: GROUP BY a.inventID');
            $query =$this->db->query($sql,[
                "location"=>$location,
                'category'=>$category,
                ]);
            foreach ($query->getResult() as $row)
            {
                $total = $row->actual+$row->damage+$row->repair;
                ?>
                <tr>
                    <td><?php echo $row->productID ?></td>
                    <td><?php echo $row->productName ?></td>
                    <td><?php echo $row->categoryName ?></td>
                    <td style="text-align:right;"><?php echo number_format($row->actual,0) ?></td>
                    <td style="text-align:right;"><?php echo number_format($row->damage,0) ?></td>
                    <td style="text-align:right;"><?php echo number_format($row->repair,0) ?></td>
                    <td style="text-align:right;"><?php echo number_format($total,0) ?></td>
                </tr>
                <?php
            }
        }
    }
}
