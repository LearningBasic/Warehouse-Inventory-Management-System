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
        $values = ['Name'=>$name];
        $industryModel->save($values);
        echo "success";
    }
}
