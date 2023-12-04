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

    public function searchInventory()
    {
        $from = $this->request->getGet('from');
        $to = $this->request->getGet('to');
        $account = $this->request->getGet('accounts');
        $validation = $this->validate([
            'from'=>'required','to'=>'required',
            'accounts'=>'required'
        ]);
        if(!$validation)
        {
            ?>
            <tr>
                <td colspan="6"><center>No Data(s)</center></td>
            </tr>
            <?php
        }
        else
        {
            $sql = ('Select a.productID,a.productName,SUM(a.Qty)actual,b.total,c.categoryName from tblinventory a  
            LEFT JOIN (Select inventID,accountID,COUNT(scanID)total from tblscanned_items WHERE Status=1 AND Date BETWEEN :from: AND :to: GROUP BY inventID) b ON b.inventID=a.inventID
            LEFT JOIN tblcategory c ON c.categoryID=a.categoryID
            WHERE b.accountID=:user: GROUP BY a.inventID');
            $query =$this->db->query($sql,[
                "from"=>$from,
                "to"=>$to,
                "user"=>$account
                ]);
            foreach ($query->getResult() as $row)
            {
                $total = $row->actual - $row->total;
                ?>
                <tr>
                    <td><?php echo $row->productID ?></td>
                    <td><?php echo $row->productName ?></td>
                    <td><?php echo $row->categoryName ?></td>
                    <td style="text-align:right;"><?php echo number_format($row->actual,0) ?></td>
                    <td style="text-align:right;"><?php echo number_format($row->total,0) ?></td>
                    <td style="text-align:right;"><?php echo number_format($total,0) ?></td>
                </tr>
                <?php
            }
        }
    }
}
