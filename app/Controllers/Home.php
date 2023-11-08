<?php

namespace App\Controllers;
use App\Libraries\Hash;

class Home extends BaseController
{
    private $db;
    public function __construct()
    {
        $this->db = db_connect();
    }

    public function index()
    {
        if (session()->has('loggedUser'))
        {
            return redirect()->back();
        }
        else
        {
		  return view('welcome_message');
        }
    }

    public function dashboard()
    {
        //get the total volume per product name
        $builder = $this->db->table('tblinventory');
        $builder->select('productName,SUM(Qty)total');
        $builder->groupBy('productID')->orderBy('total','DESC')->limit(25);
        $query = $builder->get()->getResult();
        //total of item per assignment
        $builder = $this->db->table('tblwarehouse a');
        $builder->select('a.warehouseName,IFNULL(SUM(b.Qty),0)total');
        $builder->join('tblinventory b','b.warehouseID=a.warehouseID','LEFT');
        $builder->groupBy('a.warehouseID');
        $assign = $builder->get()->getResult();
        //categorized
        $builder = $this->db->table('tblcategory a');
        $builder->select('a.categoryName,COUNT(b.inventID)total');
        $builder->join('tblinventory b','b.categoryID=a.categoryID','LEFT');
        $builder->groupBy('a.categoryID');
        $category = $builder->get()->getResult();
        $data = ['query'=>$query,'assignment'=>$assign,'category'=>$category,];
        return view('dashboard',$data);
    }

    public function stocks()
    {
        //get all the stocks
        $builder = $this->db->table('tblinventory a');
        $builder->select('a.*,b.categoryName,c.supplierName,d.warehouseName');
        $builder->join('tblcategory b','b.categoryID=a.categoryID','LEFT');
        $builder->join('tblsupplier c','c.supplierID=a.supplierID','LEFT');
        $builder->join('tblwarehouse d','d.warehouseID=a.warehouseID','LEFT');
        $builder->groupby('a.warehouseID,a.inventID');
        $builder->orderby('a.Date');
        $items = $builder->get()->getResult();
        $data = ['items'=>$items];
        return view('all-stocks',$data);
    }

    public function edit($id=null)
    {
        //warehouse
        $builder = $this->db->table('tblwarehouse');
        $builder->select('*');
        $warehouse = $builder->get()->getResult();
        //supplier
        $builder = $this->db->table('tblsupplier');
        $builder->select('*');
        $supplier = $builder->get()->getResult();
        //category
        $builder = $this->db->table('tblcategory');
        $builder->select('*');
        $category = $builder->get()->getResult();
        $inventoryModel = new \App\Models\inventoryModel();
        $items = $inventoryModel->WHERE('inventID',$id)->first();
        $data = ['items'=>$items,'warehouse'=>$warehouse,'supplier'=>$supplier,'category'=>$category,];
        return view('edit',$data);
    }

    public function update()
    {
        $inventoryModel = new \App\Models\inventoryModel();
        $id = $this->request->getPost('itemID');
        $warehouse = $this->request->getPost('warehouse');
        $supplier = $this->request->getPost('supplier');
        $category = $this->request->getPost('category');
        $location = $this->request->getPost('location');
        $code = $this->request->getPost('code');
        $item_number = $this->request->getPost('itemNumber');
        $productName = $this->request->getPost('productName');
        $desc = $this->request->getPost('description');
        $values = [
            'Location'=>$location,'productID'=>$item_number,'productName'=>$productName,
            'Code'=>$code,'Description'=>$desc,
            'categoryID'=>$category,'supplierID'=>$supplier,'warehouseID'=>$warehouse,];
        $inventoryModel->update($id,$values);
        session()->setFlashdata('success','Great! Successfully updated');
        return redirect()->to('/stocks')->withInput();
    }

