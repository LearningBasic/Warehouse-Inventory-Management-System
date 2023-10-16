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
        $builder->select('a.*,b.categoryName,c.warehouseID');
        $builder->join('tblcategory b','b.categoryID=a.categoryID','LEFT');
        $builder->join('tblwarehouse c','c.warehouseID=a.warehouseID','LEFT');
        $builder->join('tblsupplier d','d.supplierID=a.supplierID','LEFT');
        $builder->orderby('Date');
        $items = $builder->get()->getResult();
        $data = ['items'=>$items];
        return view('all-stocks',$data);
    }

    public function systemConfiguration()
    {
        return view('system-config');
    }

    public function saveIndustry()
    {
        $industryModel = new \App\Models\industryModel();
        $name = $this->request->getPost('industryName');
        $validation = $this->validate(['industryName'=>'is_unique[tblindustry.Name]']);
        if(!$validation)
        {
            echo $name." already exists";
        }
        else{
            $values = ['Name'=>$name];
            $industryModel->save($values);
            echo "success";
        }
    }

    public function listIndustry()
    {
        $builder = $this->db->table('tblindustry');
        $builder->select('Name');
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            ?>
            <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                <h5 class="mb-1 h5"><?php echo $row->Name ?><button type="button" style="float:right;" class="btn"><i class="icon-copy dw dw-delete-3"></i></button></h5>
            </a>
            <?php
        }
    }
}
