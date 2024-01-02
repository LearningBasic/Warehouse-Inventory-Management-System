<?php

namespace App\Controllers;

class Purchase extends BaseController
{
    private $db;
    public function __construct()
    {
        helper(['url','form']);
        $this->db = db_connect();
    }

    public function saveOrder()
    {
        $OrderItemModel = new \App\Models\OrderItemModel();
        $purchaseModel = new \App\Models\purchaseModel();
        $reviewModel = new \App\Models\reviewModel();
        //datas
        $user = session()->get('loggedUser');
        $datePrepared = $this->request->getPost('datePrepared');
        $dept = $this->request->getPost('department');
        $dateNeeded = $this->request->getPost('dateNeeded');
        $reason = $this->request->getPost('reason');
        $purchase_type = $this->request->getPost('purchase_type');
        //array
        $qty = $this->request->getPost('qty');
        $item = $this->request->getPost('item');
        $item_name = $this->request->getPost('item_name');
        $spec = $this->request->getPost('specification');
        //approver
        $approver_user = $this->request->getPost('approver');
        
        $validation = $this->validate([
            'datePrepared'=>'required','department'=>'required','dateNeeded'=>'required',
            'reason'=>'required','item_name'=>'required','approver'=>'required',
            'purchase_type'=>'required'
        ]);

        if(!$validation)
        {
            session()->setFlashdata('fail','Invalid! Please fill in the form');
            return redirect()->to('/orders')->withInput();
        }
        else
        {
            $code="";
            $builder = $this->db->table('tblprf');
            $builder->select('COUNT(prfID)+1 as total');
            $code = $builder->get();
            if($row  = $code->getRow())
            {
                $code = "PRF-".str_pad($row->total, 7, '0', STR_PAD_LEFT);
            }
            //save the prf data
            $values = [
                'OrderNo'=>$code,'accountID'=>$user, 'DatePrepared'=>$datePrepared,'Department'=>$dept,
                'DateNeeded'=>$dateNeeded,'Reason'=>$reason,'Status'=>0,'DateCreated'=>date('Y-m-d'),'PurchaseType'=>$purchase_type
            ];
            $purchaseModel->save($values);
            //save all the item requested
            $count = count($item_name);
            for($i=0;$i<$count;$i++)
            {
                $values = [
                    'accountID'=>$user, 'Qty'=>$qty[$i],'ItemUnit'=>$item[$i],'Item_Name'=>$item_name[$i],
                    'Specification'=>$spec[$i],'OrderNo'=>$code,'DateCreated'=>date('Y-m-d')
                ];
                $OrderItemModel->save($values);
            }
            //send to approver
            $value = [
                'accountID'=>$approver_user,'OrderNo'=>$code,'DateReceived'=>date('Y-m-d'),'Status'=>0,
                'DateApproved'=>"0000-00-00",'Comment'=>''
            ];
            $reviewModel->save($value);
            //send email notification
            $builder = $this->db->table('tblaccount');
            $builder->select('Fullname,Email');
            $builder->WHERE('accountID',$approver_user);
            $data = $builder->get();
            if($row = $data->getRow())
            {
                $email = \Config\Services::email();
                $email->setTo($row->Email,$row->Fullname);
                $email->setFrom("fastcat.system@gmail.com","FastCat");
                $imgURL = "assets/img/fastcat.png";
                $email->attach($imgURL);
                $cid = $email->setAttachmentCID($imgURL);
                $template = "<center>
                <img src='cid:". $cid ."' width='100'/>
                <table style='padding:10px;background-color:#ffffff;' border='0'><tbody>
                <tr><td><center><h1>Purchase Requistion Form</h1></center></td></tr>
                <tr><td><center>Hi, ".$row->Fullname."</center></td></tr>
                <tr><td><center>This is from FastCat System, sending you a reminder that</center></td></tr>
                <tr><td><p><center><b>".$code."</b> is requesting for your approval</center></p></td><tr>
                <tr><td><center>Please login to your account @ https:fastcat-ims.com.</center></td></tr>
                <tr><td><center>This is a system message please don't reply. Thank you</center></td></tr>
                <tr><td><center>FastCat IT Support</center></td></tr></tbody></table></center>";
                $subject = "Purchase Requisition Form - For Approval";
                $email->setSubject($subject);
                $email->setMessage($template);
                $email->send();
            }
            session()->setFlashdata('success','Great! Successfully submitted for review');
            return redirect()->to('/orders')->withInput();
        }
    }