    public function manageStocks()
    {
        //damaged item
        $builder = $this->db->table('tbldamageitem a');
        $builder->select('a.*,b.productName,c.Fullname');
        $builder->join('tblinventory b','b.inventID=a.inventID','LEFT');
        $builder->join('tblaccount c','c.accountID=a.accountID','LEFT');
        $item = $builder->get()->getResult();
        //repair item
        $builder = $this->db->table('tblrepairitem a');
        $builder->select('a.*,b.productName,c.Fullname');
        $builder->join('tblinventory b','b.inventID=a.inventID','LEFT');
        $builder->join('tblaccount c','c.accountID=a.accountID','LEFT');
        $archive = $builder->get()->getResult();
        //transfer item
        $builder = $this->db->table('tbltransferitem');
        $builder->select('*');
        $transfer = $builder->get()->getResult();

        $data = ['items'=>$item,'archive'=>$archive,'transfer'=>$transfer,];
        return view('manage-stocks',$data);
    }

    public function createReport($id=null)
    {
        $builder = $this->db->table('tbldamageitem a');
        $builder->select('a.*,b.productName,c.Fullname');
        $builder->join('tblinventory b','b.inventID=a.inventID','LEFT');
        $builder->join('tblaccount c','c.accountID=a.accountID','LEFT');
        $builder->WHERE('a.damageID',$id);
        $item = $builder->get()->getResult();
        $data = ['item'=>$item];
        return view ('create-report',$data);
    }

    public function addItem()
    {
        //warehouse
        $builder = $this->db->table('tblwarehouse');
        $builder->select('*');
        $warehouse = $builder->get()->getResult();
        //supplier
        $builder = $this->db->table('tblsupplier');
        $builder->select('*');
        $supplier = $builder->get()->getResult();
        //category
        $builder = $this->db->table('tblcategory');
        $builder->select('*');
        $category = $builder->get()->getResult();
        $data = ['warehouse'=>$warehouse,'supplier'=>$supplier,'category'=>$category,];
        return view('add-stocks',$data);
    }

    public function addProduct()
    {
        $inventoryModel = new \App\Models\inventoryModel();
        $productImage = new \App\Models\productImageModel();
        //data
        $date = date('Y-m-d');
        $warehouse = $this->request->getPost('warehouse');
        $supplier = $this->request->getPost('supplier');
        $category = $this->request->getPost('category');
        $location = $this->request->getPost('location');
        $item_number = $this->request->getPost('item_number');
        $code = $this->request->getPost('productCode');
        $productName = $this->request->getPost('productName');
        $desc = $this->request->getPost('description');
        $itemUnit = $this->request->getPost('itemUnit');
        $unitPrice = $this->request->getPost('unitPrice');
        $qty = $this->request->getPost('qty');
        $reOrder = $this->request->getPost('reOrder');
        $expirationDate = $this->request->getPost('expirationDate');
        $validation = $this->validate([
            'warehouse'=>'required',
            'category'=>'required',
            'item_number'=>'required',
            'productName'=>'required|is_unique[tblinventory.productName]',
            'itemUnit'=>'required',
            'unitPrice'=>'required',
            'qty'=>'required'
        ]);
        if(!$validation)
        {
            session()->setFlashdata('fail',"Invalid! Please check the supplier's information before the submission");
            return redirect()->to('/add')->withInput();
        }
        else
        {
            if ($this->request->getFileMultiple('images')) 
            {
                $values = [
                    'Date'=>$date,'Location'=>$location,'productID'=>$item_number,'productName'=>$productName,
                    'Code'=>$code,'Description'=>$desc,'ItemUnit'=>$itemUnit,'unitPrice'=>$unitPrice,'Qty'=>$qty,'ReOrder'=>$reOrder,
                    'categoryID'=>$category,'ExpirationDate'=>$expirationDate,'supplierID'=>$supplier,'warehouseID'=>$warehouse,];
                $inventoryModel->save($values);
                //get the inventID
                $inventID=0;
                $builder = $this->db->table('tblinventory');
                $builder->select('inventID');
                $builder->WHERE('productID',$item_number)->WHERE('productName',$productName);
                $data = $builder->get();
                if($row = $data->getRow())
                {
                    $inventID = $row->inventID;
                }
                foreach($this->request->getFileMultiple('images') as $file)
                { 
                    $originalName = $file->getClientName();
                    $file->move('Products/',$originalName);
                    //save the images
                    $values = [
                        'inventID'=>$inventID,
                        'Images'=>$originalName,
                        'DateCreated'=>date('Y-m-d'),
                    ];
                    $productImage->save($values);
                }
                session()->setFlashdata('success',"Great! Successfully added");
                return redirect()->to('/add')->withInput();
            }
            else
            {
                session()->setFlashdata('fail',"Error! Something went wrong. Please contact IT Support");
                return redirect()->to('/add')->withInput();
            }
        }
    }

