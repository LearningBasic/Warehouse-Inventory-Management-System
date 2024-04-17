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

    public function saveChanges()
    {
        date_default_timezone_set('Asia/Manila');
        $OrderItemModel = new \App\Models\OrderItemModel();
        $systemLogsModel = new \App\Models\systemLogsModel();
        $canvassModel = new \App\Models\canvassModel();
        $purchaseOrderModel = new \App\Models\purchaseOrderModel();
        $purchaseReviewModel = new \App\Models\purchaseReviewModel();
        $canvassFormModel = new \App\Models\canvasFormModel();
        //data
        $reference = $this->request->getPost('reference');
        $itemID = $this->request->getPost('itemID');
        $qty = $this->request->getPost('qty');
        $item = $this->request->getPost('item');
        $item_name = $this->request->getPost('item_name');
        $spec = $this->request->getPost('specification');
        $price = $this->request->getPost('price');
        $date = date('Y-m-d');
        $file = $this->request->getFile('file');
        $originalName = $file->getClientName();
        $status = 0;

        $count = count($itemID);
        //update the specification,Item Unit,Qty and Item Name
        for($i=0;$i<$count;$i++)
        {
            $values = [
                'Qty'=>$qty[$i],'ItemUnit'=>$item[$i],'Item_Name'=>$item_name[$i],
                'Specification'=>$spec[$i],
            ];
            $OrderItemModel->update($itemID[$i],$values);
        }
        //update the price
        for($i=0;$i<$count;$i++)
        {
            $canvass = $canvassModel->WHERE('orderID',$itemID[$i])->first();
            $values = ['Price'=>$price[$i],];
            $canvassModel->update($canvass['canvassID'],$values);
        }
        //update the attachment
        if(empty($originalName))
        {
            //do nothing
        }
        else
        {
            //get the ID
            $form = $canvassFormModel->WHERE('Reference',$reference)->first();
            $values = ['Attachment'=>$originalName];
            $file->move('Attachment/',$originalName);
            $canvassFormModel->update($form['formID'],$values);
        }

        //update the status of PO
        $builder = $this->db->table('tblpurchase_logs');
        $builder->select('purchaseLogID,purchaseNumber');
        $builder->WHERE('Reference',$reference);
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            $list = ['Status'=>$status,'Date'=>$date];
            $purchaseOrderModel->update($row->purchaseLogID,$list);
            //get the prID
            $record = ['DateReceived'=>$date,'Status'=>$status];
            $review = $purchaseReviewModel->WHERE('purchaseNumber',$row->purchaseNumber)->first();
            $purchaseReviewModel->update($review['prID'],$record);
        }
        $value = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Update the Records of Quotation/Canvass Sheet No '.$reference];
        $systemLogsModel->save($value);
        session()->setFlashdata('success','Great! Successfully update the ordered item(s)');
        return redirect()->to('/purchase-order')->withInput();
    }

    public function countItem()
    {
        $user = session()->get('loggedUser');
        $builder = $this->db->table('tblissuance');
        $builder->select('COUNT(*)total');
        $builder->WHERE('accountID',$user)->WHERE('Status',0);
        $list = $builder->get();
        if($row = $list->getRow())
        {
            echo $row->total;
        }
    }

    public function cancelItem()
    {
        $issuanceModel = new \App\Models\issuanceModel();
        //data
        $val = $this->request->getPost('value');
        $values = ['Status'=>2];
        $issuanceModel->update($val,$values);
        echo "success";
    }

    public function acceptItem()
    {
        $issuanceModel = new \App\Models\issuanceModel();
        //data
        $val = $this->request->getPost('value');
        $values = ['Status'=>1];
        $issuanceModel->update($val,$values);
        echo "success";
    }

    public function sendItem()
    {
        $issuanceModel = new \App\Models\issuanceModel();
        $accountModel = new \App\Models\accountModel();
        $OrderItemModel = new \App\Models\OrderItemModel();
        //data
        $val = $this->request->getPost('item');
        $planner = $this->request->getPost('planner');
        if(empty($planner))
        {
            echo "Invalid! Please select assigned Personnel for issuance";
        }
        else
        {
            //get the details of the item
            $items = $OrderItemModel->WHERE('orderID',$val)->first();
            //send to the planner
            $values = [
                'accountID'=>$planner,'DateReceived'=>date('Y-m-d'),
                'Qty'=>$items['Qty'],'ItemUnit'=>$items['ItemUnit'],'Item_Name'=>$items['Item_Name'],
                'Specification'=>$items['Specification'],'OrderNo'=>$items['OrderNo'],'Status'=>0];
            $issuanceModel->save($values);
            //send email notification
            $account = $accountModel->WHERE('accountID',$planner)->first();
            $email = \Config\Services::email();
            $email->setTo($account['Email'],$account['Fullname']);
            $email->setFrom("fastcat.system@gmail.com","FastCat");
            $imgURL = "assets/img/fastcat.png";
            $email->attach($imgURL);
            $cid = $email->setAttachmentCID($imgURL);
            $template = "<center>
            <img src='cid:". $cid ."' width='100'/>
            <table style='padding:10px;background-color:#ffffff;' border='0'><tbody>
            <tr><td><center><h1>For Issuance</h1></center></td></tr>
            <tr><td><center>Hi, ".$account['Fullname']."</center></td></tr>
            <tr><td><center>This is from FastCat System, sending you a reminder that the item : ".$items['Item_Name']." is subject for issuance.</center></td></tr>
            <tr><td><p><center>Kindly create a issuance form to deliver the item to the respective area.</center></p></td><tr>
            <tr><td><center>Please login to your account @ https:fastcat-ims.com.</center></td></tr>
            <tr><td><center>This is a system message please don't reply. Thank you</center></td></tr>
            <tr><td><center>FastCat IT Support</center></td></tr></tbody></table></center>";
            $subject = "Issuance of Item";
            $email->setSubject($subject);
            $email->setMessage($template);
            $email->send();
            //remove the item
            $builder = $this->db->table('tbl_order_item');
            $builder->WHERE('orderID',$val);
            $builder->delete();
            echo "success";
        }
    }

    public function deleteItem()
    {
        $val = $this->request->getPost('value');
        $builder = $this->db->table('tbl_order_item');
        $builder->WHERE('orderID',$val);
        $builder->delete();
        echo "success";
    }

    public function listEditor()
    {
        $role = ['Editor','Administrator'];
        $user = session()->get('loggedUser');
        $builder = $this->db->table('tblaccount');
        $builder->select('*');
        $builder->WHERE('Status',1)->WHEREIN('systemRole',$role)->WHERE('accountID<>',$user)->WHERE('Department!=','');
        $editor = $builder->get();
        foreach($editor->getResult() as $row)
        {
            ?>
            <option value="<?php echo $row->accountID ?>"><?php echo $row->Fullname ?> - <?php echo $row->Department ?></option>
            <?php
        }
    }

    public function reSubmit()
    {
        date_default_timezone_set('Asia/Manila');
        $OrderItemModel = new \App\Models\OrderItemModel();
        $purchaseModel = new \App\Models\purchaseModel();
        $reviewModel = new \App\Models\reviewModel();
        //data
        $purchaseID = $this->request->getPost('purchaseID');
        $purchaseNumber = $this->request->getPost('purchaseNumber');
        $datePrepared = $this->request->getPost('datePrepared');
        $dept = $this->request->getPost('department');
        $dateNeeded = $this->request->getPost('dateNeeded');
        $reason = $this->request->getPost('reason');
        $purchase_type = $this->request->getPost('purchase_type');
        $file = $this->request->getFile('file');
        $originalName = $file->getClientName();
        $approver_user = $this->request->getPost('approver');
        //array
        $itemID = $this->request->getPost('itemID');
        $qty = $this->request->getPost('qty');
        $item = $this->request->getPost('item');
        $item_name = $this->request->getPost('item_name');
        $spec = $this->request->getPost('specification');

        $validation = $this->validate([
            'datePrepared'=>'required','department'=>'required','dateNeeded'=>'required',
            'reason'=>'required','item_name'=>'required','approver'=>'required',
            'purchase_type'=>'required',
        ]);

        if(!$validation)
        {
            session()->setFlashdata('fail','Invalid! Please fill in the form');
            return redirect()->to('/edit-purchase/'.$purchaseNumber)->withInput();
        }
        else
        {
            //update all the item requested
            $count = count($item_name);
            for($i=0;$i<$count;$i++)
            {
                $values = [
                    'Qty'=>$qty[$i],'ItemUnit'=>$item[$i],'Item_Name'=>$item_name[$i],
                    'Specification'=>$spec[$i],
                ];
                $OrderItemModel->update($itemID[$i],$values);
            }
            //update the purchase Form
            $values = [
                'DatePrepared'=>$datePrepared,'Department'=>$dept,
                'DateNeeded'=>$dateNeeded,'Reason'=>$reason,'Status'=>0,'DateCreated'=>date('Y-m-d'),
                'PurchaseType'=>$purchase_type,'Attachment'=>$originalName,
            ];
            $purchaseModel->update($purchaseID,$values);
            //upload the attachment
            if(empty($originalName))
            {
                //do nothing
            }
            else
            {
                $file->move('Attachment/',$originalName);
            }
            //send to approver
            $value = [
                'accountID'=>$approver_user,'OrderNo'=>$purchaseNumber,'DateReceived'=>date('Y-m-d'),'Status'=>0,
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
                <tr><td><p><center><b>".$purchaseNumber."</b> is requesting for your approval</center></p></td><tr>
                <tr><td><center>Please login to your account @ https:fastcat-ims.com.</center></td></tr>
                <tr><td><center>This is a system message please don't reply. Thank you</center></td></tr>
                <tr><td><center>FastCat IT Support</center></td></tr></tbody></table></center>";
                $subject = "Purchase Requisition Form - For Approval";
                $email->setSubject($subject);
                $email->setMessage($template);
                $email->send();
            }
            session()->setFlashdata('success','Great! Successfully submitted for review');
            return redirect()->to('/list-orders')->withInput();
        }
    }

    public function saveOrder()
    {
        date_default_timezone_set('Asia/Manila');
        $OrderItemModel = new \App\Models\OrderItemModel();
        $purchaseModel = new \App\Models\purchaseModel();
        $reviewModel = new \App\Models\reviewModel();
        //datas
        $user = session()->get('loggedUser');
        $datePrepared = $this->request->getPost('datePrepared');
        $itemGroup = $this->request->getPost('item_group');
        $tomorrow = date("Y-m-d", time() + 86400);
        $dept = $this->request->getPost('department');
        $dateNeeded = $this->request->getPost('dateNeeded');
        $reason = $this->request->getPost('reason');
        $purchase_type = $this->request->getPost('purchase_type');
        $file = $this->request->getFile('file');
        $originalName = $file->getClientName();
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
            //check purchase type
            if($purchase_type=="Local Purchase")
            {
                if(empty($originalName))
                {
                    session()->setFlashdata('fail','Error! Please attach the required documents');
                    return redirect()->to('/orders')->withInput();
                }
                else
                {
                    //save the prf data
                    if(date("h:i:s a")<="02:00:00 pm")
                    {
                        $values = [
                            'OrderNo'=>$code,'accountID'=>$user, 'DatePrepared'=>$datePrepared,'ItemGroup'=>$itemGroup,'Department'=>$dept,
                            'DateNeeded'=>$dateNeeded,'Reason'=>$reason,'Status'=>0,'DateCreated'=>date('Y-m-d'),
                            'PurchaseType'=>$purchase_type,'Attachment'=>$originalName,'Remarks'=>'OPEN',
                        ];
                        $purchaseModel->save($values);
                        $file->move('Attachment/',$originalName);
                    }
                    else
                    {
                        $values = [
                            'OrderNo'=>$code,'accountID'=>$user, 'DatePrepared'=>$tomorrow,'ItemGroup'=>$itemGroup,'Department'=>$dept,
                            'DateNeeded'=>$dateNeeded,'Reason'=>$reason,'Status'=>0,'DateCreated'=>date('Y-m-d'),
                            'PurchaseType'=>$purchase_type,'Attachment'=>$originalName,'Remarks'=>'OPEN',
                        ];
                        $purchaseModel->save($values);
                        $file->move('Attachment/',$originalName);
                    }  
                    
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
                    if(date("h:i:s a")<="02:00:00 pm"){
                        $value = [
                            'accountID'=>$approver_user,'OrderNo'=>$code,'DateReceived'=>date('Y-m-d'),'Status'=>0,
                            'DateApproved'=>"0000-00-00",'Comment'=>''
                        ];
                        $reviewModel->save($value);
                    }
                    else
                    {
                        $value = [
                            'accountID'=>$approver_user,'OrderNo'=>$code,'DateReceived'=>$tomorrow,'Status'=>0,
                            'DateApproved'=>"0000-00-00",'Comment'=>''
                        ];
                        $reviewModel->save($value);
                    }
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
            else
            {
                //save the prf data
                if(date("h:i:s a")<="02:00:00 pm")
                {
                    if(empty($originalName))
                    {
                        $values = [
                            'OrderNo'=>$code,'accountID'=>$user, 'DatePrepared'=>$datePrepared,'ItemGroup'=>$itemGroup,'Department'=>$dept,
                            'DateNeeded'=>$dateNeeded,'Reason'=>$reason,'Status'=>0,'DateCreated'=>date('Y-m-d'),
                            'PurchaseType'=>$purchase_type,'Attachment'=>'N/A','Remarks'=>'OPEN',
                        ];
                        $purchaseModel->save($values);
                    }
                    else
                    {
                        $values = [
                            'OrderNo'=>$code,'accountID'=>$user, 'DatePrepared'=>$datePrepared,'ItemGroup'=>$itemGroup,'Department'=>$dept,
                            'DateNeeded'=>$dateNeeded,'Reason'=>$reason,'Status'=>0,'DateCreated'=>date('Y-m-d'),
                            'PurchaseType'=>$purchase_type,'Attachment'=>$originalName,'Remarks'=>'OPEN',
                        ];
                        $purchaseModel->save($values);
                        $file->move('Attachment/',$originalName);
                    }
                }
                else
                {
                    if(empty($originalName))
                    {
                        $values = [
                            'OrderNo'=>$code,'accountID'=>$user, 'DatePrepared'=>$tomorrow,'ItemGroup'=>$itemGroup,'Department'=>$dept,
                            'DateNeeded'=>$dateNeeded,'Reason'=>$reason,'Status'=>0,'DateCreated'=>date('Y-m-d'),
                            'PurchaseType'=>$purchase_type,'Attachment'=>'N/A','Remarks'=>'OPEN',
                        ];
                        $purchaseModel->save($values);
                    }
                    else
                    {
                        $values = [
                            'OrderNo'=>$code,'accountID'=>$user, 'DatePrepared'=>$tomorrow,'ItemGroup'=>$itemGroup,'Department'=>$dept,
                            'DateNeeded'=>$dateNeeded,'Reason'=>$reason,'Status'=>0,'DateCreated'=>date('Y-m-d'),
                            'PurchaseType'=>$purchase_type,'Attachment'=>$originalName,'Remarks'=>'OPEN',
                        ];
                        $purchaseModel->save($values);
                        $file->move('Attachment/',$originalName);
                    }
                }  
                
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
                if(date("h:i:s a")<="02:00:00 pm"){
                    $value = [
                        'accountID'=>$approver_user,'OrderNo'=>$code,'DateReceived'=>date('Y-m-d'),'Status'=>0,
                        'DateApproved'=>"0000-00-00",'Comment'=>''
                    ];
                    $reviewModel->save($value);
                }
                else
                {
                    $value = [
                        'accountID'=>$approver_user,'OrderNo'=>$code,'DateReceived'=>$tomorrow,'Status'=>0,
                        'DateApproved'=>"0000-00-00",'Comment'=>''
                    ];
                    $reviewModel->save($value);
                }
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
        <table class="table table-bordered stripe hover nowrap" id="table1">
            <thead>
                <th class="bg-primary text-white">Product Name</th>
                <th class="bg-primary text-white">Qty</th>
                <th class="bg-primary text-white">Item Unit</th>
                <th class="bg-primary text-white">Specification</th>
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
        if(str_contains(session()->get('location'), 'FCM'))
        {
            $builder = $this->db->table('tblaccount');
            $builder->select('*');
            $builder->WHERE('Status',1)->WHERE('systemRole','Editor')->WHERE('warehouseID',session()->get('assignment'));
            $data = $builder->get();
            foreach($data->getResult() as $row)
            {
                ?>
                <option value="<?php echo $row->accountID ?>"><?php echo $row->Fullname ?> - <?php echo $row->Department ?></option>
                <?php
            }
        }
        else
        {
            $user = session()->get('loggedUser');
            $role = ['Editor','Administrator'];
            $builder = $this->db->table('tblaccount');
            $builder->select('*');
            $builder->WHERE('Status',1)->WHEREIN('systemRole',$role)->WHERE('Department!=','')->WHERE('accountID<>',$user);
            $data = $builder->get();
            foreach($data->getResult() as $row)
            {
                ?>
                <option value="<?php echo $row->accountID ?>"><?php echo $row->Fullname ?> - <?php echo $row->Department ?></option>
                <?php
            }
        }
    }

    public function purchaseNotification()
    {
        $user = session()->get('loggedUser');
        $prf=0;$purchase_order=0;
        $builder = $this->db->table('tblreview');
        $builder->select('COUNT(reviewID)total');
        $builder->WHERE('Status',0)->WHERE('accountID',$user);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            echo $row->total;
        }
    }

    public function notification()
    {
        $user = session()->get('loggedUser');
        $prf=0;$purchase_order=0;
        $builder = $this->db->table('tblreview');
        $builder->select('COUNT(reviewID)total');
        $builder->WHERE('Status',0)->WHERE('accountID',$user);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $prf = $row->total;
        }

        $builder = $this->db->table('tblpurchase_review');
        $builder->select('COUNT(prID)total');
        $builder->WHERE('Status',0)->WHERE('accountID',$user);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $purchase_order = $row->total;
        }
        echo $purchase_order+ $prf;
    }

    public function canvasNotification()
    {
        $user = session()->get('loggedUser');
        $builder = $this->db->table('tblcanvass_review');
        $builder->select('COUNT(crID)total');
        $builder->WHERE('Status',0)->WHERE('accountID',$user);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            echo $row->total;
        }
    }

    public function PONotification()
    {
        $user = session()->get('loggedUser');
        $builder = $this->db->table('tblpurchase_review');
        $builder->select('COUNT(prID)total');
        $builder->WHERE('Status',0)->WHERE('accountID',$user);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            echo $row->total;
        }
    }

    public function totalNotification()
    {
        $user = session()->get('loggedUser');
        $canvass=0;$prf=0;$purchase_order=0;
        $builder = $this->db->table('tblcanvass_review');
        $builder->select('COUNT(crID)total');
        $builder->WHERE('Status',0)->WHERE('accountID',$user);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $canvass = $row->total;
        }

        $builder = $this->db->table('tblreview');
        $builder->select('COUNT(reviewID)total');
        $builder->WHERE('Status',0)->WHERE('accountID',$user);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $prf = $row->total;
        }

        $builder = $this->db->table('tblpurchase_review');
        $builder->select('COUNT(prID)total');
        $builder->WHERE('Status',0)->WHERE('accountID',$user);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $purchase_order = $row->total;
        }
        echo $prf + $canvass + $purchase_order;
    }

    public function viewQuotation()
    {
        $reference = $this->request->getGet('value');
        $file="";$orderNo="";
        //fetch
        $builder = $this->db->table('tblcanvass_form a');
        $builder->select('a.Reference,a.Attachment as quotation,b.Department,b.OrderNo,b.DateNeeded,b.PurchaseType,b.Reason,b.Attachment,d.Status,d.prID');
        $builder->join('tblprf b','b.OrderNo=a.OrderNo','LEFT');
        $builder->join('tblpurchase_logs c','a.Reference=c.Reference','LEFT');
        $builder->join('tblpurchase_review d','d.purchaseNumber=c.purchaseNumber','LEFT');
        $builder->WHERE('c.purchaseNumber',$reference);
        $datax = $builder->get();
        if($rowx = $datax->getRow())
        {
            $file = $rowx->Attachment;
            $quotation = $rowx->quotation;
            ?>
        <div class="tab">
            <ul class="nav nav-pills" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active text-blue" data-toggle="tab" href="#items" role="tab" aria-selected="true">Ordered Items</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-blue" data-toggle="tab" href="#prf" role="tab" aria-selected="true">PRF Attachment</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-blue" data-toggle="tab" href="#quotation" role="tab" aria-selected="true">Quotation</a>
                </li>
            </ul>
        <?php
        }
        $builder = $this->db->table('tblcanvass_sheet a');
        $builder->select('a.*,b.Qty,b.ItemUnit,b.Item_Name,b.Specification');
        $builder->join('tbl_order_item b','b.orderID=a.orderID','INNER');
        $builder->join('tblpurchase_logs c','c.purchaseLogID=a.purchaseLogID','INNER');
        $builder->WHERE('c.purchaseNumber',$reference);
        $data = $builder->get();
        ?>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="items" role="tabpanel">
                    <br/>
                    <div class="form-group table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <th class="bg-primary text-white">Item(s)</th>
                                <th class="bg-primary text-white">Qty</th>
                                <th class="bg-primary text-white">Unit Price</th>
                                <th class="bg-primary text-white">Total Price</th>
                                <th class="bg-primary text-white">Specification</th>
                                <th class="bg-primary text-white">Vendor(s)</th>
                                <th class="bg-primary text-white">Terms</th>
                            </thead>
                            <tbody>
                    <?php
                    foreach($data->getResult() as $row)
                    {
                        ?>
                        <tr>
                            <td><?php echo $row->Item_Name ?></td>
                            <td><?php echo $row->Qty ?></td>
                            <td style="text-align:right;"><?php echo number_format($row->Price,2) ?></td>
                            <td style="text-align:right;"><?php echo number_format($row->Qty*$row->Price,2) ?></td>
                            <td><?php echo $row->Specification ?></td>
                            <td><?php echo $row->Supplier ?></td>
                            <td><?php echo $row->Terms ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade show" id="prf" role="tabpanel">
                    <br/>
                    <object data="Attachment/<?php echo $file ?>" type="application/pdf" style="width:100%;height:500px;">
                        <div>No PDF viewer available</div>
                    </object>
                </div>
                <div class="tab-pane fade show" id="quotation" role="tabpanel">
                    <br/>
                    <object data="Canvass/<?php echo $quotation ?>" type="application/pdf" style="width:100%;height:500px;">
                        <div>No PDF viewer available</div>
                    </object>
                </div>
            </div>
        </div>
        <?php if($rowx->Status==0){ ?>
            <button type="button" class="btn btn-primary btn-sm approve" value="<?php echo $rowx->prID ?>"><span class="dw dw-check"></span>&nbsp;Approve</button>
            <button type="button" class="btn btn-danger btn-sm decline" value="<?php echo $rowx->prID ?>"><span class="dw dw-trash"></span>&nbsp;Decline</button>
        <?php } ?>
        <?php
    }

    public function viewPurchase()
    {
        $val = $this->request->getGet('value');
        $builder = $this->db->table('tblreview a');
        $builder->select('a.reviewID,a.Status,b.OrderNo,b.Department,b.Reason,b.DateNeeded,c.Fullname,b.PurchaseType,d.warehouseName,b.Attachment');
        $builder->join('tblprf b','b.OrderNo=a.OrderNo','LEFT');
        $builder->join('tblaccount c','b.accountID=c.accountID','LEFT');
        $builder->join('tblwarehouse d','d.warehouseID=c.warehouseID','LEFT');
        $builder->WHERE('a.reviewID',$val);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            ?>
            <form method="post" class="row g-3" id="frmReview">
                <input type="hidden" name="reviewID" id="reviewID" value="<?php echo $row->reviewID ?>"/>
                <input type="hidden" name="location" id="location" value="<?php echo $row->warehouseName ?>"/>
                <div class="col-12 form-group">
                    <div class="row g-3">
                        <div class="col-lg-8 form-group">
                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <label>Type of Purchase</label>
                                    <select class="form-control" name="purchase_type" id="purchase_type">
                                        <option value="">Choose</option>
                                        <option <?php if($row->PurchaseType=="Local Purchase") echo 'selected="selected"'; ?>>Local Purchase</option>
                                        <option <?php if($row->PurchaseType=="Regular Purchase") echo 'selected="selected"'; ?>>Regular Purchase</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label>PRF Number</label>
                                    <input type="text" class="form-control" name="orderno" value="<?php echo $row->OrderNo ?>"/>
                                </div>
                                <div class="col-lg-4">
                                    <label>Date Needed</label>
                                    <input type="date" class="form-control" name="dateneeded" value="<?php echo $row->DateNeeded ?>"/>
                                </div>
                            </div>
                            <label>Reason</label>
                            <textarea name="reason" id="reason" class="form-control" style="height:120px;"><?php echo $row->Reason ?></textarea>
                            <br/>
                            <label>Attachment</label>
                            <div><a href="Attachment/<?php echo $row->Attachment ?>" target="_BLANK"><span class="dw dw-paperclip"></span>&nbsp;Attachment</a></div>
                        </div>
                        <div class="col-lg-4 form-group table-responsive">
                            <table class="table table-striped table-bordered hover nowrap">
                                <thead>
                                    <th class="bg-primary text-white">Department Head</th>
                                    <th class="bg-primary text-white">Date</th>
                                </thead>
                                <tbody>
                                    <?php
                                    $builder = $this->db->table('tblreview a');
                                    $builder->select('a.DateApproved,b.Fullname');
                                    $builder->join('tblaccount b','b.accountID=a.accountID','LEFT');
                                    $builder->WHERE('a.OrderNo',$row->OrderNo);
                                    $datas = $builder->get();
                                    foreach($datas->getResult() as $rows)
                                    {
                                        ?>
                                        <tr>
                                            <td><?php echo $rows->Fullname ?></td>
                                            <td><?php echo $rows->DateApproved ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>  
                        </div>
                    </div>
                </div>
                <div class="col-12 form-group table-responsive">
                    <table class="table table-striped table-bordered hover nowrap">
                        <thead>
                            <th class="bg-primary text-white">Item(s)</th>
                            <th class="bg-primary text-white">Unit(s)</th>
                            <th class="bg-primary text-white">Qty</th>
                            <th class="bg-primary text-white">Specification(s)</th>
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
                </div>`
                <?php if(str_contains(session()->get('location'), 'FCM')){ ?>
                <div class="col-12 form-group">
                    <label>Department Head</label>
                    <select class="form-control" name="departmentHead" id="departmentHead">
                        <option value="">Choose</option>
                    </select>
                </div>
                <?php }?>
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
        $typePurchase = $this->request->getPost('purchase_type');
        $deptHead = $this->request->getPost('departmentHead');
        $location = $this->request->getPost('location');
        $user = session()->get('loggedUser');
        
        if(str_contains($location, 'FCM'))
        {
            if(empty($deptHead) && str_contains(session()->get('location'), 'FCM'))
            {
                echo "Invalid! Please select Department Head for next approval";
            }
            else
            {
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
                        $value = ['Status'=>4];
                        $purchaseModel->update($purchase['prfID'],$value);
                        //save logs
                        $values = [
                            'accountID'=>$user,'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Accepted '.$row->OrderNo
                        ];
                        $systemLogsModel->save($values);
                        //send email to procurement dept head
                        $builder = $this->db->table('tblaccount');
                        $builder->select('*');
                        $builder->WHERE('accountID',$deptHead);
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
                    else if($row->Status==4)
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
                        $value = ['Status'=>3,'PurchaseType'=>$typePurchase];
                        $purchaseModel->update($purchase['prfID'],$value);
                        //save logs
                        $values = [
                            'accountID'=>$user,'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Accepted '.$row->OrderNo
                        ];
                        $systemLogsModel->save($values);
                    }
                }
                $values = [
                    'Status'=>1,'DateApproved'=>date('Y-m-d')
                ];
                $reviewModel->update($val,$values);
                echo "success";
            }
        }
        else
        {
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
                    $value = ['Status'=>3,'PurchaseType'=>$typePurchase];
                    $purchaseModel->update($purchase['prfID'],$value);
                    //save logs
                    $values = [
                        'accountID'=>$user,'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Accepted '.$row->OrderNo
                    ];
                    $systemLogsModel->save($values);
                }
            }
            $values = [
                'Status'=>1,'DateApproved'=>date('Y-m-d')
            ];
            $reviewModel->update($val,$values);
            echo "success";
        }  
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
        if(empty($msg))
        {
            echo "Invalid! Please leave a message";
        }
        else
        {
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
    }

    public function archivePurchase()
    {
        $purchaseModel = new \App\Models\purchaseModel();
        $val = $this->request->getPost('value');
        $purchase = $purchaseModel->WHERE('OrderNo',$val)->first();
        $values = ['Status'=>5,'Remarks'=>'CLOSE'];
        $purchaseModel->update($purchase['prfID'],$values);
        echo "success";
    }

    public function CancelPurchase()
    {
        $systemLogsModel = new \App\Models\systemLogsModel();
        $purchaseModel = new \App\Models\purchaseModel();
        $accountModel = new \App\Models\accountModel();
        $assignmentModel = new \App\Models\assignmentModel();
        //data
        $val = $this->request->getPost('value');
        $msg = $this->request->getPost('message');
        $user = session()->get('loggedUser');
        if(empty($msg))
        {
            echo "Invalid! Please try again";
        }
        else
        {
            //update
            $purchase = $purchaseModel->WHERE('OrderNo',$val)->first();
            $value = ['Status'=>2];
            $purchaseModel->update($purchase['prfID'],$value);
            //update the assigned PRF
            $assign = $assignmentModel->WHERE('prfID',$purchase['prfID'])->first();
            $values = ['Status'=>2];
            $assignmentModel->update($assign['assignID'],$values);
            //send an email to the requestor
            $account = $accountModel->WHERE('accountID',$purchase['accountID'])->first();
            $email = \Config\Services::email();
            $email->setTo($account['Email'],$account['Fullname']);
            $email->setFrom("fastcat.system@gmail.com","FastCat");
            $imgURL = "assets/img/fastcat.png";
            $email->attach($imgURL);
            $cid = $email->setAttachmentCID($imgURL);
            $template = "<center>
            <img src='cid:". $cid ."' width='100'/>
            <table style='padding:10px;background-color:#ffffff;' border='0'><tbody>
            <tr><td><center><h1>For Issuance</h1></center></td></tr>
            <tr><td><center>Hi, ".$account['Fullname']."</center></td></tr>
            <tr><td><center>This is from FastCat System, sending you a message that your request PRF No : ".$val." has been rejected.</center></td></tr>
            <tr><td><p><center>Please see the comment below :</center></p></td><tr>
            <tr><td><p><center>Reason : ".$msg."</center></p></td><tr>
            <tr><td><center>Please login to your account @ https:fastcat-ims.com.</center></td></tr>
            <tr><td><center>This is a system message please don't reply. Thank you</center></td></tr>
            <tr><td><center>FastCat IT Support</center></td></tr></tbody></table></center>";
            $subject = "Declined PRF";
            $email->setSubject($subject);
            $email->setMessage($template);
            $email->send();
            //system logs
            $values = [
                'accountID'=>$user,'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Cancelled '.$val
            ];
            $systemLogsModel->save($values);
            echo "success";
        }
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
        $builder->WHERE('b.OrderNo',$val)->WHERE('b.Reference','');
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
        $supplierModel = new \App\Models\supplierModel();
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
        $vatable = $this->request->getPost('vatable');
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
            if($vatable=="Yes")
            {
                $values = [
                    'OrderNo'=>$orderNo, 'orderID'=>$item,'Supplier'=>$supplier,
                    'Price'=>$unitPrice/1.12,'Currency'=>'PHP','ContactPerson'=>$contactPerson,'Address'=>$address,
                    'ContactNumber'=>$phone,'Terms'=>$terms,'Warranty'=>$warranty,
                    'Reference'=>'','Remarks'=>'','Vatable'=>$vatable,'purchaseLogID'=>0
                ];
                $canvassModel->save($values);
            }
            else
            {
                $values = [
                    'OrderNo'=>$orderNo, 'orderID'=>$item,'Supplier'=>$supplier,
                    'Price'=>$unitPrice,'Currency'=>'PHP','ContactPerson'=>$contactPerson,'Address'=>$address,
                    'ContactNumber'=>$phone,'Terms'=>$terms,'Warranty'=>$warranty,
                    'Reference'=>'','Remarks'=>'','Vatable'=>$vatable,'purchaseLogID'=>0
                ];
                $canvassModel->save($values);
            }
            //validate if supplier already exist
            $builder = $this->db->table('tblsupplier');
            $builder->select('*');
            $builder->WHERE('supplierName',$supplier);
            $data = $builder->get();
            if($row = $data->getRow())
            {
                //do nothing
            }
            else
            {
                $values = [
                    'supplierName'=>$supplier,'Address'=>$address,
                    'contactPerson'=>$contactPerson,'EmailAddress'=>"N/A",'contactNumber'=>$phone,
                    'industryID'=>0,
                ];
                $supplierModel->save($values);
            }
            echo "success";
        }
    }

    public function loadEntries()
    {
        $id = $this->request->getGet('value');
        $sql = "Select a.* from tbl_order_item a WHERE a.OrderNo=:id: AND NOT EXISTS (Select b.orderID from tblcanvass_sheet b WHERE b.orderID=a.orderID)";
        $query = $this->db->query($sql,['id'=>$id]);
        foreach ($query->getResult() as $row) 
        {
            ?>
            <tr>
                <td><input type="checkbox" style="height:18px;width:18px;" value="<?php echo $row->orderID ?>" name="itemID[]" id="itemID" checked/></td>
                <td><?php echo $row->Item_Name ?></td>
                <td><?php echo $row->Specification ?></td>
                <td><input type="text" class="form-control" name="unitPrice[]"/></td>
            </tr>
            <?php
        }
    }

    public function saveEntries()
    {
        $canvassModel = new \App\Models\canvassModel();
        $supplierModel = new \App\Models\supplierModel();
        //datas
        $orderNo = $this->request->getPost('orderNo');
        $unitPrice = $this->request->getPost('unitPrice');
        $supplier = $this->request->getPost('supplier');
        $contactPerson = $this->request->getPost('contactPerson');
        $address = $this->request->getPost('address');
        $phone = $this->request->getPost('phone');
        $terms = $this->request->getPost('terms');
        $warranty = $this->request->getPost('warranty');
        $vatable = $this->request->getPost('vatable');
        $orderID = $this->request->getPost('itemID');
        $currency = $this->request->getPost('currency');
        $count = count($orderID);

        $validation = $this->validate([
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
            if($vatable=="VAT INC")
            {
                for($i=0;$i<$count;$i++)
                {
                    $values = 
                    ['OrderNo'=>$orderNo, 'orderID'=>$orderID[$i],'Supplier'=>$supplier,'Price'=>$unitPrice[$i]/1.12,
                    'Currency'=>$currency,'ContactPerson'=>$contactPerson,'ContactNumber'=>$phone,'Address'=>$address,
                    'Terms'=>$terms,'Warranty'=>$warranty,'Reference'=>'','Remarks'=>'','Vatable'=>$vatable,'purchaseLogID'=>0];
                    $canvassModel->save($values);
                }
            }
            else if($vatable=="VAT EX")
            {
                for($i=0;$i<$count;$i++)
                {
                    $values = 
                    ['OrderNo'=>$orderNo, 'orderID'=>$orderID[$i],'Supplier'=>$supplier,'Price'=>$unitPrice[$i]*1.12,
                    'Currency'=>$currency,'ContactPerson'=>$contactPerson,'ContactNumber'=>$phone,'Address'=>$address,
                    'Terms'=>$terms,'Warranty'=>$warranty,'Reference'=>'','Remarks'=>'','Vatable'=>$vatable,'purchaseLogID'=>0];
                    $canvassModel->save($values);
                }
            }
            else
            {
                for($i=0;$i<$count;$i++)
                {
                    $values = 
                    ['OrderNo'=>$orderNo, 'orderID'=>$orderID[$i],'Supplier'=>$supplier,'Price'=>$unitPrice[$i],
                    'Currency'=>$currency,'ContactPerson'=>$contactPerson,'ContactNumber'=>$phone,'Address'=>$address,
                    'Terms'=>$terms,'Warranty'=>$warranty,'Reference'=>'','Remarks'=>'','Vatable'=>$vatable,'purchaseLogID'=>0];
                    $canvassModel->save($values);
                }
            }
            //validate if supplier already exist
            $builder = $this->db->table('tblsupplier');
            $builder->select('*');
            $builder->WHERE('supplierName',$supplier);
            $data = $builder->get();
            if($row = $data->getRow())
            {
                //do nothing
            }
            else
            {
                $values = [
                    'supplierName'=>$supplier,'Address'=>$address,
                    'contactPerson'=>$contactPerson,'EmailAddress'=>"N/A",'contactNumber'=>$phone,
                    'industryID'=>0,
                ];
                $supplierModel->save($values);
            }
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
            <li class="col-lg-4 col-md-6 col-sm-12">
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
        $canvassModel = new \App\Models\canvassModel();
        $reviewCanvassModel = new \App\Models\reviewCanvassModel();
        //data
        $user = session()->get('loggedUser');
        $datePrepared = $this->request->getPost('datePrepared');
        $dateNeeded = $this->request->getPost('dateNeeded');
        $OrderNo = $this->request->getPost('OrderNo');
        $department = $this->request->getPost('department');
        $deptHead = $this->request->getPost('approver');
        $requestor = $this->request->getPost('requestor');
        $type_purchase = $this->request->getPost('type_purchase');
        $file = $this->request->getFile('file');
        $originalName = $file->getClientName();

        $validation = $this->validate([
            'datePrepared'=>'required','dateNeeded'=>'required',
            'OrderNo'=>'required','department'=>'required',
            'approver'=>'required',
        ]);
        if(!$validation)
        {
            session()->setFlashdata('fail','Invalid! Please something went wrong. Please try again');
            return redirect()->to('/create/'.$OrderNo)->withInput();
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
            if($type_purchase=="Local Purchase")
            {
                //save the records
                $records = [
                    'Reference'=>$code, 'accountID'=>$requestor,'DatePrepared'=>$datePrepared,
                    'DateNeeded'=>$dateNeeded,'OrderNo'=>$OrderNo,'Department'=>$department,
                    'Status'=>0,'createdBy'=>$user,'Attachment'=>$originalName
                ];
                $canvassForm->save($records);
                //update the list of vendors status and reference
                $builder = $this->db->table('tblcanvass_sheet');
                $builder->select('canvassID');
                $builder->WHERE('OrderNo',$OrderNo);
                $data = $builder->get();
                foreach($data->getResult() as $row)
                {
                    $values = [
                        'Reference'=>$code
                    ];
                    $canvassModel->update($row->canvassID,$values);
                }
                //send to approver
                $value = ['accountID'=>$deptHead,'Reference'=>$code,'DateReceived'=>date('Y-m-d'),'Status'=>0,'DateApproved'=>''];
                $reviewCanvassModel->save($value);
                //send email
                $builder = $this->db->table('tblaccount');
                $builder->select('*');
                $builder->WHERE('accountID',$deptHead);
                $datas = $builder->get();
                if($rows = $datas->getRow())
                {
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
                    <tr><td><center><h1>Canvass Sheet Form</h1></center></td></tr>
                    <tr><td><center>Hi, ".$rows->Fullname."</center></td></tr>
                    <tr><td><center>This is from FastCat System, sending you a reminder that requesting for your approval.</center></td></tr>
                    <tr><td><p><center><b>Reference No : ".$code."</b></center></p></td><tr>
                    <tr><td><center>Please login to your account @ https:fastcat-ims.com.</center></td></tr>
                    <tr><td><center>This is a system message please don't reply. Thank you</center></td></tr>
                    <tr><td><center>FastCat IT Support</center></td></tr></tbody></table></center>";
                    $subject = "Canvass Sheet Form - For Approval";
                    $email->setSubject($subject);
                    $email->setMessage($template);
                    $email->send();
                }
                $file->move('Canvass/',$originalName);
                session()->setFlashdata('success','Great! Successfully submitted to review');
                return redirect()->to('/list-orders')->withInput();
            }
            else
            {
                if(empty($originalName))
                {
                    session()->setFlashdata('fail','Invalid! Please attach required document(s)');
                    return redirect()->to('/create/'.$OrderNo)->withInput();
                }
                else
                {
                    //save the records
                    $records = [
                        'Reference'=>$code, 'accountID'=>$requestor,'DatePrepared'=>$datePrepared,
                        'DateNeeded'=>$dateNeeded,'OrderNo'=>$OrderNo,'Department'=>$department,
                        'Status'=>0,'createdBy'=>$user,'Attachment'=>$originalName
                    ];
                    $canvassForm->save($records);
                    //update the list of vendors status and reference
                    $builder = $this->db->table('tblcanvass_sheet');
                    $builder->select('canvassID');
                    $builder->WHERE('OrderNo',$OrderNo);
                    $data = $builder->get();
                    foreach($data->getResult() as $row)
                    {
                        $values = [
                            'Reference'=>$code,'Remarks'=>'Selected'
                        ];
                        $canvassModel->update($row->canvassID,$values);
                    }
                    //send to approver
                    $value = ['accountID'=>$deptHead,'Reference'=>$code,'DateReceived'=>date('Y-m-d'),'Status'=>0,'DateApproved'=>''];
                    $reviewCanvassModel->save($value);
                    //send email
                    $builder = $this->db->table('tblaccount');
                    $builder->select('*');
                    $builder->WHERE('accountID',$deptHead);
                    $datas = $builder->get();
                    if($rows = $datas->getRow())
                    {
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
                        <tr><td><center><h1>Canvass Sheet Form</h1></center></td></tr>
                        <tr><td><center>Hi, ".$rows->Fullname."</center></td></tr>
                        <tr><td><center>This is from FastCat System, sending you a reminder that requesting for your approval.</center></td></tr>
                        <tr><td><p><center><b>Reference No : ".$code."</b></center></p></td><tr>
                        <tr><td><center>Please login to your account @ https:fastcat-ims.com.</center></td></tr>
                        <tr><td><center>This is a system message please don't reply. Thank you</center></td></tr>
                        <tr><td><center>FastCat IT Support</center></td></tr></tbody></table></center>";
                        $subject = "Canvass Sheet Form - For Approval";
                        $email->setSubject($subject);
                        $email->setMessage($template);
                        $email->send();
                    }
                    $file->move('Canvass/',$originalName);
                    session()->setFlashdata('success','Great! Successfully submitted to review');
                    return redirect()->to('/assign')->withInput();
                }
            }
        }
    }

    public function saveEntry()
    {
        $OrderItemModel = new \App\Models\OrderItemModel();
        date_default_timezone_set('Asia/Manila');
        $reservedModel = new \App\Models\reservedModel();
        $systemLogsModel = new \App\Models\systemLogsModel();
        $purchaseOrderModel = new \App\Models\purchaseOrderModel();
        $receiveModel = new \App\Models\receiveModel();
        $purchaseModel = new \App\Models\purchaseModel();
        //data
        $job_number = $this->request->getPost('job_number');
        $purchase_number = $this->request->getPost('purchase_number');
        $invoiceNo = $this->request->getPost('invoice_number');
        $invoiceAmt = $this->request->getPost('invoice_amount');
        $shipper = $this->request->getPost('shipper');
        $dateReceive = $this->request->getPost('date_receive');
        $remarks = $this->request->getPost('remarks');
        $receiver = $this->request->getPost('receiver');
        $assign = $this->request->getPost('assignment');
        //array
        $itemID = $this->request->getPost('itemID');
        $qty = $this->request->getPost('qty');

        $validation = $this->validate([
            'job_number'=>'required',
            'purchase_number'=>'required',
            'invoice_number'=>'required',
            'invoice_amount'=>'required',
            'shipper'=>'required',
            'date_receive'=>'required',
            'remarks'=>'required',
            'assignment'=>'required',
            'receiver'=>'required'
        ]);

        if(!$validation)
        {
            session()->setFlashdata('fail','Invalid! Please fill in the form and try again');
            return redirect()->to('/receive-order')->withInput();
        }
        else
        {
            $values = ['Date'=>$dateReceive,'OrderNo'=>$job_number,'purchaseNumber'=>$purchase_number,
                        'InvoiceNo'=>$invoiceNo,'InvoiceAmount'=>$invoiceAmt,
                        'supplierID'=>$shipper,'Remarks'=>$remarks,'Receiver'=>$receiver,'warehouseID'=>$assign];
            $receiveModel->save($values);

            //save items
            $count = count($itemID);
            for($i=0;$i<$count;$i++)
            {
                $items = $OrderItemModel->WHERE('orderID',$itemID[$i])->first();
                $value = ['Date'=>$dateReceive,'OrderNo'=>$job_number,'purchaseNumber'=>$purchase_number,
                        'InvoiceNo'=>$invoiceNo,'supplierID'=>$shipper,'productName'=>$items['Item_Name'],
                        'Qty'=>$qty[$i],'Available'=>$qty[$i],'ItemUnit'=>$items['ItemUnit'],
                        'Description'=>$items['Specification']];
                $reservedModel->save($value);
            }
            
            if($remarks=="Full Delivery")
            {
                $purchase = $purchaseOrderModel->WHERE('purchaseNumber',$purchase_number)->first();
                $values = ['Remarks'=>'CLOSE'];
                $purchaseOrderModel->update($purchase['purchaseLogID'],$values);
                //PRF
                $prf = $purchaseModel->WHERE('OrderNo',$job_number)->first();
                $new_values = ['Status'=>5,'Remarks'=>'CLOSE'];
                $purchaseModel->update($prf['prfID'],$new_values);
            }
            //system logs
            $value = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Received Order of '.$invoiceNo];
            $systemLogsModel->save($value);
            session()->setFlashdata('success','Great! Successfully submitted');
            return redirect()->to('/receive-order')->withInput();
        }
    }

    public function addComment()
    {
        $commentModel = new \App\Models\commentModel();
        //data
        $message = $this->request->getPost('message');
        $val = $this->request->getPost('value');

        $validation = $this->validate([
            'value'=>'required|is_unique[tblcomment.Reference]'
        ]);

        if(!$validation)
        {
            echo "Invalid! Delivery instruction Already Added";
        }
        else
        {
            if(empty($message))
            {
                echo "Invalid! Please enter your delivery instruction";
            }
            else
            {
                $values = ['Reference'=>$val,'Message'=>$message];
                $commentModel->save($values);
                echo "success";
            }
        }
    }

    public function updateOrder()
    {
        $OrderItemModel = new \App\Models\OrderItemModel();
        $systemLogsModel = new \App\Models\systemLogsModel();
        //data
        $prf = $this->request->getPost('prf');
        $itemID = $this->request->getPost('itemID');
        $qty = $this->request->getPost('qty');
        $item = $this->request->getPost('item');
        $item_name = $this->request->getPost('item_name');
        $spec = $this->request->getPost('specification');

        $count = count($itemID);
        for($i=0;$i<$count;$i++)
        {
            $values = [
                'Qty'=>$qty[$i],'ItemUnit'=>$item[$i],'Item_Name'=>$item_name[$i],
                'Specification'=>$spec[$i],
            ];
            $OrderItemModel->update($itemID[$i],$values);
        }
        $value = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Update the Ordered items of '.$prf];
        $systemLogsModel->save($value);
        if(session()->get('role')=="Staff")
        {
            session()->setFlashdata('success','Great! Successfully update the ordered item(s)');
            return redirect()->to('/assign')->withInput();
        }
        else
        {
            session()->setFlashdata('success','Great! Successfully update the ordered item(s)');
            return redirect()->to('/approve-orders')->withInput();
        }
    }

    public function fetchItems()
    {
        $val = $this->request->getGet('value');
        $sql = "Select c.* from tblpurchase_logs a 
        LEFT JOIN tblcanvass_sheet b ON b.purchaseLogID=a.purchaseLogID 
        LEFT JOIN tbl_order_item c ON c.orderID=b.orderID WHERE a.purchaseNumber=:val: 
        AND NOT EXISTS(Select d.productName from tblreserved d WHERE c.Item_Name=d.productName AND c.OrderNo=d.OrderNo)";
        $query = $this->db->query($sql,['val'=>$val]);
        foreach ($query->getResult() as $row)
        {
            ?>
            <tr>
                <td><input type="checkbox" class="checkbox" value="<?php echo $row->orderID ?>" name="itemID[]" id="itemID" style="width:20px;height:20px;" checked/></td>
                <td><input type='number' class='form-control' id='qty' name='qty[]'/></td>
                <td><input type='text' class='form-control' id='item' name='item[]' value="<?php echo $row->ItemUnit ?>"/></td>
                <td><input type='text' class='form-control' id='item_name' name='item_name[]' value="<?php echo $row->Item_Name ?>"/></td>
                <td><input type='text' class='form-control' id='specification' name='specification[]' value="<?php echo $row->Specification ?>"/></td>
            </tr>
            <?php
        }
    }

    public function forwardPRF()
    {
        $assignmentModel = new \App\Models\assignmentModel();
        $assignID = $this->request->getPost('assignID');
        $receiver = $this->request->getPost('receiver');
        $values = [
            'accountID'=>$receiver,'Date'=>date('Y-m-d')
        ];
        $assignmentModel->update($assignID,$values);
        $builder = $this->db->table('tblaccount');
        $builder->select('Fullname,Email');
        $builder->WHERE('accountID',$receiver);
        $data = $builder->get();
        if($row = $data->getRow())
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
            <tr><td><center><h1>Canvass Sheet Form</h1></center></td></tr>
            <tr><td><center>Hi, ".$row->Fullname."</center></td></tr>
            <tr><td><center>This is from FastCat System, sending you a reminder for creating of Canvass Sheet Form with the approved PRF</center></td></tr>
            <tr><td><center>Please login to your account @ https:fastcat-ims.com.</center></td></tr>
            <tr><td><center>This is a system message please don't reply. Thank you</center></td></tr>
            <tr><td><center>FastCat IT Support</center></td></tr></tbody></table></center>";
            $subject = "PRF - Canvass Sheet Form";
            $email->setSubject($subject);
            $email->setMessage($template);
            $email->send();
        }
        echo "success";
    }
}