    public function cancelOrder()
    {
        $purchaseModel = new \App\Models\purchaseModel();
        $reviewModel = new \App\Models\reviewModel();
        //data
        $val = $this->request->getPost('value');
        $code = "";
        //cancel the request
        $builder = $this->db->table('tblprf');
        $builder->select('OrderNo');
        $builder->WHERE('prfID',$val);
        $data = $builder->get();
        if($row  = $data->getRow())
        {
            $code = $row->OrderNo;
            $values = ['Status'=>2];
            $purchaseModel->update($val,$values);
        }
        //cancel the approval
        $builder = $this->db->table('tblreview');
        $builder->select('reviewID');
        $builder->WHERE('OrderNo',$code);
        $datas = $builder->get();
        if($rows = $datas->getRow())
        {
            $values = ['Status'=>2];
            $reviewModel->update($rows->reviewID,$values);
        }
        echo "success";
    }


    public function viewOrder()
    {
        $val = $this->request->getGet('value');
        $builder = $this->db->table('tbl_order_item');
        $builder->select('*');
        $builder->WHERE('OrderNo',$val);
        $data = $builder->get();
        ?>
        <table class="table table-bordered stripe hover nowrap">
            <thead>
                <th>Product Name</th>
                <th>Qty</th>
                <th>Item Unit</th>
                <th>Specification</th>
            </thead>
            <tbody>
        <?php
        foreach($data->getResult() as $row)
        {
            ?>
            <tr>
                <td><?php echo $row->Item_Name ?></td>
                <td><?php echo $row->Qty ?></td>
                <td><?php echo $row->ItemUnit ?></td>
                <td><?php echo $row->Specification ?></td>
            </tr>
            <?php
        }
        ?>
        </table>
            </tbody>
        <?php
    }

