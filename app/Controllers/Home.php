<?php

namespace App\Controllers;

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
        return view('dashboard');
    }

    public function stocks()
    {
        //get all the stocks
        $builder = $this->db->table('tblinventory a');
        $builder->select('a.*,b.categoryName');
        $builder->join('tblcategory b','b.categoryID=a.categoryID','LEFT');
        $builder->join('tblsupplier c','c.supplierID=a.supplierID','LEFT');
        $builder->orderby('a.Date');
        $items = $builder->get()->getResult();
        $data = ['items'=>$items];
        return view('all-stocks',$data);
    }

    public function addStocks()
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
        $data = ['warehouse'=>$warehouse,'supplier'=>$supplier,'category'=>$category];
        return view('add-stocks',$data);
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
        return redirect()->to('/list-supplier')->withInput();
    }
    
    public function addSupplier()
    {
        return view('add-supplier');
    }

    public function systemConfiguration()
    {
        return view('system-config');
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
}
