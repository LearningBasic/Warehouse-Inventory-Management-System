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
        //array
        $qty = $this->request->getPost('qty');
        $item = $this->request->getPost('item');
        $item_name = $this->request->getPost('item_name');
        $spec = $this->request->getPost('specification');
        //approver
        $approver_user = $this->request->getPost('approver');
        
        $validation = $this->validate([
            'datePrepared'=>'required','department'=>'required','dateNeeded'=>'required',
            'reason'=>'required','item_name'=>'required','approver'=>'required'
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
                'DateNeeded'=>$dateNeeded,'Reason'=>$reason,'Status'=>0,'DateCreated'=>date('Y-m-d')
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
                <table style='padding:20px;background-color:#ffffff;' border='0'><tbody>
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
        $builder->WHERE('b.OrderNo',$val);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            ?>
            <form method="post" class="row g-3" id="frmReview">
                <input type="hidden" name="reviewID" value="<?php echo $row->reviewID ?>"/>
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
                    <button type="button" class="btn btn-primary accept">Accept</button>
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
        $builder = $this->db->table('tblreview');
        $builder->select('OrderNo');
        $builder->WHERE('reviewID',$val);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $purchase = $purchaseModel->WHERE('OrderNo',$row->OrderNo)->first();
            $value = ['Status'=>1];
            $purchaseModel->update($purchase['prfID'],$value);
            //save logs
            $values = [
                'accountID'=>$user,'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Accepted '.$row->OrderNo
            ];
            $systemLogsModel->save($values);
        }
        //send email to procurement dept head
        echo "success";
    }
}