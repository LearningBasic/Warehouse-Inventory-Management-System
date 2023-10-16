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
                    <div class="avatar mr-2 flex-shrink-0">
                        <?php echo $row->categoryName ?>
                    </div>
                    <div class="txt">
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
}