    public function transfer($id=null)
    {
        //warehouse
        $builder = $this->db->table('tblwarehouse');
        $builder->select('*');
        $warehouse = $builder->get()->getResult();
        //item
        $inventoryModel = new \App\Models\inventoryModel();
        $items = $inventoryModel->WHERE('inventID',$id)->first();
        
        $data = ['items'=>$items,'location'=>$warehouse,];
        return view('transfer-item',$data);
    }

    public function suppliers()
    {
        $builder = $this->db->table('tblsupplier a');
        $builder->select('a.*,b.Name');
        $builder->join('tblindustry b','b.industryID=a.industryID','LEFT');
        $record = $builder->get()->getResult();
        $data = [
            'record'=>$record,
        ];
        return view('supplier',$data);
    }

    public function editSupplier($id = null)
    {
        $supplierModel = new \App\Models\supplierModel();
        $record = $supplierModel->WHERE('supplierID',$id)->first();
        $data = [
            'record'=>$record,
        ];
        return view('edit-supplier',$data);
    }

    public function updateSupplier()
    {
        $supplierModel = new \App\Models\supplierModel();
        $id = $this->request->getPost('supplierID');
        $supplier_name = $this->request->getPost('supplier_name');
        $supplier_address = $this->request->getPost('address');
        $person = $this->request->getPost('contactPerson');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');
        $values = [
            'supplierName'=>$supplier_name,'Address'=>$supplier_address,
            'contactPerson'=>$person,'EmailAddress'=>$email,'contactNumber'=>$phone,
        ];
        $supplierModel->update($id,$values);
        session()->setFlashdata('success','Great! Successfully updated');
        return redirect()->to('/suppliers')->withInput();
    }
    
    public function addSupplier()
    {
        return view('add-supplier');
    }

    public function receiveItem()
    {
        $location = session()->get('assignment');
        $builder = $this->db->table('tbltransferitem');
        $builder->select('*');
        $builder->WHERE('warehouseID',$location);
        $items = $builder->get()->getResult();
        $data = ['items'=>$items,];
        return view('receive-item',$data);
    }

    public function addReport()
    {
        return view('add-report');
    }

    public function damageReport()
    {
        $builder = $this->db->table('tblinventory');
        $builder->select('*');
        $items = $builder->get()->getResult();
        $data = ['items'=>$items];
        return view('damage-report',$data);
    }

    public function systemConfiguration()
    {
        //get the user accounts
        $builder = $this->db->table('tblaccount');
        $builder->select('*');
        $account = $builder->get()->getResult();
        $data = ['account'=>$account,];
        return view('system-config',$data);
    }

    public function profile()
    {
        $user = session()->get('loggedUser');
        $builder = $this->db->table('tblaccount a');
        $builder->select('a.username,a.Fullname,a.systemRole,a.Status,b.warehouseName,b.Address');
        $builder->join('tblwarehouse b','b.warehouseID=a.warehouseID','LEFT');
        $builder->WHERE('a.accountID',$user);
        $account = $builder->get()->getResult();
        $data = ['account'=>$account];
        return view('profile',$data);
    }

