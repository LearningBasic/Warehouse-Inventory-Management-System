<?php

namespace App\Controllers;
use Dompdf\Dompdf;

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
        $location = $this->request->getGet('location');
        $validation = $this->validate([
            'from'=>'required','to'=>'required',
            'location'=>'required'
        ]);
        if(!$validation)
        {
            ?>
            <tr>
                <td colspan="7"><center>No Data(s)</center></td>
            </tr>
            <?php
        }
        else
        {
            $sql = ('Select a.productID,a.productName,SUM(a.Qty)actual,a.unitPrice,b.total,c.categoryName from tblinventory a  
            LEFT JOIN (Select inventID,accountID,COUNT(scanID)total from tblscanned_items WHERE Status=1 AND Date BETWEEN :from: AND :to: GROUP BY inventID) b ON b.inventID=a.inventID
            LEFT JOIN tblcategory c ON c.categoryID=a.categoryID
            LEFT JOIN tblaccount d ON b.accountID=d.accountID
            WHERE d.warehouseID=:location: GROUP BY a.inventID');
            $query =$this->db->query($sql,[
                "from"=>$from,
                "to"=>$to,
                "location"=>$location
                ]);
            foreach ($query->getResult() as $row)
            {
                $total = $row->actual - $row->total;
                $totalamount = $total * $row->unitPrice;
                ?>
                <tr>
                    <td><?php echo $row->productID ?></td>
                    <td><?php echo $row->productName ?></td>
                    <td><?php echo $row->categoryName ?></td>
                    <td style="text-align:right;"><?php echo number_format($row->actual,0) ?></td>
                    <td style="text-align:right;"><?php echo number_format($row->total,0) ?></td>
                    <td style="text-align:right;"><?php echo number_format($total,0) ?></td>
                    <td style="text-align:right;">PhP <?php echo number_format($totalamount,2) ?></td>
                </tr>
                <?php
            }
        }
    }

    public function Download($id)
    {
        $dompdf = new Dompdf();
        $purchase_number="";
        $builder = $this->db->table('tblpurchase_logs a');
        $builder->select('a.purchaseNumber,a.Date,b.OrderNo,b.Supplier,b.Price,b.Terms,b.Address,c.Qty,c.Item_Name,c.ItemUnit');
        $builder->join('tblcanvass_sheet b','b.canvassID=a.canvassID','LEFT');
        $builder->join('tbl_order_item c','c.orderID=b.orderID','LEFT');
        $builder->WHERE('a.canvassID',$id);
        $data = $builder->get(); 
        $template = '';  
        if($row = $data->getRow())
        {        
            $purchase_number = $row->purchaseNumber;
            $template .= "
            <head>
                <style>
                table{font-size:12px;}
                #vendor {
                    font-family: sans-serif;
                    border-collapse: collapse;
                    width: 100%;
                  }
                  
                  #vendor td, #vendor th {
                    border: 1px solid #ddd;
                    padding: 5px;font-size:12px;
                  }
                 
                  #vendor tr:hover {background-color: #ddd;}
                  
                  #vendor th {
                    padding-top: 12px;
                    padding-bottom: 12px;
                    text-align: left;
                    color: #000000;
                  }
                </style>
            </head>
            <body>
                <table style='width:100%;'>
                <tr>
                    <td><img src='' height='50'></td>
                    <td><h4><center>PURCHASE ORDER</center></h4></td>
                    <td>No. ".$purchase_number."</td>
                </tr>
                <tr><td colspan='3'>&nbsp;</td></tr>
                <tr><td colspan='3'><center><b>Unioil Center Building, Commence Ave. Cor. Acacia Ave., Muntinlupa, PHL</b></center></td></tr>
                <tr><td colspan='3'><center><small>VAT Reg. TIN 233-662-279-00</small></center></td></tr>
                <tr><td colspan='3'><center><small>Tel No. (632) 842 9341 Fax No. 632 807 5670</small></center></td></tr>
                <tr><td colspan='3'>&nbsp;</td></tr>
                <tr>
                    <td colspan='2'><b>Vendor : ".$row->Supplier."</b></td>
                    <td><b>Date</b> : ".$row->Date."</td>
                </tr>
                <tr>
                    <td colspan='2'><b>Address</b> : ".$row->Address."</td>
                    <td><b>Terms</b> : ".$row->Terms."</td>
                </tr>
                <tr><td colspan='3'><b>TIN :</b></td></tr>
                <tr><td colspan='3'><b>Ship To : Archipelago Philippine Ferries Corporation</b><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Unioil Center Building, Commence Ave. Cor. Acacia Ave., Muntinlupa, PHL</td></tr>
                <tr><td colspan='3'>&nbsp;</td></tr>
                <tr><td colspan='3'><b>Gentlemen:</b> We are ordering the following and charged to our account</td></tr>
                <tr>
                    <td colspan='3'>
                    <table id='vendor' style='width:100%;'>
                        <thead>
                        <th>QUANTITY</th>
                        <th>UNIT</th>
                        <th>DESCRIPTION</th>
                        <th>UNIT PRICE</th>
                        <th>AMOUNT</th>
                        </thead>
                        <tbody>
                            <tr>
                            <td>".$row->Qty."</td>
                            <td>".$row->ItemUnit."</td>
                            <td>".$row->Item_Name."<br/>".$row->OrderNo."</td>
                            <td style='text-align:right;'>".number_format($row->Price,2)."</td>
                            <td style='text-align:right;'>".number_format($row->Price*$row->Qty,2)."</td>
                            </tr>
                            <tr>
                                <td colspan='4' style='text-align:right;'>Subtotal Amount:</td>
                                <td style='text-align:right;font-weight:bold;'>PHP ".number_format($row->Price*$row->Qty,2)."</td>
                            </tr>
                        </tbody>
                    </table>
                    </td>
                </tr>
                </table>
            </body>";
            
        }
        $dompdf->loadHtml($template);
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();
        $dompdf->stream($purchase_number.".pdf");
        exit();
    }
}