    public function getEditor()
    {
        $builder = $this->db->table('tblaccount');
        $builder->select('*');
        $builder->WHERE('Status',1)->WHERE('systemRole','Editor');
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            ?>
            <option value="<?php echo $row->accountID ?>"><?php echo $row->Fullname ?> - <?php echo $row->Department ?></option>
            <?php
        }
    }

    public function notification()
    {
        $user = session()->get('loggedUser');
        $builder = $this->db->table('tblreview');
        $builder->select('COUNT(reviewID)total');
        $builder->WHERE('Status',0)->WHERE('accountID',$user);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            echo $row->total;
        }
    }

    public function viewPurchase()
    {
        $val = $this->request->getGet('value');
        $builder = $this->db->table('tblreview a');
        $builder->select('a.reviewID,a.Status,b.OrderNo,b.Department,b.Reason,b.DateNeeded,c.Fullname');
        $builder->join('tblprf b','b.OrderNo=a.OrderNo','LEFT');
        $builder->join('tblaccount c','b.accountID=c.accountID','LEFT');
        $builder->WHERE('a.reviewID',$val);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            ?>
            <form method="post" class="row g-3" id="frmReview">
                <input type="hidden" name="reviewID" id="reviewID" value="<?php echo $row->reviewID ?>"/>
                <div class="col-12 form-group">
                    <div class="row g-3">
                        <div class="col-lg-8">
                            <label>PRF Number</label>
                            <input type="text" class="form-control" name="orderno" value="<?php echo $row->OrderNo ?>"/>
                        </div>
                        <div class="col-lg-4">
                            <label>Date Needed</label>
                            <input type="date" class="form-control" name="dateneeded" value="<?php echo $row->DateNeeded ?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-12 form-group">
                    <div class="row g-3">
                        <div class="col-lg-8">
                            <label>Requestor</label>
                            <input type="text" class="form-control" name="fullname" value="<?php echo $row->Fullname ?>"/>
                        </div>
                        <div class="col-lg-4">
                            <label>Department</label>
                            <input type="text" class="form-control" name="department" value="<?php echo $row->Department ?>"/>
                        </div>
                    </div>
                </div>
                <div class="col-12 form-group">
                    <label>Reason</label>
                    <textarea name="reason" id="reason" class="form-control"><?php echo $row->Reason ?></textarea>
                </div>
                <div class="col-12 form-group">
                    <table class="table table-striped table-bordered hover nowrap">
                        <thead>
                            <th>Item(s)</th>
                            <th>Unit(s)</th>
                            <th>Qty</th>
                            <th>Specification(s)</th>
                        </thead>
                        <tbody>
                            <?php
                            $builder = $this->db->table('tbl_order_item');
                            $builder->select('*');
                            $builder->WHERE('OrderNo',$row->OrderNo);
                            $datas = $builder->get();
                            foreach($datas->getResult() as $rows)
                            {
                                ?>
                                <tr>
                                    <td><?php echo $rows->Item_Name ?></td>
                                    <td><?php echo $rows->ItemUnit ?></td>
                                    <td><?php echo $rows->Qty ?></td>
                                    <td><?php echo $rows->Specification ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 form-group">
                    <?php if($row->Status==0){ ?>
                    <input type="button" class="btn btn-primary accept" value="Accept"/>
                    <button type="button" class="btn btn-outline-danger cancel">Cancel</button>
                    <?php } ?>
                </div>
            </form>
            <?php
        }
    }

    public function Accept()
    {
        $systemLogsModel = new \App\Models\systemLogsModel();
        $reviewModel = new \App\Models\reviewModel();
        $purchaseModel = new \App\Models\purchaseModel();
        //data
        $val = $this->request->getPost('reviewID');
        $user = session()->get('loggedUser');
        $values = [
            'Status'=>1,'DateApproved'=>date('Y-m-d')
        ];
        $reviewModel->update($val,$values);
        //update
        $builder = $this->db->table('tblreview a');
        $builder->select('a.OrderNo,b.Status');
        $builder->join('tblprf b','b.OrderNo=a.OrderNo','LEFT');
        $builder->WHERE('a.reviewID',$val);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            if($row->Status==0)
            {
                $purchase = $purchaseModel->WHERE('OrderNo',$row->OrderNo)->first();
                $value = ['Status'=>1];
                $purchaseModel->update($purchase['prfID'],$value);
                //save logs
                $values = [
                    'accountID'=>$user,'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Accepted '.$row->OrderNo
                ];
                $systemLogsModel->save($values);
                //send email to procurement dept head
                $builder = $this->db->table('tblaccount');
                $builder->select('*');
                $builder->WHERE('Department','Procurement')->WHERE('systemRole','Administrator');
                $datas = $builder->get();
                if($rows = $datas->getRow())
                {
                    $records = [
                        'accountID'=>$rows->accountID,'OrderNo'=>$row->OrderNo,
                        'DateReceived'=>date('Y-m-d'),'Status'=>0,'DateApproved'=>'0000-00-00','Comment'=>''
                    ];
                    $reviewModel->save($records);
                    //email
                    $email = \Config\Services::email();
                    $email->setTo($rows->Email,$rows->Fullname);
                    $email->setFrom("fastcat.system@gmail.com","FastCat");
                    $imgURL = "assets/img/fastcat.png";
                    $email->attach($imgURL);
                    $cid = $email->setAttachmentCID($imgURL);
                    $template = "<center>
                    <img src='cid:". $cid ."' width='100'/>
                    <table style='padding:10px;background-color:#ffffff;' border='0'><tbody>
                    <tr><td><center><h1>Purchase Requistion Form</h1></center></td></tr>
                    <tr><td><center>Hi, ".$rows->Fullname."</center></td></tr>
                    <tr><td><center>This is from FastCat System, sending you a reminder that</center></td></tr>
                    <tr><td><p><center><b>".$row->OrderNo."</b> is requesting for your approval</center></p></td><tr>
                    <tr><td><center>Please login to your account @ https:fastcat-ims.com.</center></td></tr>
                    <tr><td><center>This is a system message please don't reply. Thank you</center></td></tr>
                    <tr><td><center>FastCat IT Support</center></td></tr></tbody></table></center>";
                    $subject = "Purchase Requisition Form - For Approval";
                    $email->setSubject($subject);
                    $email->setMessage($template);
                    $email->send();
                }
            }
            else
            {
                $purchase = $purchaseModel->WHERE('OrderNo',$row->OrderNo)->first();
                $value = ['Status'=>3];
                $purchaseModel->update($purchase['prfID'],$value);
                //save logs
                $values = [
                    'accountID'=>$user,'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Accepted '.$row->OrderNo
                ];
                $systemLogsModel->save($values);
            }
        }
        echo "success";
    }

    public function Cancel()
    {
        $systemLogsModel = new \App\Models\systemLogsModel();
        $reviewModel = new \App\Models\reviewModel();
        $purchaseModel = new \App\Models\purchaseModel();
        //data
        $val = $this->request->getPost('value');
        $msg = $this->request->getPost('message');
        $user = session()->get('loggedUser');
        //cancel
        $values = [
            'Status'=>2,'Comment'=>$msg
        ];
        $reviewModel->update($val,$values);
        //update
        $builder = $this->db->table('tblreview a');
        $builder->select('a.OrderNo');
        $builder->join('tblprf b','b.OrderNo=a.OrderNo','LEFT');
        $builder->WHERE('a.reviewID',$val);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $purchase = $purchaseModel->WHERE('OrderNo',$row->OrderNo)->first();
            $value = ['Status'=>2];
            $purchaseModel->update($purchase['prfID'],$value);
            //system logs
            $values = [
                'accountID'=>$user,'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Cancelled '.$row->OrderNo
            ];
            $systemLogsModel->save($values);
        }
        echo "success";
    }

    public function cancelTransfer()
    {
        $systemLogsModel = new \App\Models\systemLogsModel();
        $transferModel = new \App\Models\transferModel();
        $inventoryModel = new \App\Models\inventoryModel();
        //data
        $val = $this->request->getPost('value');
        $user = session()->get('loggedUser');
        //revert the Qty
        $transfer = $transferModel->WHERE('transferID',$val)->first();
        $inventory = $inventoryModel->WHERE('inventID',$transfer['inventID'])->first();
        //add the quantity from two models
        $total = $transfer['Qty']+$inventory['Qty'];
        $record = ['Qty'=>$total];
        $inventoryModel->update($transfer['inventID'],$record);
        //cancel the transferring
        $values = ['Status'=>2];
        $transferModel->update($val,$values);
        //create logs
        //system logs
        $values = [
            'accountID'=>$user,'Date'=>date('Y-m-d H:i:s a'),
            'Activity'=>'Cancelled Transferring of '.$transfer['productName'].' with total of '.$transfer['Qty']
        ];
        $systemLogsModel->save($values);
        echo "success";
    }

    public function autoReset()
    {
        ignore_user_abort(true);
        $inventoryModel = new \App\Models\inventoryModel();
        $date = date('Y-m-d');
        $builder = $this->db->table('tblinventory');
        $builder->select('inventID');
        $builder->WHERE('ExpirationDate',$date);
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            $values = ['Qty'=>0];
            $inventoryModel->update($row->inventID,$values);
        }
        echo "success";
    }

    public function autoEmail()
    {
        ignore_user_abort(true);
        //send email to procurement dept head
        $builder = $this->db->table('tblinventory');
        $builder->select('*');
        $builder->WHERE('Qty',0);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $builder = $this->db->table('tblaccount');
            $builder->select('Fullname,Email');
            $builder->WHERE('Department','Procurement')->WHERE('systemRole','Administrator');
            $datas = $builder->get();
            if($row = $datas->getRow())
            {
                //email
                $email = \Config\Services::email();
                $email->setTo($row->Email,$row->Fullname);
                $email->setFrom("fastcat.system@gmail.com","FastCat");
                $imgURL = "assets/img/fastcat.png";
                $email->attach($imgURL);
                $cid = $email->setAttachmentCID($imgURL);
                $template = "<center>
                <img src='cid:". $cid ."' width='100'/>
                <table style='padding:10px;background-color:#ffffff;' border='0'><tbody>
                <tr><td><center><h1>FastCat Inventory System</h1></center></td></tr>
                <tr><td><center>Hi, ".$row->Fullname."</center></td></tr>
                <tr><td><center>This is from FastCat System, sending you a reminder that the system identifies all the out of stock(s) item.</center></td></tr>
                <tr><td><center>Please login to your account @ https:fastcat-ims.com.</center></td></tr>
                <tr><td><center>This is a system message please don't reply. Thank you</center></td></tr>
                <tr><td><center>FastCat IT Support</center></td></tr></tbody></table></center>";
                $subject = "Out-of-Stock - FastCat IMS";
                $email->setSubject($subject);
                $email->setMessage($template);
                $email->send();
            }
        }
        else{
            //do nothing
        }
    }

    public function fetchSupplier()
    {
        $val = $this->request->getGet('value');
        $builder = $this->db->table('tbl_order_item a');
        $builder->select('a.*,b.*');
        $builder->join('tblcanvass_sheet b','b.orderID=a.orderID','LEFT');
        $builder->WHERE('b.OrderNo',$val);
        $builder->groupby('b.canvassID,a.orderID');
        $builder->orderby('b.orderID','ASC');
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            ?>
            <tr>
                <td><?php echo $row->Qty ?></td>
                <td><?php echo $row->ItemUnit ?></td>
                <td><?php echo $row->Item_Name ?></td>
                <td><?php echo number_format($row->Price,2) ?></td>
                <td><?php echo $row->Supplier ?></td>
                <td><?php echo $row->ContactPerson ?></td>
                <td><?php echo $row->ContactNumber ?></td>
                <td><?php echo $row->Terms ?></td>
                <td><?php echo $row->Warranty ?></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm text-white delete" value="<?php echo $row->canvassID ?>"><span class="dw dw-trash"></span></button>
                </td>
            </tr>
            <?php
        }
    }

    public function addEntry()
    {
        $canvassModel = new \App\Models\canvassModel();
        //datas
        $orderNo = $this->request->getPost('orderNo');
        $item = $this->request->getPost('item');
        $unitPrice = $this->request->getPost('unitPrice');
        $supplier = $this->request->getPost('supplier');
        $contactPerson = $this->request->getPost('contactPerson');
        $address = $this->request->getPost('address');
        $phone = $this->request->getPost('phone');
        $terms = $this->request->getPost('terms');
        $warranty = $this->request->getPost('warranty');
        //validate
        $validation = $this->validate([
            'item'=>'required',
            'unitPrice'=>'required',
            'supplier'=>'required',
            'contactPerson'=>'required',
            'address'=>'required',
            'phone'=>'required',
            'terms'=>'required',
            'warranty'=>'required',
        ]);
        if(!$validation)
        {
            echo "Invalid! Please fill in the form to continue";
        }
        else
        {
            $values = [
                'OrderNo'=>$orderNo, 'orderID'=>$item,'Supplier'=>$supplier,
                'Price'=>$unitPrice,'ContactPerson'=>$contactPerson,'Address'=>$address,
                'ContactNumber'=>$phone,'Terms'=>$terms,'Warranty'=>$warranty,
                'Reference'=>'','Remarks'=>''
            ];
            $canvassModel->save($values);
            echo "success";
        }
    }

    public function viewImage()
    {
        $val = $this->request->getGet('value');
        $builder = $this->db->table('tblimage');
        $builder->select('Image');
        $builder->WHERE('inventID',$val);
        $data = $builder->get();
        ?>
        <div class="gallery-wrap">
            <ul class="row">
        <?php
        foreach($data->getResult() as $row)
        {
            $imgURL = "Products/".$row->Image;
            ?>
            <li class="col-lg-3 col-md-6 col-sm-12">
                <div class="da-card box-shadow">
                    <div class="da-card-photo">
                        <img src="<?php echo $imgURL ?>" alt="">
                    </div>
                </div>
            </li>
            <?php
        }
        ?>
            </ul>
        </div>
        <?php
    }

    public function removeItem()
    {
        $val = $this->request->getPost('value');
        $builder = $this->db->table('tblcanvass_sheet');
        $builder->WHERE('canvassID',$val);
        $builder->delete();
        echo "success";
    }

    public function saveForm()
    {
        $canvassForm = new \App\Models\canvasFormModel();
        //data
        $user = session()->get('loggedUser');
        $datePrepared = $this->request->getPost('datePrepared');
        $dateNeeded = $this->request->getPost('dateNeeded');
        $OrderNo = $this->request->getPost('OrderNo');
        $department = $this->request->getPost('department');
        $deptHead = $this->request->getPost('approver');
        $requestor = $this->request->getPost('requestor');

        $validation = $this->validate([
            'datePrepared'=>'required','dateNeeded'=>'required',
            'OrderNo'=>'required','department'=>'required',
            'approver'=>'required',
        ]);
        if(!$validation)
        {
            
        }
        else
        {
            $code="";
            $builder = $this->db->table('tblcanvass_form');
            $builder->select('COUNT(formID)+1 as total');
            $code = $builder->get();
            if($row  = $code->getRow())
            {
                $code = "CS".str_pad($row->total, 7, '0', STR_PAD_LEFT);
            }
        }
    }
}