    public function changePassword()
    {
        $accountModel = new \App\Models\accountModel();
        //data
        $userID = $this->request->getPost('userID');
        $password = $this->request->getPost('current_password');
        $retypepassword = $this->request->getPost('retype_password');
        
        $validation = $this->validate([
            'current_password'=>'required',
            'retype_password'=>'required',
        ]);

        if(!$validation)
        {
            session()->setFlashdata('fail','Invalid! Please fill in the form');
            return redirect()->to('/profile')->withInput();
        }
        else
        {
            if($password!=$retypepassword)
            {
                session()->setFlashdata('fail','Invalid! Password mismatched');
                return redirect()->to('/profile')->withInput();
            }
            else
            {
                $values = ['password'=>Hash::make($password)];
                $accountModel->update($userID,$values);
                session()->setFlashdata('success','Great! Your password has successfully changed');
                return redirect()->to('/profile')->withInput();
            }
        }
    }

    public function editAccount($id=null)
    {
        //get the user accounts
        $accountModel = new \App\Models\accountModel();
        $account = $accountModel->WHERE('accountID',$id)->first();
        $data = ['account'=>$account,];
        return view('edit-account',$data);
    }

    public function saveAccount()
    {
        $accountModel = new \App\Models\accountModel();
        $fullname = $this->request->getPost('fullname');
        $username = $this->request->getPost('username');
        $role = $this->request->getPost('systemRole');
        $assign = $this->request->getPost('assignment');
        $status = 1;
        $dateCreated = date('Y-m-d');
        $defaultPassword = Hash::make("Fastcat_01");
        $validation = $this->validate([
            'fullname'=>'required|is_unique[tblaccount.Fullname]',
            'username'=>'required|is_unique[tblaccount.username]',
            'assignment'=>'required',
            'systemRole'=>'required'
        ]);
        if(!$validation)
        {
            echo "Invalid! Please fill in the form/username already exist";
        }
        else{
            $values = 
            ['username'=>$username, 'password'=>$defaultPassword,'Fullname'=>$fullname,'Status'=>$status,'systemRole'=>$role,'warehouseID'=>$assign,'DateCreated'=>$dateCreated];
            $accountModel->save($values);
            echo "success";
        }
        
    }

    public function updateAccount()
    {
        $accountModel = new \App\Models\accountModel();
        $id = $this->request->getPost('accountID');
        $fullname = $this->request->getPost('fullname');
        $username = $this->request->getPost('username');
        $role = $this->request->getPost('systemRole');
        $status = $this->request->getPost('status');
        $values = 
            ['username'=>$username,'Fullname'=>$fullname,'Status'=>$status,'systemRole'=>$role];
        $accountModel->update($id,$values);
        session()->setFlashdata('success','Great! Successfully updated');
        return redirect()->to('/configuration')->withInput();
    }

    public function saveSupplier()
    {
        $supplierModel = new \App\Models\supplierModel();
        $industry = $this->request->getPost('industry');
        $supplier_name = $this->request->getPost('supplier_name');
        $supplier_address = $this->request->getPost('supplier_address');
        $person = $this->request->getPost('contact_person');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');
        $validation = $this->validate([
            'supplier_name'=>'required|is_unique[tblsupplier.supplierName]',
            'supplier_address'=>'required',
            'contact_person'=>'required',
            'email'=>'required|valid_email|is_unique[tblsupplier.EmailAddress]',
            'phone'=>'required'
        ]);
        if(!$validation)
        {
            session()->setFlashdata('fail',"Invalid! Please check the supplier's information before the submission");
            return redirect()->to('/add-supplier')->withInput();
        }
        else{
            $values = [
                'supplierName'=>$supplier_name,'Address'=>$supplier_address,
                'contactPerson'=>$person,'EmailAddress'=>$email,'contactNumber'=>$phone,
                'industryID'=>$industry,
            ];
            $supplierModel->save($values);
            session()->setFlashdata('success','Great! Successfully added');
            return redirect()->to('/add-supplier')->withInput();
        }
    }

