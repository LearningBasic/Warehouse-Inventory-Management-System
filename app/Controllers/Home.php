<?php

namespace App\Controllers;
use App\Libraries\Hash;
use PHPUnit\TextUI\XmlConfiguration\Group;

class Home extends BaseController
{
    private $db;
    public function __construct()
    {
        $this->db = db_connect();
    }

    public function Scanner()
    {
        return view('scan-qrcode');
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
        $builder->select('a.warehouseName,IFNULL(COUNT(b.productID),0)total');
        $builder->join('tblinventory b','b.warehouseID=a.warehouseID','LEFT');
        $builder->groupBy('a.warehouseID');
        $assign = $builder->get()->getResult();
        //categorized
        $builder = $this->db->table('tblcategory a');
        $builder->select('a.categoryName,COUNT(DISTINCT b.productID)total');
        $builder->join('tblinventory b','b.categoryID=a.categoryID','LEFT');
        $builder->groupBy('a.categoryID');
        $category = $builder->get()->getResult();
        //total stocks
        $stocks=0;
        $builder = $this->db->table('tblinventory');
        $builder->select('FORMAT(IFNULL(SUM(Qty),0),0)total');
        $builder->WHERE('Qty<>',0);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $stocks= $row->total;
        }
        //void
        $void=0;
        $builder = $this->db->table('tblinventory');
        $builder->select('FORMAT(COUNT(*),0)total');
        $builder->WHERE('Qty',0);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $void= $row->total;
        }
        //reserved
        $reserved=0;
        $builder = $this->db->table('tblreserved');
        $builder->select('FORMAT(IFNULL(SUM(Qty),0),0)total');
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $reserved= $row->total;
        }
        //total item
        $onhand=0;
        $builder = $this->db->table('tblinventory');
        $builder->select('SUM(Qty)total');
        $builder->WHERE('Qty<>',0);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $onhand =  $row->total;
        }
        //reserved
        $reserve=0;
        $builder = $this->db->table('tblreserved');
        $builder->select('SUM(Qty)total');
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $reserve =  $row->total;
        }
        $total = $onhand + $reserve;
        $totalItem = number_format($total,0);
        //approved PO
        $purchase_order=0;
        $builder = $this->db->table('tblpurchase_logs');
        $builder->select('COUNT(*)total');
        $builder->WHERE('Status',1);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $purchase_order = number_format($row->total,0);
        }
        $data = ['query'=>$query,'assignment'=>$assign,'category'=>$category,'purchase'=>$purchase_order,
        'stocks'=>$stocks,'void'=>$void,'reserve'=>$reserved,'total'=>$totalItem];
        return view('dashboard',$data);
    }

    public function stocks()
    {
        //get all the stocks
        $builder = $this->db->table('tblinventory a');
        $builder->select('a.*,SUM(a.Qty)Qty,b.categoryName,c.supplierName,d.warehouseName,e.Image');
        $builder->join('tblcategory b','b.categoryID=a.categoryID','LEFT');
        $builder->join('tblsupplier c','c.supplierID=a.supplierID','LEFT');
        $builder->join('tblwarehouse d','d.warehouseID=a.warehouseID','LEFT');
        $builder->join('(Select Image,inventID from tblimage GROUP BY inventID) e','e.inventID=a.inventID','LEFT');
        $builder->groupby('a.inventID');
        $items = $builder->get()->getResult();
        $data = ['items'=>$items];
        return view('all-stocks',$data);
    }

    public function generateQR($id=null)
    {
        $inventoryModel = new \App\Models\inventoryModel();
        $items = $inventoryModel->WHERE('inventID',$id)->first();
        $data = ['items'=>$items,];
        return view('generate-qrcode',$data);
    }

    public function edit($id=null)
    {
        //supplier
        $builder = $this->db->table('tblsupplier');
        $builder->select('*');
        $supplier = $builder->get()->getResult();
        $builder = $this->db->table('tblinventory a');
        $builder->select('a.*,b.categoryName,c.warehouseName');
        $builder->join('tblcategory b','b.categoryID=a.categoryID','LEFT');
        $builder->join('tblwarehouse c','c.warehouseID=a.warehouseID','LEFT');
        $builder->WHERE('a.inventID',$id);
        $items = $builder->get()->getResult();
        $data = ['items'=>$items,'supplier'=>$supplier,];
        return view('edit',$data);
    }

    public function update()
    {
        $inventoryModel = new \App\Models\inventoryModel();
        $id = $this->request->getPost('itemID');
        $supplier = $this->request->getPost('supplier');
        $location = $this->request->getPost('location');
        $code = $this->request->getPost('code');
        $item_number = $this->request->getPost('itemNumber');
        $productName = $this->request->getPost('productName');
        $desc = $this->request->getPost('description');
        $values = [
            'Location'=>$location,'productID'=>$item_number,'productName'=>$productName,
            'Code'=>$code,'Description'=>$desc,
            'supplierID'=>$supplier,];
        $inventoryModel->update($id,$values);
        //create logs
        $systemLogsModel = new \App\Models\systemLogsModel();
        $values = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Update product '.$productName];
        $systemLogsModel->save($values);

        session()->setFlashdata('success','Great! Successfully updated');
        return redirect()->to('/stocks')->withInput();
    }

    public function manageStocks()
    {
        $user = session()->get('loggedUser');
        //for release
        $builder = $this->db->table('tblissuance a');
        $builder->select('a.*,b.Department');
        $builder->join('tblprf b','b.OrderNo=a.OrderNo','LEFT');
        $builder->WHERE('a.accountID',$user);
        $release = $builder->get()->getResult();
        //transfer item
        $builder = $this->db->table('tbltransferitem');
        $builder->select('*');
        $transfer = $builder->get()->getResult();
        //products
        $builder = $this->db->table('tblinventory');
        $builder->select('*');
        $product = $builder->get()->getResult();

        $data = ['transfer'=>$transfer,'product'=>$product,'release'=>$release];
        return view('manage-stocks',$data);
    }

    public function storage()
    {
        $builder = $this->db->table("tblreserved");
        $builder->select('*');
        $reserve = $builder->get()->getResult();
        //product
        $builder = $this->db->table('tblinventory');
        $builder->select('*');
        $product = $builder->get()->getResult();
        //get the receive PO
        $builder = $this->db->table('tblreceive a');
        $builder->select('a.*,b.warehouseName');
        $builder->join('tblwarehouse b','b.warehouseID=a.warehouseID','LEFT');
        $receive = $builder->get()->getResult();

        $data = ['reserve'=>$reserve,'product'=>$product,'receive'=>$receive];
        return view('storage',$data);
    }

    public function addStock()
    {
        $inventoryModel = new \App\Models\inventoryModel();
        $systemLogsModel = new \App\Models\systemLogsModel();
        $qrModel = new \App\Models\qrcodeModel();
        $reservedModel = new \App\Models\reservedModel();
        //data
        $id = $this->request->getPost('receiveID');
        $product = $this->request->getPost('product');
        $num_stocks = $this->request->getPost('qty');
        $user = session()->get('loggedUser');

        $validation = $this->validate([
           'product'=>'required',
           'qty'=>'required',
        ]);
        if(!$validation)
        {
            echo "Invalid! Please fill in the form";
        }
        else
        {
            //update the stocks
            $inventory = $inventoryModel->WHERE('inventID',$product)->first();
            $newQty = $inventory['Qty']+$num_stocks;
            $value = ['Qty'=>$newQty];
            $inventoryModel->update($product,$value);
            //save logs
            $records = [
                'accountID'=>$user,'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Added stocks to '.$inventory['productName']
            ];
            $systemLogsModel->save($records);
            for($i=0;$i<$num_stocks;$i++)
            {
                //count the items per inventory and add qrcode
                $builder = $this->db->table('tblqrcode');
                $builder->select('COUNT(*) as total');
                $builder->WHERE('inventID',$product);
                $data = $builder->get();
                if($row = $data->getRow())
                {
                    $values = ['inventID'=>$product,'TextValue'=>$inventory['productID'].$row->total];
					$qrModel->save($values);
                }
            }
            //deduct the qty
            $reserved = $reservedModel->WHERE('reservedID',$id)->first();
            $newValues = ['Available'=>$reserved['Available']-$num_stocks];
            $reservedModel->update($id,$newValues);
            echo "success";
        }
    }

    public function newProduct($id)
    {
        $receiveModel = new \App\Models\receiveModel();
        $reservedModel = new \App\Models\reservedModel();
        $reserve = $reservedModel->WHERE('reservedID',$id)->first();
        //get the warehouse ID
        $receive = $receiveModel->WHERE('purchaseNumber',$reserve['purchaseNumber'])->first();
        //warehouse
        $builder = $this->db->table('tblwarehouse');
        $builder->select('*');
        $builder->WHERE('warehouseID',$receive['warehouseID']);
        $warehouse = $builder->get()->getResult();
        //supplier
        $builder = $this->db->table('tblsupplier');
        $builder->select('*');
        $builder->WHERE('supplierID',$reserve['supplierID']);
        $supplier = $builder->get()->getResult();
        //category
        $builder = $this->db->table('tblcategory');
        $builder->select('*');
        $category = $builder->get()->getResult();

        $data = ['warehouse'=>$warehouse,'supplier'=>$supplier,'category'=>$category,'reserve'=>$reserve];
        return view('new-product',$data);
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
        $qrModel = new \App\Models\qrcodeModel();
        //data
        $date = date('Y-m-d');
        $warehouse = $this->request->getPost('warehouse');
        $supplier = $this->request->getPost('supplier');
        $category = $this->request->getPost('category');
        $location = $this->request->getPost('location');
        $item_number="";
        $code = $this->request->getPost('productCode');
        $productName = $this->request->getPost('productName');
        $desc = $this->request->getPost('description');
        $itemUnit = $this->request->getPost('itemUnit');
        $unitPrice = $this->request->getPost('unitPrice');
        $qty = $this->request->getPost('qty');
        $reOrder = $this->request->getPost('reOrder');
        $expirationDate = $this->request->getPost('expirationDate');
        $imageFile = $this->request->getFileMultiple('images');
        $validation = $this->validate([
            'warehouse'=>'required',
            'category'=>'required',
            'productName'=>'required|is_unique[tblinventory.productName]',
            'itemUnit'=>'required',
            'unitPrice'=>'required',
            'qty'=>'required',
            'images'=>'uploaded[images]'
        ]);
        if(!$validation)
        {
            session()->setFlashdata('fail',"Invalid! Please check the item/equipment information before submission");
            return redirect()->to('/add')->withInput();
        }
        else
        {
            if($this->request->getFileMultiple('images')) 
            {
                //get the aliases
                $alias = "";
                $builder = $this->db->table('tblcategory');
                $builder->select('Alias');
                $builder->WHERE('categoryID',$category);
                $datas = $builder->get();
                if($row = $datas->getRow())
                {
                    $alias = $row->Alias;
                }
                //generate the code
                $builder = $this->db->table('tblinventory');
                $builder->select('COUNT(inventID)+1 as total');
                $builder->WHERE('categoryID',$category);
                $list = $builder->get();
                if($li = $list->getRow())
                {
                    $item_number = $alias.str_pad($li->total, 4, '0', STR_PAD_LEFT);
                }
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

                //generate QR
                for($i=0;$i<$qty;$i++)
                {
                    $values = ['inventID'=>$inventID ,'TextValue'=>$item_number.$i];
					$qrModel->save($values);
                }

                foreach($imageFile as $file)
                { 
                    $originalName = date("YmdHis").$file->getClientName();
                    $file->move('Products/',$originalName);
                    //save the images
                    $values = [
                        'inventID'=>$inventID,
                        'Image'=>$originalName,
                        'DateCreated'=>date('Y-m-d'),
                    ];
                    $productImage->save($values);
                }

                //create logs
                $systemLogsModel = new \App\Models\systemLogsModel();
                $values = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Added new product '.$productName];
                $systemLogsModel->save($values);
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

    public function uploadImage()
    {
        $productImage = new \App\Models\productImageModel();
        //data
        $inventID = $this->request->getPost('productID');
        $imageFile = $this->request->getFileMultiple('images');
        $validation = $this->validate([
            'images' =>'uploaded[images]',
        ]);

        if(!$validation)
        {
            echo "Invalid! Please select image";
        }
        else
        {
            //remove the previous images
            $builder = $this->db->table('tblimage');
            $builder->WHERE('inventID',$inventID);
            $builder->delete();

            foreach($imageFile as $file)
            {
                $originalName = date("YmdHis").$file->getClientName();
                $file->move('Products/',$originalName);
                //save the images
                $values = [
                    'inventID'=>$inventID,
                    'Image'=>$originalName,
                    'DateCreated'=>date('Y-m-d'),
                ];
                $productImage->save($values);
            }
            echo "success";
        }
    }

    public function saveProduct()
    {
        $inventoryModel = new \App\Models\inventoryModel();
        $productImage = new \App\Models\productImageModel();
        $qrModel = new \App\Models\qrcodeModel();
        $reservedModel = new \App\Models\reservedModel();
        //data
        $date = date('Y-m-d');
        $id = $this->request->getPost('reserveID');
        $warehouse = $this->request->getPost('warehouse');
        $supplier = $this->request->getPost('supplier');
        $category = $this->request->getPost('category');
        $location = $this->request->getPost('location');
        $item_number="";
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
            'productName'=>'required',
            'itemUnit'=>'required',
            'unitPrice'=>'required',
            'qty'=>'required'
        ]);
        if(!$validation)
        {
            session()->setFlashdata('fail',"Invalid! Please check the item/equipment information before submission");
            return redirect()->to('/new-product/'.$id)->withInput();
        }
        else
        {
            if($this->request->getFileMultiple('images')) 
            {
                //get the aliases
                $alias = "";
                $builder = $this->db->table('tblcategory');
                $builder->select('Alias');
                $builder->WHERE('categoryID',$category);
                $datas = $builder->get();
                if($row = $datas->getRow())
                {
                    $alias = $row->Alias;
                }
                //generate the code
                $builder = $this->db->table('tblinventory');
                $builder->select('COUNT(inventID)+1 as total');
                $builder->WHERE('categoryID',$category);
                $list = $builder->get();
                if($li = $list->getRow())
                {
                    $item_number = $alias.str_pad($li->total, 4, '0', STR_PAD_LEFT);
                }
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
                        'Image'=>$file->getClientName(),
                        'DateCreated'=>date('Y-m-d'),
                    ];
                    $productImage->save($values);
                }
                //generate QR
                for($i=0;$i<$qty;$i++)
                {
                    $values = ['inventID'=>$inventID ,'TextValue'=>$item_number.$i];
					$qrModel->save($values);
                }
                //deduct the qty
                $reserved = $reservedModel->WHERE('reservedID',$id)->first();
                $newValues = ['Available'=>$reserved['Available']-$qty];
                $reservedModel->update($id,$newValues);

                //create logs
                $systemLogsModel = new \App\Models\systemLogsModel();
                $values = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Added new product '.$productName];
                $systemLogsModel->save($values);
                session()->setFlashdata('success',"Great! Successfully added");
                return redirect()->to('/storage')->withInput();
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

        //create logs
        $systemLogsModel = new \App\Models\systemLogsModel();
        $values = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Update information of '.$supplier_name];
        $systemLogsModel->save($values);

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

    public function receiveOrder()
    {
        $builder = $this->db->table('tblsupplier');
        $builder->select('*');
        $vendor = $builder->get()->getResult();
        //warehouse
        $builder = $this->db->table('tblwarehouse');
        $builder->select('*');
        $warehouse = $builder->get()->getResult();
        //purchase order
        $builder = $this->db->table('tblprf');
        $builder->select('OrderNo');
        $builder->WHERE('Remarks','OPEN')->WHERE('Status',3);
        $order = $builder->get()->getResult();

        $data = ['vendor'=>$vendor,'warehouse'=>$warehouse,'order'=>$order];
        return view('inventory-receive',$data);
    }

    public function purchaseRequest()
    {
        return view('orders');
    }

    public function editPurchase($id)
    {
        $purchaseModel = new \App\Models\purchaseModel();
        $purchase = $purchaseModel->WHERE('OrderNo',$id)->first();

        //items
        $OrderItemModel = new \App\Models\OrderItemModel();
        $item = $OrderItemModel->WHERE('OrderNo',$id)->findAll();
        $data = ['purchase'=>$purchase,'item'=>$item];
        return view('edit-purchase',$data);
    }

    public function addReport()
    {
        $user = session()->get('loggedUser');
        $builder = $this->db->table('tbldamagereport a');
        $builder->select('a.*,b.productName');
        $builder->join('tblinventory b','b.inventID=a.inventID','LEFT');
        $builder->WHERE('a.accountID',$user);
        $damage = $builder->get()->getResult();
        //repair
        $builder = $this->db->table('tblrepairreport a');
        $builder->select('a.*,c.productName');
        $builder->join('tbldamagereport b','b.reportID=a.reportID','LEFT');
        $builder->join('tblinventory c','c.inventID=b.inventID','LEFT');
        $builder->WHERE('a.accountID',$user);
        $builder->groupby('a.rrID');
        $repair = $builder->get()->getResult();
        //transfer request
        $builder = $this->db->table('tbltransfer_request');
        $builder->select('*');
        $builder->WHERE('accountID',$user);
        $request = $builder->get()->getResult();
        //return order
        $builder = $this->db->table('tblreturn a');
        $builder->select('a.*,b.supplierName');
        $builder->join('tblsupplier b','b.supplierID=a.supplierID','LEFT');
        $builder->WHERE('a.accountID',$user);
        $returnOrder = $builder->get()->getResult();

        $data = ['damage'=>$damage,'repair'=>$repair,'request'=>$request,'returnOrder'=>$returnOrder];
        return view('add-report',$data);
    }

    public function damageReport()
    {
        $assign = session()->get('assignment');
        $builder = $this->db->table('tblinventory');
        $builder->select('*');
        $builder->WHERE('warehouseID',$assign);
        $items = $builder->get()->getResult();
        $data = ['items'=>$items];
        return view('damage-report',$data);
    }

    public function repairReport()
    {
        $assign = session()->get('assignment');
        $builder = $this->db->table('tbldamagereport a');
        $builder->select('a.reportID,b.productName');
        $builder->join('tblinventory b','b.inventID=a.inventID','LEFT');
        $builder->WHERE('b.warehouseID',$assign)->WHERE('a.Status',1)->WHERE('a.Remarks','For Repair');
        $forRepair = $builder->get()->getResult();
        $data = ['repair'=>$forRepair,];
        return view('repair-report',$data);
    }

    public function transferItem()
    {
        //warehouse
        $builder = $this->db->table('tblwarehouse');
        $builder->select('*');
        $warehouse = $builder->get()->getResult();
        $data = ['location'=>$warehouse];
        return view('transfer-request',$data);
    }

    public function returnOrder()
    {
        $builder = $this->db->table("tblreserved");
        $builder->select('*');
        $reserve = $builder->get()->getResult();
        //vendor
        //supplier
        $builder = $this->db->table('tblsupplier');
        $builder->select('*');
        $supplier = $builder->get()->getResult();
        
        $data = ['reserve'=>$reserve,'supplier'=>$supplier];
        return view('return-order',$data);
    }
    
    public function userRequest()
    {
        if(session()->get('role')=="Administrator")
        {
            //damage
            $builder = $this->db->table('tbldamagereport a');
            $builder->select('a.*,b.productName');
            $builder->join('tblinventory b','b.inventID=a.inventID','LEFT');
            $builder->groupby('a.reportID');
            $damage = $builder->get()->getResult();
            //for repair
            $builder = $this->db->table('tblrepairreport a');
            $builder->select('a.*,c.productName');
            $builder->join('tbldamagereport b','b.reportID=a.reportID','LEFT');
            $builder->join('tblinventory c','c.inventID=b.inventID','LEFT');
            $builder->groupby('a.rrID');
            $repair = $builder->get()->getResult();
            //transfer request
            $builder = $this->db->table('tbltransfer_request a');
            $builder->select('a.*,b.Fullname,c.warehouseName,d.warehouseName as newLocation');
            $builder->join('tblaccount b','b.accountID=a.accountID','LEFT');
            $builder->join('tblwarehouse c','c.warehouseID=b.warehouseID','LEFT');
            $builder->join('tblwarehouse d','d.warehouseID=a.warehouseID','LEFT');
            $builder->groupBy('a.requestID');
            $transfer = $builder->get()->getResult();
            //return order
            $builder = $this->db->table('tblreturn a');
            $builder->select('a.*,b.supplierName');
            $builder->join('tblsupplier b','b.supplierID=a.supplierID','LEFT');
            $returnOrder = $builder->get()->getResult();

            $data = ['damage'=>$damage,'repair'=>$repair,'transfer'=>$transfer,'returnOrder'=>$returnOrder];
            return view('request',$data);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function systemConfiguration()
    {
        //get the user accounts
        $builder = $this->db->table('tblaccount');
        $builder->select('*');
        $account = $builder->get()->getResult();
        //get the logs
        $builder = $this->db->table('tblsystem_logs a');
        $builder->select('a.*,b.Fullname');
        $builder->join('tblaccount b','b.accountID=a.accountID','LEFT');
        $builder->orderby('a.systemID','DESC');
        $logs = $builder->get()->getResult();
        //administrator
        $builder = $this->db->table('tblaccount');
        $builder->select('*');
        $builder->WHERE('systemRole','Administrator');
        $admin = $builder->get()->getResult();
        //staff
        $builder = $this->db->table('tblaccount');
        $builder->select('*');
        $builder->WHERE('systemRole','Staff')->WHERE('Status',1);
        $staff = $builder->get()->getResult();
        //item group
        $builder = $this->db->table('tbltask a');
        $builder->select('a.ItemGroup,b.Fullname,a.taskID');
        $builder->join('tblaccount b','b.accountID=a.accountID','LEFT');
        $task = $builder->get()->getResult();

        $data = ['account'=>$account,'logs'=>$logs,'admin'=>$admin,'staff'=>$staff,'task'=>$task];
        return view('system-config',$data);
    }

    public function saveSettings()
    {
        $systemModel = new \App\Models\systemModel();
        //data
        $date = date('Y-m-d');
        $user = $this->request->getPost('user');
        //check if table has data
        $builder = $this->db->table('tblsystem');
        $builder->select('COUNT(systemID)total');
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            if($row->total==0)
            {
                //save
                $values = ['accountID'=>$user,'Date'=>$date];
                $systemModel->save($values);
            }
            else
            {
                //update
                $builder = $this->db->table('tblsystem');
                $builder->select('systemID');
                $list = $builder->get();
                if($rows = $list->getRow())
                {
                    $values = ['accountID'=>$user,'Date'=>$date];
                    $systemModel->update($rows->systemID,$values);
                }
            }
            session()->setFlashdata('success','Great! Successfully saved');
            return redirect()->to('/configuration')->withInput();
        }
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

    public function Assign()
    {
        $canvasFormModel = new \App\Models\canvasFormModel();
        $user = session()->get('loggedUser');
        $builder = $this->db->table('tblassignment a');
        $builder->select('a.Status,b.prfID,b.OrderNo,b.Remarks,a.Date,b.DateNeeded,b.Reason,b.Department,c.Fullname,a.assignID,e.Fullname as Staff');
        $builder->join('tblprf b','b.prfID=a.prfID','LEFT');
        $builder->join('tblaccount c','c.accountID=b.accountID','LEFT');
        $builder->join('tblaccount e','e.accountID=a.accountID','LEFT');
        $builder->groupby('a.assignID');
        $list = $builder->get()->getResult();
        //pending
        $builder = $this->db->table('tblassignment a');
        $builder->select('a.Status,b.prfID,b.OrderNo,b.Remarks,a.Date,b.DateNeeded,b.Reason,b.Department,c.Fullname,a.assignID');
        $builder->join('tblprf b','b.prfID=a.prfID','LEFT');
        $builder->join('tblaccount c','c.accountID=b.accountID','LEFT');
        $builder->WHERE('a.accountID',$user)->WHERE('a.Status',0);
        $builder->groupby('a.assignID');
        $pending = $builder->get()->getResult();
        //quotation
        $canvass = $canvasFormModel->WHERE('createdBy',$user)->findAll();
        $data = ['list'=>$list,'pending'=>$pending,'canvass'=>$canvass];
        return view('assign',$data);
    }

    public function editOrder($id)
    {
        $builder = $this->db->table('tbl_order_item');
        $builder->select('*');
        $builder->WHERE('OrderNo',$id);
        $item = $builder->get()->getResult();
        //planner
        $role = ['Planner'];
        $builder = $this->db->table('tblaccount');
        $builder->select('*');
        $builder->WHEREIN('systemRole',$role)->WHERE('Status',1);
        $account = $builder->get()->getResult();

        $data = ['item'=>$item,'PRF'=>$id,'account'=>$account];
        return view('edit-order',$data);
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
        $email = $this->request->getPost('email');
        $username = $this->request->getPost('username');
        $role = $this->request->getPost('systemRole');
        $assign = $this->request->getPost('assignment');
        $dept = $this->request->getPost('department');
        $file = $this->request->getFile('file');
        $originalName = $file->getClientName();
        $status = 1;
        $dateCreated = date('Y-m-d');
        $defaultPassword = Hash::make("Fastcat_01");
        $validation = $this->validate([
            'fullname'=>'required|is_unique[tblaccount.Fullname]',
            'username'=>'required|is_unique[tblaccount.username]',
            'email'=>'required|valid_email',
            'assignment'=>'required',
            'systemRole'=>'required'
        ]);
        if(!$validation)
        {
            echo "Invalid! Please fill in the form/username already exist";
        }
        else{
            $values = 
            ['username'=>$username, 'password'=>$defaultPassword,'Fullname'=>$fullname,'Email'=>$email,
            'Status'=>$status,'systemRole'=>$role,'warehouseID'=>$assign,'Department'=>$dept,
            'DateCreated'=>$dateCreated,'Signatures'=>$originalName];
            $accountModel->save($values);
            $file->move('Signatures/',$originalName);
            //create logs
            $systemLogsModel = new \App\Models\systemLogsModel();
            $value = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Registered new account of '.$fullname];
            $systemLogsModel->save($value);
            echo "success";
        }
        
    }

    public function updateAccount()
    {
        $accountModel = new \App\Models\accountModel();
        $id = $this->request->getPost('accountID');
        $fullname = $this->request->getPost('fullname');
        $email = $this->request->getPost('email');
        $username = $this->request->getPost('username');
        $role = $this->request->getPost('systemRole');
        $status = $this->request->getPost('status');
        $file = $this->request->getFile('file');
        $originalName = $file->getClientName();
        if(empty($originalName))
        {
            $values = 
                ['username'=>$username,'Fullname'=>$fullname,'Email'=>$email,'Status'=>$status,'systemRole'=>$role];
            $accountModel->update($id,$values);
        }
        else
        {
            $values = 
                ['username'=>$username,'Fullname'=>$fullname,'Email'=>$email,'Status'=>$status,'systemRole'=>$role,'Signatures'=>$originalName];
            $accountModel->update($id,$values);
            $file->move('Signatures/',$originalName);
        }
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

            //create logs
            $systemLogsModel = new \App\Models\systemLogsModel();
            $value = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Added new vendor '.$supplier_name];
            $systemLogsModel->save($value);
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
        $alias = $this->request->getPost('alias');
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
                'Alias'=>$alias,
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
                    <button type="button" class="btn btn-sm btn-outline-danger removeIndustry" value="<?php echo $row->industryID ?>">
                        <i class="icon-copy dw dw-delete-3"></i>
                    </button>
                </div>
            </li>
            <?php
        }
    }
    
    public function listCategory()
    {
        $builder = $this->db->table('tblcategory');
        $builder->select('categoryName,categoryID,Description,Alias');
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            ?>
            <li class="d-flex align-items-center justify-content-between">
                <div class="name-avatar d-flex align-items-center pr-2">
                    <div class="txt">
                        <div class="font-14 weight-600"><?php echo $row->categoryName ?></div>
                        <div class="font-12 weight-500" data-color="#b2b1b6">
                            <?php echo $row->Alias ?>
                        </div>
                    </div>
                </div>
                <div class="cta flex-shrink-0">
                    <button type="button" class="btn btn-sm btn-outline-danger removeCategory" value="<?php echo $row->categoryID ?>">
                        <i class="icon-copy dw dw-delete-3"></i>
                    </button>
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
                    <button type="button" class="btn btn-sm btn-outline-danger removeLocation" value="<?php echo $row->warehouseID ?>">
                        <i class="icon-copy dw dw-delete-3"></i>
                    </button>
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

    public function stocksReport()
    {
        if(session()->get('role')=="Administrator"||session()->get('role')=="Editor")
        {
            $builder = $this->db->table('tblwarehouse');
            $builder->select('*');
            $warehouse = $builder->get()->getResult();
            //category
            $builder = $this->db->table('tblcategory');
            $builder->select('*');
            $category = $builder->get()->getResult();
            //account
            $builder = $this->db->table('tblaccount');
            $builder->select('*');
            $account = $builder->get()->getResult();
            //nearly expired
            $builder = $this->db->table('nearly_expired a');
            $builder->select('a.*,SUM(a.Qty)Qty,b.categoryName,c.supplierName,d.warehouseName');
            $builder->join('tblcategory b','b.categoryID=a.categoryID','LEFT');
            $builder->join('tblsupplier c','c.supplierID=a.supplierID','LEFT');
            $builder->join('tblwarehouse d','d.warehouseID=a.warehouseID','LEFT');
            $builder->groupby('a.warehouseID,a.inventID');
            $builder->orderby('a.Date');
            $items = $builder->get()->getResult();

            $data = ['location'=>$warehouse,'category'=>$category,'account'=>$account,'items'=>$items];
            return view('stocks-report',$data);
        }
        else
        {
            return redirect()->back();
        }
    }

    public function listOrders()
    {;
        $user = session()->get('loggedUser');
        $builder = $this->db->table('tblprf a');
        $builder->select('a.*,b.Comment');
        $builder->join('(Select Comment,OrderNo from tblreview group by reviewID order by reviewID DESC) b','b.OrderNo=a.OrderNo','LEFT');
        $builder->WHERE('a.accountID',$user);
        $builder->groupBy('a.OrderNo');
        $orders = $builder->get()->getResult();
        //canvass 
        $builder = $this->db->table('tblcanvass_form');
        $builder->select('*');
        $builder->WHERE('accountID',$user);
        $canvass = $builder->get()->getResult();
        //po
        $builder = $this->db->table('tblcanvass_form a');
        $builder->select('a.Reference,b.purchaseNumber,b.Date,b.Status,a.OrderNo');
        $builder->join('tblpurchase_logs b','b.Reference=a.Reference','LEFT');
        $builder->WHERE('a.accountID',$user);
        $po = $builder->get()->getResult();

        $data = ['orders'=>$orders,'canvass'=>$canvass,'po'=>$po];
        return view('list-orders',$data);
    }

    public function generatePRF($id)
    {
        //requestor
        $builder = $this->db->table('tblprf a');
        $builder->select('b.Fullname,b.Signatures');
        $builder->join('tblaccount b','b.accountID=a.accountID','LEFT');
        $builder->WHERE('a.OrderNo',$id);
        $requestor = $builder->get()->getResult();

        $purchaseModel = new \App\Models\purchaseModel();
        $purchase = $purchaseModel->WHERE('OrderNo',$id)->first();
        //items
        $OrderItemModel = new \App\Models\OrderItemModel();
        $items = $OrderItemModel->WHERE('OrderNo',$id)->findAll();
        //approver
        $builder = $this->db->table('tblreview a');
        $builder->select('b.Fullname,b.Department,b.Signatures');
        $builder->join('tblaccount b','b.accountID=a.accountID','LEFT');
        $builder->WHERE('a.OrderNo',$id);
        $list = $builder->get()->getResult();

        $data = ['purchase'=>$purchase,'code'=>$id,'items'=>$items,'list'=>$list,'requestor'=>$requestor];
        return view('generate-prf',$data);
    }

    public function export($id)
    {
        //assignment
        $builder = $this->db->table('tblcanvass_form a');
        $builder->select('d.Fullname,d.Signatures,b.PurchaseType');
        $builder->join('tblprf b','b.OrderNo=a.OrderNo','LEFT');
        $builder->join('tblassignment c','c.prfID=b.prfID','LEFT');
        $builder->join('tblaccount d','d.accountID=c.accountID','LEFT');
        $builder->WHERE('a.Reference',$id);
        $assign = $builder->get()->getResult();
        //get the requestor name
        $builder = $this->db->table('tblcanvass_form a');
        $builder->select('b.Fullname');
        $builder->join('tblaccount b','b.accountID=a.accountID','LEFT');
        $builder->WHERE('a.Reference',$id);
        $requestor = $builder->get()->getResult();
        //canvass form
        $canvasFormModel = new \App\Models\canvasFormModel();
        $form = $canvasFormModel->WHERE('Reference',$id)->first();
        //quotation
        $builder = $this->db->table('tblcanvass_sheet a');
        $builder->select('a.*,b.Qty,b.ItemUnit,b.Item_Name,b.Specification');
        $builder->join('tbl_order_item b','b.orderID=a.orderID','LEFT');
        $builder->WHERE('a.Reference',$id);
        $list = $builder->get()->getResult();
        //approver
        $builder = $this->db->table('tblcanvass_review a');
        $builder->select('b.Fullname,b.Department,b.Signatures');
        $builder->join('tblaccount b','b.accountID=a.accountID','LEFT');
        $builder->WHERE('a.Reference',$id);
        $account = $builder->get()->getResult();
        $data = ['form'=>$form,'list'=>$list,'account'=>$account,'requestor'=>$requestor,'assign'=>$assign];
        return view('export',$data);
    }

    public function createCanvas($id=null)
    {
        $builder = $this->db->table('tblprf');
        $builder->select('*');
        $builder->WHERE('OrderNo',$id);
        $prf = $builder->get()->getResult();
        //order
        $builder = $this->db->table('tbl_order_item');
        $builder->select('*');
        $builder->WHERE('OrderNo',$id);
        $item = $builder->get()->getResult();

        $data = ['prf'=>$prf,'item'=>$item,'id'=>$id];
        return view('create-canvass-sheet',$data);
    }

    public function approver()
    {
        $user = session()->get('loggedUser');
        $builder = $this->db->table('tblreview a');
        $builder->select('a.reviewID,a.OrderNo,a.DateReceived,a.DateApproved,a.Status,b.Department,b.DateNeeded,b.PurchaseType,c.Fullname');
        $builder->join('tblprf b','b.OrderNo=a.OrderNo','LEFT');
        $builder->join('tblaccount c','c.accountID=b.accountID','LEFT');
        $builder->WHERE('a.accountID',$user);
        $builder->groupBy('a.reviewID');
        $review = $builder->get()->getResult();
        //assignment
        $builder = $this->db->table('tblprf a');
        $builder->select('a.prfID,a.DatePrepared,a.Department,a.Reason,a.DateNeeded,a.OrderNo,c.Fullname,b.Status');
        $builder->join('tblassignment b','b.prfID=a.prfID','LEFT');
        $builder->join('tblaccount c','c.accountID=b.accountID','LEFT');
        $builder->WHERE('a.Status',3)->WHERE('PurchaseType','Regular Purchase');
        $assign = $builder->get()->getResult();
        //account
        $role = ['Staff','Administrator'];
        $builder = $this->db->table('tblaccount');
        $builder->select('*');
        $builder->WHEREIN('systemRole',$role)->WHERE('Status',1);
        $account = $builder->get()->getResult();
        //purchase order
        $builder = $this->db->table('tblpurchase_review a');
        $builder->select('a.prID,a.DateReceived,b.Reference,a.Status,a.purchaseNumber,a.DateApproved,c.OrderNo');
        $builder->join('tblpurchase_logs b','b.purchaseNumber=a.purchaseNumber','LEFT');
        $builder->join('tblcanvass_form c','c.Reference=b.Reference','LEFT');
        $builder->WHERE('a.accountID',$user);
        $builder->groupBy('a.prID');
        $purchase = $builder->get()->getResult();
        $data = ['review'=>$review,'assign'=>$assign,'account'=>$account,'purchase'=>$purchase];
        return view('approver',$data);
    }

    public function canvassRequest()
    {
        $user = session()->get('loggedUser');
        $builder = $this->db->table('tblcanvass_review a');
        $builder->select('a.DateReceived,a.Reference,b.DateNeeded,b.Department,a.Status,c.Fullname,b.OrderNo,a.accountID');
        $builder->join('tblcanvass_form b','b.Reference=a.Reference','LEFT');
        $builder->join('tblaccount c','c.accountID=b.accountID','LEFT');
        $builder->WHERE('a.accountID',$user);
        $builder->groupBy('a.crID')->orderby('a.crID','DESC');
        $list = $builder->get()->getResult();

        $data = ['list'=>$list];
        return view ('canvass-sheet-request',$data);
    }

    public function localPurchase()
    {
        $user = session()->get('loggedUser');
        $builder = $this->db->table('tblcanvass_review a');
        $builder->select('a.DateReceived,a.Reference,b.DateNeeded,b.Department,a.Status,c.Fullname,b.OrderNo,a.accountID,c.Department as Dept');
        $builder->join('tblcanvass_form b','b.Reference=a.Reference','LEFT');
        $builder->join('tblaccount c','c.accountID=b.accountID','LEFT');
        $builder->WHERE('a.accountID',$user);
        $builder->groupBy('a.crID')->orderby('a.crID','DESC');
        $list = $builder->get()->getResult();

        $data = ['review'=>$list];
        return view ('selection-vendor',$data);
    }

    public function viewVendor($id=null)
    {
        //vendor
        $builder = $this->db->table('tblcanvass_sheet a');
        $builder->select('a.*,b.Item_Name,b.Qty,b.Specification');
        $builder->join('tbl_order_item b','b.orderID=a.orderID','LEFT');
        $builder->WHERE('a.Reference',$id);
        $list = $builder->get()->getResult();
        //canvass form
        $builder = $this->db->table('tblcanvass_form');
        $builder->select('DateNeeded,Department,OrderNo');
        $builder->WHERE('Reference',$id);
        $canvass = $builder->get()->getResult();

        $data = ['code'=>$id,'list'=>$list,'canvass'=>$canvass];
        return view ('view-vendor',$data);
    }

    public function purchaseOrder()
    {
        $builder = $this->db->table('tblcanvass_form a');
        $builder->select('a.DateNeeded,a.DatePrepared,a.Department,a.OrderNo,a.Reference,b.Status,b.Comment');
        $builder->join('tblpurchase_logs b','b.Reference=a.Reference','LEFT');
        $builder->WHERE('a.Status',4);
        $builder->groupBy('a.Reference');
        $canvass = $builder->get()->getResult();

        $data = ['canvass'=>$canvass];
        return view('purchase-order',$data);
    }

    public function Modify($id)
    {
        $builder = $this->db->table('tblcanvass_sheet a');
        $builder->select('a.*,b.Item_Name,b.ItemUnit,b.Qty,b.Specification');
        $builder->join('tbl_order_item b','b.orderID=a.orderID','LEFT');
        $builder->WHERE('a.Remarks','Selected')->WHERE('a.Reference',$id);
        $record = $builder->get()->getResult();

        $data  = ['record'=>$record,'Reference'=>$id];
        return view('modify-purchase',$data);
    }

    public function createPO()
    {
        $purchaseOrderModel = new \App\Models\purchaseOrderModel();
        $systemLogsModel = new \App\Models\systemLogsModel();
        $purchaseReviewModel = new \App\Models\purchaseReviewModel();
        $canvasModel = new \App\Models\canvassModel();
        $purchaseModel = new \App\Models\purchaseModel();
        //data
        $val = $this->request->getPost('value');
        $date = date('Y-m-d');
        $status = 0;
        $user = session()->get('loggedUser');
        //validate if already exist
        $validation  = $this->validate([
            'value'=>'is_unique[tblpurchase_logs.Reference]'
        ]);
        if(!$validation)
        {
            echo "Invalid Request. The selected vendor was already generated";
        }
        else
        {
            $builder = $this->db->table('tblcanvass_sheet');
            $builder->select('COUNT(Reference)total,Supplier,Vatable,OrderNo');
            $builder->WHERE('Reference',$val);
            $builder->groupBy('Reference,Supplier,Vatable');
            $data = $builder->get();
            foreach($data->getResult() as $row)
            {
                $code="";
                $builder = $this->db->table('tblpurchase_logs');
                $builder->select('COUNT(purchaseLogID)+1 as total');
                $list = $builder->get();
                if($li = $list->getRow())
                {
                    $code = "PO-".str_pad($li->total, 9, '0', STR_PAD_LEFT);
                }
                //save
                $values = ['purchaseNumber'=>$code,'Reference'=>$val, 'Status'=>$status,'Date'=>$date,'accountID'=>$user,'Remarks'=>'OPEN','Comment'=>''];
                $purchaseOrderModel->save($values);
                //get the PO Number
                $purchase = $purchaseOrderModel->WHERE('purchaseNumber',$code)->first();
                //update the canvass Sheet
                $builder = $this->db->table('tblcanvass_sheet');
                $builder->select('canvassID');
                $builder->WHERE('Vatable',$row->Vatable)->WHERE('Supplier',$row->Supplier)->WHERE('Reference',$val);
                $datas = $builder->get();
                foreach($datas->getResult() as $rows)
                {
                    $record = ['purchaseLogID'=>$purchase['purchaseLogID']];
                    $canvasModel->update($rows->canvassID,$record);
                }
                //system logs
                $value = ['accountID'=>$user,'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Created PO Number '.$code];
                $systemLogsModel->save($value);
                //get the approver from purchase order setup
                $purchaseOrder = $purchaseModel->WHERE('OrderNo',$row->OrderNo)->first();
                if($purchaseOrder['PurchaseType']=="Regular Purchase")
                {
                    $builder = $this->db->table('tblaccount');
                    $builder->select('accountID,Fullname,Email');
                    $builder->WHERE('systemRole','Administrator')->WHERE('Department','General Services');
                    $data = $builder->get();
                    if($row = $data->getRow())
                    {
                        //save the data
                        $values = ['accountID'=>$row->accountID,'purchaseNumber'=>$code,'DateReceived'=>date('Y-m-d'),'Status'=>0,'DateApproved'=>''];
                        $purchaseReviewModel->save($values);
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
                        <tr><td><center><h1>Purchase Order Form</h1></center></td></tr>
                        <tr><td><center>Hi, ".$row->Fullname."</center></td></tr>
                        <tr><td><center>This is from FastCat System, sending you a reminder that requesting for your approval of the generated Purchase Order.</center></td></tr>
                        <tr><td><center>Purchase Order No</center></td></tr>
                        <tr><td><center><h2>".$code."</h2></center></td></tr>
                        <tr><td><center>Please login to your account @ https:fastcat-ims.com.</center></td></tr>
                        <tr><td><center>This is a system message please don't reply. Thank you</center></td></tr>
                        <tr><td><center>FastCat IT Support</center></td></tr></tbody></table></center>";
                        $subject = "Purchase Order Form - For Approval";
                        $email->setSubject($subject);
                        $email->setMessage($template);
                        $email->send();
                    }
                }
                else
                {
                    $builder = $this->db->table('tblaccount');
                    $builder->select('accountID,Fullname,Email');
                    $builder->WHERE('systemRole','Administrator')->WHERE('Department','Procurement');
                    $data = $builder->get();
                    if($row = $data->getRow())
                    {
                        //save the data
                        $values = ['accountID'=>$row->accountID,'purchaseNumber'=>$code,'DateReceived'=>date('Y-m-d'),'Status'=>0,'DateApproved'=>''];
                        $purchaseReviewModel->save($values);
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
                        <tr><td><center><h1>Purchase Order Form</h1></center></td></tr>
                        <tr><td><center>Hi, ".$row->Fullname."</center></td></tr>
                        <tr><td><center>This is from FastCat System, sending you a reminder that requesting for your approval of the generated Purchase Order.</center></td></tr>
                        <tr><td><center>Purchase Order No</center></td></tr>
                        <tr><td><center><h2>".$code."</h2></center></td></tr>
                        <tr><td><center>Please login to your account @ https:fastcat-ims.com.</center></td></tr>
                        <tr><td><center>This is a system message please don't reply. Thank you</center></td></tr>
                        <tr><td><center>FastCat IT Support</center></td></tr></tbody></table></center>";
                        $subject = "Purchase Order Form - For Approval";
                        $email->setSubject($subject);
                        $email->setMessage($template);
                        $email->send();
                    }
                }
            }
            echo "success";
        }
    }

    public function saveStocks()
    {
        $stockModel = new \App\Models\stocksModel();
        $inventoryModel = new \App\Models\inventoryModel();
        $systemLogsModel = new \App\Models\systemLogsModel();
        $qrModel = new \App\Models\qrcodeModel();
        //data
        $product = $this->request->getPost('product');
        $date = date('Y-m-d');
        $num_stocks = $this->request->getPost('num_stocks');
        $unitPrice = $this->request->getPost('unitPrice');
        $totalPrice = $this->request->getPost('totalPrice');
        $details = $this->request->getPost('details');
        $user = session()->get('loggedUser');

        $validation = $this->validate([
           'product'=>'required',
           'num_stocks'=>'required',
           'unitPrice'=>'required',
           'totalPrice'=>'required',
           'details'=>'required' 
        ]);
        if(!$validation)
        {
            session()->setFlashdata('fail',"Invalid! Please fill in the form");
            return redirect()->to('/manage')->withInput();
        }
        else
        {
            $values = [
                'DateAdded'=>$date,'inventID'=>$product,'Qty'=>$num_stocks,
                'UnitPrice'=>$unitPrice,'TotalPrice'=>$totalPrice,'Details'=>$details,'accountID'=>$user
            ];
            $stockModel->save($values);
            //update the stocks
            $inventory = $inventoryModel->WHERE('inventID',$product)->first();
            $newQty = $inventory['Qty']+$num_stocks;
            $value = ['Qty'=>$newQty];
            $inventoryModel->update($product,$value);
            //save logs
            $records = [
                'accountID'=>$user,'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Added stocks to '.$inventory['productName']
            ];
            $systemLogsModel->save($records);
            for($i=0;$i<$num_stocks;$i++)
            {
                //count the items per inventory and add qrcode
                $builder = $this->db->table('tblqrcode');
                $builder->select('COUNT(*) as total');
                $builder->WHERE('inventID',$product);
                $data = $builder->get();
                if($row = $data->getRow())
                {
                    $values = ['inventID'=>$product,'TextValue'=>$inventory['productID'].$row->total];
					$qrModel->save($values);
                }
            }
            session()->setFlashdata('success',"Great! Successfully submitted");
            return redirect()->to('/manage')->withInput();
        }
    }

    public function removeCategory()
    {
        $val = $this->request->getPost('value');
        $builder = $this->db->table('tblcategory');
        $builder->WHERE('categoryID',$val);
        $builder->delete();
        echo "success";
    }

    public function removeLocation()
    {
        $val = $this->request->getPost('value');
        $builder = $this->db->table('tblwarehouse');
        $builder->WHERE('warehouseID',$val);
        $builder->delete();
        echo "success";
    }

    public function removeIndustry()
    {
        $val = $this->request->getPost('value');
        $builder = $this->db->table('tblindustry');
        $builder->WHERE('industryID',$val);
        $builder->delete();
        echo "success";
    }

    public function addAssignment()
    {
        $assignmentModel = new \App\Models\assignmentModel();
        //data
        $prf = $this->request->getPost('prfID');
        $receiver = $this->request->getPost('receiver');

        $validation = $this->validate([
            'prfID'=>'is_unique[tblassignment.prfID]'
        ]);
        if(!$validation)
        {
            echo "Invalid! Already assigned";
        }
        else
        {
            $values = [
                'prfID'=>$prf, 'accountID'=>$receiver,'Date'=>date('Y-m-d'),'Status'=>0
            ];
            $assignmentModel->save($values);
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

    public function acceptAssignment()
    {
        $assignmentModel = new \App\Models\assignmentModel();
        $val = $this->request->getPost('value');
        $values = ['Status'=>1];
        $assignmentModel->update($val,$values);
        echo "success";
    }

    public function acceptRequest()
    {
        $canvasFormModel = new \App\Models\canvasFormModel();
        $reviewCanvassModel = new \App\Models\reviewCanvassModel();
        $purchaseModel = new \App\Models\purchaseModel();
        $taskModel = new \App\Models\taskModel();
        //data
        $user = session()->get('loggedUser');
        $code = $this->request->getPost('code');
        if(session()->get('role')!="Administrator")
        {
            $canvass = $canvasFormModel->WHERE('Reference',$code)->first();
            $purchase = $purchaseModel->WHERE('OrderNo',$canvass['OrderNo'])->first();
            $task = $taskModel->WHERE('ItemGroup',$purchase['ItemGroup'])->first();
            if($purchase['PurchaseType']=="Local Purchase")
            {
                if($canvass['Status']==0)
                {
                    //update the status of canvass sheet form
                    $value = ['Status'=>1];
                    $canvass = $canvasFormModel->WHERE('Reference',$code)->first();
                    $canvasFormModel->update($canvass['formID'],$value);
                    //send 
                    $builder = $this->db->table('tblaccount');
                    $builder->select('*');
                    $builder->WHERE('accountID',$task['accountID']);
                    $datas = $builder->get();
                    if($rows = $datas->getRow())
                    {
                        //save entry
                        $values = ['accountID'=>$task['accountID'],'Reference'=>$code,'DateReceived'=>date('Y-m-d'),'Status'=>0,'DateApproved'];
                        $reviewCanvassModel->save($values);
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
                        <tr><td><center>This is from FastCat System, sending you a reminder that requesting for your approval of the selected vendor per Item</center></td></tr>
                        <tr><td><p><center><b>Reference No : ".$code."</b></center></p></td><tr>
                        <tr><td><center>Please login to your account @ https:fastcat-ims.com.</center></td></tr>
                        <tr><td><center>This is a system message please don't reply. Thank you</center></td></tr>
                        <tr><td><center>FastCat IT Support</center></td></tr></tbody></table></center>";
                        $subject = "Canvass Sheet Form - For Approval";
                        $email->setSubject($subject);
                        $email->setMessage($template);
                        $email->send();
                    }
                }
                else if($canvass['Status']==3)
                {
                    //update the status of canvass sheet form
                    $value = ['Status'=>4];
                    $canvass = $canvasFormModel->WHERE('Reference',$code)->first();
                    $canvasFormModel->update($canvass['formID'],$value);
                }
                //approved
                $review = $reviewCanvassModel->WHERE('accountID',$user)->WHERE('Reference',$code)->first();
                $values = ['Status'=>1,'DateApproved'=>date('Y-m-d')];
                $reviewCanvassModel->update($review['crID'],$values);
            }
            else
            {
                //update the status of canvass sheet form
                $value = ['Status'=>4];
                $canvass = $canvasFormModel->WHERE('Reference',$code)->first();
                $canvasFormModel->update($canvass['formID'],$value);
                //approved
                $review = $reviewCanvassModel->WHERE('accountID',$user)->WHERE('Reference',$code)->first();
                $values = ['Status'=>1,'DateApproved'=>date('Y-m-d')];
                $reviewCanvassModel->update($review['crID'],$values);
            }
            echo "success";
        }
        else
        {
            $canvass = $canvasFormModel->WHERE('Reference',$code)->first();
            $purchase = $purchaseModel->WHERE('OrderNo',$canvass['OrderNo'])->first();
            $task = $taskModel->WHERE('ItemGroup',$purchase['ItemGroup'])->first();
            if($purchase['PurchaseType']=="Local Purchase")
            {
                if($canvass['Status']==0)
                {
                    //update the status of canvass sheet form
                    $value = ['Status'=>1];
                    $canvass = $canvasFormModel->WHERE('Reference',$code)->first();
                    $canvasFormModel->update($canvass['formID'],$value);
                    //send 
                    $builder = $this->db->table('tblaccount');
                    $builder->select('*');
                    $builder->WHERE('accountID',$task['accountID']);
                    $datas = $builder->get();
                    if($rows = $datas->getRow())
                    {
                        //save entry
                        $values = ['accountID'=>$task['accountID'],'Reference'=>$code,'DateReceived'=>date('Y-m-d'),'Status'=>0,'DateApproved'];
                        $reviewCanvassModel->save($values);
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
                        <tr><td><center>This is from FastCat System, sending you a reminder that requesting for your approval of the selected vendor per Item</center></td></tr>
                        <tr><td><p><center><b>Reference No : ".$code."</b></center></p></td><tr>
                        <tr><td><center>Please login to your account @ https:fastcat-ims.com.</center></td></tr>
                        <tr><td><center>This is a system message please don't reply. Thank you</center></td></tr>
                        <tr><td><center>FastCat IT Support</center></td></tr></tbody></table></center>";
                        $subject = "Canvass Sheet Form - For Approval";
                        $email->setSubject($subject);
                        $email->setMessage($template);
                        $email->send();
                    }
                }
                else if($canvass['Status']==3)
                {
                    //update the status of canvass sheet form
                    $value = ['Status'=>4];
                    $canvass = $canvasFormModel->WHERE('Reference',$code)->first();
                    $canvasFormModel->update($canvass['formID'],$value);
                }
                //approved
                $review = $reviewCanvassModel->WHERE('accountID',$user)->WHERE('Reference',$code)->first();
                $values = ['Status'=>1,'DateApproved'=>date('Y-m-d')];
                $reviewCanvassModel->update($review['crID'],$values);
            }
            else
            {
                //update the status of canvass sheet form
                $value = ['Status'=>4];
                $canvass = $canvasFormModel->WHERE('Reference',$code)->first();
                $canvasFormModel->update($canvass['formID'],$value);
                //approved
                $review = $reviewCanvassModel->WHERE('accountID',$user)->WHERE('Reference',$code)->first();
                $values = ['Status'=>1,'DateApproved'=>date('Y-m-d')];
                $reviewCanvassModel->update($review['crID'],$values);
            }
            echo "success";
        }
    }

    public function proceedRequest()
    {
        $canvasFormModel = new \App\Models\canvasFormModel();
        $reviewCanvassModel = new \App\Models\reviewCanvassModel();
        $canvassModel = new \App\Models\canvassModel();
        //data
        $user = session()->get('loggedUser');
        $code = $this->request->getPost('code');
        $rowCounts = count($this->request->getPost('itemID'));
        //update the canvass form status
        $value= ['Status'=>3];
        $canvass = $canvasFormModel->WHERE('Reference',$code)->first();
        $canvasFormModel->update($canvass['formID'],$value);
        //update the selected vendor
        for($i=0;$i<$rowCounts;$i++)
        {
            $id = $this->request->getPost('itemID')[$i];
            //values
            $values = ['Remarks'=>'Selected'];
            $canvassModel->update($id,$values);
        }
        //send to the final approver
        $builder = $this->db->table('tblaccount');
        $builder->select('*');
        $builder->WHERE('Department','Procurement')->WHERE('systemRole','Administrator');
        $datas = $builder->get();
        if($rows = $datas->getRow())
        {
            //save entry
            $values = ['accountID'=>$rows->accountID,'Reference'=>$code,'DateReceived'=>date('Y-m-d'),'Status'=>0,'DateApproved'];
            $reviewCanvassModel->save($values);
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
            <tr><td><center>This is from FastCat System, sending you a reminder that requesting for your approval of the selected vendor per Item</center></td></tr>
            <tr><td><p><center><b>Reference No : ".$code."</b></center></p></td><tr>
            <tr><td><center>Please login to your account @ https:fastcat-ims.com.</center></td></tr>
            <tr><td><center>This is a system message please don't reply. Thank you</center></td></tr>
            <tr><td><center>FastCat IT Support</center></td></tr></tbody></table></center>";
            $subject = "Canvass Sheet Form - For Approval";
            $email->setSubject($subject);
            $email->setMessage($template);
            $email->send();
        }
        //update the approver status
        $review = $reviewCanvassModel->WHERE('accountID',$user)->WHERE('Reference',$code)->first();
        $values = ['Status'=>1,'DateApproved'=>date('Y-m-d')];
        $reviewCanvassModel->update($review['crID'],$values);
        echo "success";
    }

    public function cancelRequest()
    {
        $canvasFormModel = new \App\Models\canvasFormModel();
        $reviewCanvassModel = new \App\Models\reviewCanvassModel();
        $canvassModel = new \App\Models\canvassModel();
        //data
        $user = session()->get('loggedUser');
        $code = $this->request->getPost('code');
        $msg = $this->request->getPost('message');
        $status = 2;
        if(empty($msg))
        {
            echo "Invalid! Please try again";
        }
        else
        {
            //approver
            $review = $reviewCanvassModel->WHERE('accountID',$user)->WHERE('Reference',$code)->first();
            $values = ['Status'=>$status];
            $reviewCanvassModel->update($review['crID'],$values);
            //canvass form
            $value = ['Status'=>$status,'Comment'=>$msg];
            $canvass = $canvasFormModel->WHERE('Reference',$code)->first();
            $canvasFormModel->update($canvass['formID'],$value);
            //remove the Reference and Remarks
            $newValues = ['Reference'=>'','Remarks'=>''];
            $builder = $this->db->table('tblcanvass_sheet');
            $builder->select('canvassID');
            $builder->WHERE('OrderNo',$canvass['OrderNo']);
            $data = $builder->get();
            foreach($data->getResult() as $row)
            {
                $canvassModel->update($row->canvassID,$newValues);
            }
            echo "success";
        }
    }

    public function approve()
    {
        $purchaseOrderModel = new \App\Models\purchaseOrderModel();
        $purchaseReviewModel = new \App\Models\purchaseReviewModel();
        $val = $this->request->getPost('value');
        $date = date('Y-m-d');
        //update the approver status
        $values = ['Status'=>1,'DateApproved'=>$date];
        $purchaseReviewModel->update($val,$values);
        //update the PO status
        $purchase = $purchaseReviewModel->WHERE('prID',$val)->first();
        $purchase_order = $purchaseOrderModel->WHERE('purchaseNumber',$purchase['purchaseNumber'])->first();
        $values = ['Status'=>1];
        $purchaseOrderModel->update($purchase_order['purchaseLogID'],$values);
        //send email
        $builder = $this->db->table('tblcanvass_form a');
        $builder->select('b.Fullname,b.Email');
        $builder->join('tblaccount b','b.accountID=a.accountID','LEFT');
        $builder->WHERE('a.Reference',$purchase_order['Reference']);
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
            <tr><td><center><h1>Purchase Order Form</h1></center></td></tr>
            <tr><td><center>Hi, ".$row->Fullname."</center></td></tr>
            <tr><td><center>This is from FastCat System, we would like to inform you that your purchase request has already approved.</center></td></tr>
            <tr><td><center>Contact Materials/Procurement Department for more details.</center></td></tr>
            <tr><td><p><center><b>P.O. No : ".$purchase['purchaseNumber']."</b></center></p></td><tr>
            <tr><td><center>Please login to your account @ https:fastcat-ims.com.</center></td></tr>
            <tr><td><center>This is a system message please don't reply. Thank you</center></td></tr>
            <tr><td><center>FastCat IT Support</center></td></tr></tbody></table></center>";
            $subject = "Approved Purchase Order Form";
            $email->setSubject($subject);
            $email->setMessage($template);
            $email->send();
        }
        echo "success";
    }

    public function decline()
    {
        $purchaseOrderModel = new \App\Models\purchaseOrderModel();
        $purchaseReviewModel = new \App\Models\purchaseReviewModel();
        $val = $this->request->getPost('value');
        $msg = $this->request->getPost('message');
        if(empty($msg))
        {
            echo "Invalid! Please leave a message";
        }
        else
        {
            //update the approver status
            $values = ['Status'=>2];
            $purchaseReviewModel->update($val,$values);
            //update the PO status
            $purchase = $purchaseReviewModel->WHERE('prID',$val)->first();
            $purchase_order = $purchaseOrderModel->WHERE('purchaseNumber',$purchase['purchaseNumber'])->first();
            $values = ['Status'=>2,'Comment'=>$msg];
            $purchaseOrderModel->update($purchase_order['purchaseLogID'],$values);
            //send email
            $builder = $this->db->table('tblcanvass_form a');
            $builder->select('b.Fullname,b.Email');
            $builder->join('tblaccount b','b.accountID=a.accountID','LEFT');
            $builder->WHERE('a.Reference',$purchase_order['Reference']);
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
                <tr><td><center><h1>Purchase Order Form</h1></center></td></tr>
                <tr><td><center>Hi, ".$row->Fullname."</center></td></tr>
                <tr><td><center>This is from FastCat System, we would like to inform you that your request has been rejected.</center></td></tr>
                <tr><td><center>Reason : ".$msg."</center></td></tr>
                <tr><td><p><center><b>P.O. No : ".$purchase['purchaseNumber']."</b></center></p></td><tr>
                <tr><td><center>Please login to your account @ https:fastcat-ims.com.</center></td></tr>
                <tr><td><center>This is a system message please don't reply. Thank you</center></td></tr>
                <tr><td><center>FastCat IT Support</center></td></tr></tbody></table></center>";
                $subject = "Declined Purchase Order Form";
                $email->setSubject($subject);
                $email->setMessage($template);
                $email->send();
            }
            echo "success";
        }
    }

    public function searchVendor()
    {
        $val = "%".$this->request->getGet('keyword')."%";
        $builder = $this->db->table('tblsupplier');
        $builder->select('*');
        $builder->LIKE('supplierName',$val);
        $data = $builder->get();
        foreach($data->getResult() as $row)
        {
            ?>
            <option class="li" value="<?php echo $row->supplierID ?>"><?php echo $row->supplierName ?></option>
            <?php
        }
    }

    public function vendorInformation()
    {
        $val = "%".$this->request->getGet('value')."%";
        $builder = $this->db->table('tblsupplier');
        $builder->select('*');
        $builder->LIKE('supplierName',$val);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $info = array("Address"=>$row->Address,"contactNumber"=>$row->contactNumber,"contactPerson"=>$row->contactPerson);
            echo json_encode($info);
        }
    }

    public function ledger()
    {
        $builder = $this->db->table('tblsupplier');
        $builder->select('*');
        $record = $builder->get()->getResult();

        $data = ['vendor'=>$record];
        return view('ledger',$data);
    }

    public function overAllReport()
    {
        $cost=0;
        $builder = $this->db->table('tblcanvass_sheet a');
        $builder->select('SUM(a.Price*b.Qty)total');
        $builder->join('tbl_order_item b','b.orderID=a.orderID','LEFT');
        $builder->join('tblpurchase_logs c','c.Reference=a.Reference','LEFT');
        $builder->WHERE('c.Status',1);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $cost = $row->total;
        }
        $total=0;
        $builder = $this->db->table('tblprf');
        $builder->select('COUNT(prfID)total');
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $total = $row->total;
        }
        //tag as released
        $release=0;
        $builder = $this->db->table('tblpurchase_logs');
        $builder->select('COUNT(purchaseNumber)total');
        $builder->WHERE('Status',1);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $release = $row->total;
        }
        //tag as unreleased
        $unrelease=0;
        $builder = $this->db->table('tblpurchase_logs');
        $builder->select('COUNT(purchaseNumber)total');
        $builder->WHERE('Status',0);
        $data = $builder->get();
        if($row = $data->getRow())
        {
            $unrelease = $row->total;
        }
        //vendor
        $builder = $this->db->table('tblcanvass_sheet a');
        $builder->select('a.Supplier,SUM(a.Price*b.Qty)total');
        $builder->join('tbl_order_item b','b.orderID=a.orderID','LEFT');
        $builder->join('tblpurchase_logs c','c.Reference=a.Reference','LEFT');
        $builder->WHERE('a.Remarks','Selected')->WHERE('c.Status',1);
        $builder->groupBy('a.Supplier');
        $builder->orderBy('total','DESC')->limit(20);
        $vendor = $builder->get()->getResult();
        //graph total po
        $builder = $this->db->table('tblpurchase_logs');
        $builder->select('Date,COUNT(purchaseNumber)total');
        $builder->WHERE('Status',1);
        $builder->groupBy('Date');
        $po = $builder->get()->getResult();

        $data = ['total'=>$total,'release'=>$release,'unrelease'=>$unrelease,'cost'=>$cost,'vendor'=>$vendor,'po'=>$po];
        return view('overall-report',$data);
    }

    public function returnOrderReport()
    {
        $builder = $this->db->table('tblsupplier');
        $builder->select('*');
        $record = $builder->get()->getResult();

        $data = ['vendor'=>$record];
        return view('return-order-report',$data);
    }

    public function Issuance()
    {
        return view('issuance-report');
    }
}