    public function fetchIndustry()
    {
        $builder = $this->db->table('tblindustry');
        $builder->select('*');
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            ?>
            <option value="<?php echo $row->industryID ?>"><?php echo $row->Name ?></option>
            <?php
        }
    }

    public function saveIndustry()
    {
        $industryModel = new \App\Models\industryModel();
        $name = $this->request->getPost('industryName');
        $validation = $this->validate(['industryName'=>'is_unique[tblindustry.Name]|required']);
        if(!$validation)
        {
            echo "Invalid! Please fill in the form";
        }
        else{
            $values = ['Name'=>$name];
            $industryModel->save($values);
            echo "success";
        }
    }

    public function saveCategory()
    {
        $categoryModel = new \App\Models\categoryModel();
        $category = $this->request->getPost('categoryName');
        $desc = $this->request->getPost('description');
        $validation = $this->validate([
            'categoryName'=>'required|is_unique[tblcategory.categoryName]'
        ]);
        if(!$validation)
        {
            echo "Invalid! Please fill in the form";
        }
        else{
            $values = [
                'categoryName'=>$category,
                'Description'=>$desc,
            ];
            $categoryModel->save($values);
            echo "success";
        }
    }

    public function saveWarehouse()
    {
        $warehouseModel = new \App\Models\warehouseModel();
        $name = $this->request->getPost('warehouseName');
        $location = $this->request->getPost('address');
        $status = 1;
        $validation = $this->validate([
            'warehouseName'=>'required|is_unique[tblwarehouse.warehouseName]',
            'address'=>'required'
        ]);
        if(!$validation)
        {
            echo "Invalid! Please fill in the form";
        }
        else{
            $values = [
                'warehouseName'=>$name,
                'Address'=>$location,
                'Status'=>$status,
            ];
            $warehouseModel->save($values);
            echo "success";
        }
    }

    public function listIndustry()
    {
        $builder = $this->db->table('tblindustry');
        $builder->select('Name,industryID');
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            ?>
            <li class="d-flex align-items-center justify-content-between">
                <div class="name-avatar d-flex align-items-center pr-2">
                    <div class="avatar mr-2 flex-shrink-0">
                        <?php echo $row->Name ?>
                    </div>
                </div>
                <div class="cta flex-shrink-0">
                    <button type="button" class="btn btn-sm btn-outline-danger remove" value="<?php echo $row->industryID ?>"><i class="icon-copy dw dw-delete-3"></i></button>
                </div>
            </li>
            <?php
        }
    }
    
    public function listCategory()
    {
        $builder = $this->db->table('tblcategory');
        $builder->select('categoryName,categoryID,Description');
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            ?>
            <li class="d-flex align-items-center justify-content-between">
                <div class="name-avatar d-flex align-items-center pr-2">
                    <div class="txt">
                        <div class="font-14 weight-600"><?php echo $row->categoryName ?></div>
                        <div class="font-12 weight-500" data-color="#b2b1b6">
                            <?php echo $row->Description ?>
                        </div>
                    </div>
                </div>
                <div class="cta flex-shrink-0">
                    <button type="button" class="btn btn-sm btn-outline-danger remove" value="<?php echo $row->categoryID ?>"><i class="icon-copy dw dw-delete-3"></i></button>
                </div>
            </li>
            <?php
        }
    }

    public function listWarehouse()
    {
        $builder = $this->db->table('tblwarehouse');
        $builder->select('warehouseID,warehouseName,Address');
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            ?>
            <li class="d-flex align-items-center justify-content-between">
                <div class="name-avatar d-flex align-items-center pr-2">
                    <div class="txt">
                        <div class="font-14 weight-600"><?php echo $row->warehouseName ?></div>
                        <div class="font-12 weight-500" data-color="#b2b1b6">
                            <?php echo $row->Address ?>
                        </div>
                    </div>
                </div>
                <div class="cta flex-shrink-0">
                    <button type="button" class="btn btn-sm btn-outline-danger remove" value="<?php echo $row->warehouseID ?>"><i class="icon-copy dw dw-delete-3"></i></button>
                </div>
            </li>
            <?php
        }
    }

    public function assignment()
    {
        $builder = $this->db->table('tblwarehouse');
        $builder->select('warehouseID,warehouseName,Address');
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            ?>
            <option value="<?php echo $row->warehouseID ?>"><?php echo $row->warehouseName ?></option>
            <?php
        }
    }
}
