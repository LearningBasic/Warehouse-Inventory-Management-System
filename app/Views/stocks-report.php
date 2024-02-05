
<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Stocks Report</title>

		<!-- Site favicon -->
		<link
			rel="apple-touch-icon"
			sizes="180x180"
			href="assets/img/fastcat.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="32x32"
			href="assets/img/fastcat.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="assets/img/fastcat.png"
		/>

		<!-- Mobile Specific Metas -->
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>

		<!-- Google Font -->
		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="assets/vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="assets/vendors/styles/icon-font.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="assets/src/plugins/datatables/css/dataTables.bootstrap4.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="assets/src/plugins/datatables/css/responsive.bootstrap4.min.css"
		/>
		<link rel="stylesheet" type="text/css" href="assets/vendors/styles/style.css" />
        <style>
        /* Track */
            ::-webkit-scrollbar-track {
              background: #f1f1f1; 
            }

            /* Handle */
            ::-webkit-scrollbar-thumb {
              background: #888; 
            }

            /* Handle on hover */
            ::-webkit-scrollbar-thumb:hover {
              background: #555; 
            }
            ::-webkit-scrollbar {
                height: 4px;              /* height of horizontal scrollbar ← You're missing this */
                width: 4px;               /* width of vertical scrollbar */
                border: 1px solid #d5d5d5;
              }
            .tableFixHead thead th { position: sticky; top: 0; z-index: 1;color:#fff;background-color:#0275d8;}

            /* Just common table stuff. Really. */
            table  { border-collapse: collapse; width: 100%; }
            th, td { padding: 8px 16px;color:#000; }
            tbody{color:#000;}
            tr:nth-child(even) {
            background-color: #f2f2f2;
            }
            
        </style>
	</head>
	<body>
		<div class="pre-loader">
			<div class="pre-loader-box">
				<div class="loader-logo">
					<img src="assets/img/fastcat.png" alt="Fastcat" width="100"/>
				</div>
				<div class="loader-progress" id="progress_div">
					<div class="bar" id="bar1"></div>
				</div>
				<div class="percent" id="percent1">0%</div>
				<div class="loading-text">Loading...</div>
			</div>
		</div>

		<div class="header">
			<div class="header-left">
				<div class="menu-icon bi bi-list"></div>
			</div>
			<div class="header-right">
				<div class="dashboard-setting user-notification">
					<div class="dropdown">
						<a
							class="dropdown-toggle no-arrow"
							href="javascript:;"
							data-toggle="right-sidebar"
						>
							<i class="dw dw-settings2"></i>
						</a>
					</div>
				</div>
				<div class="user-info-dropdown">
					<div class="dropdown">
						<a
							class="dropdown-toggle"
							href="#"
							role="button"
							data-toggle="dropdown"
						>
							<span class="user-icon">
                                <i class="dw dw-user1"></i>
							</span>
							<span class="user-name"><?php echo session()->get('fullname'); ?></span>
						</a>
						<div
							class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list"
						>
							<a class="dropdown-item" href="<?=site_url('profile')?>"
								><i class="dw dw-user1"></i> Profile</a
							>
							<a class="dropdown-item" onclick="return confirm('Do you want to sign out?')" href="<?=site_url('/logout')?>"
								><i class="dw dw-logout"></i> Log Out</a
							>
						</div>
					</div>
				</div>
				<div class="github-link">
					<a href="https://github.com/dropways/deskapp" target="_blank"
						><img src="vendors/images/github.svg" alt=""
					/></a>
				</div>
			</div>
		</div>

		<div class="right-sidebar">
			<div class="sidebar-title">
				<h3 class="weight-600 font-16 text-blue">
					Layout Settings
					<span class="btn-block font-weight-400 font-12"
						>User Interface Settings</span
					>
				</h3>
				<div class="close-sidebar" data-toggle="right-sidebar-close">
					<i class="icon-copy ion-close-round"></i>
				</div>
			</div>
			<div class="right-sidebar-body customscroll">
				<div class="right-sidebar-body-content">
					<h4 class="weight-600 font-18 pb-10">Header Background</h4>
					<div class="sidebar-btn-group pb-30 mb-10">
						<a
							href="javascript:void(0);"
							class="btn btn-outline-primary header-white active"
							>White</a
						>
						<a
							href="javascript:void(0);"
							class="btn btn-outline-primary header-dark"
							>Dark</a
						>
					</div>

					<h4 class="weight-600 font-18 pb-10">Sidebar Background</h4>
					<div class="sidebar-btn-group pb-30 mb-10">
						<a
							href="javascript:void(0);"
							class="btn btn-outline-primary sidebar-light"
							>White</a
						>
						<a
							href="javascript:void(0);"
							class="btn btn-outline-primary sidebar-dark active"
							>Dark</a
						>
					</div>

					<h4 class="weight-600 font-18 pb-10">Menu Dropdown Icon</h4>
					<div class="sidebar-radio-group pb-10 mb-10">
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebaricon-1"
								name="menu-dropdown-icon"
								class="custom-control-input"
								value="icon-style-1"
								checked=""
							/>
							<label class="custom-control-label" for="sidebaricon-1"
								><i class="fa fa-angle-down"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebaricon-2"
								name="menu-dropdown-icon"
								class="custom-control-input"
								value="icon-style-2"
							/>
							<label class="custom-control-label" for="sidebaricon-2"
								><i class="ion-plus-round"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebaricon-3"
								name="menu-dropdown-icon"
								class="custom-control-input"
								value="icon-style-3"
							/>
							<label class="custom-control-label" for="sidebaricon-3"
								><i class="fa fa-angle-double-right"></i
							></label>
						</div>
					</div>

					<h4 class="weight-600 font-18 pb-10">Menu List Icon</h4>
					<div class="sidebar-radio-group pb-30 mb-10">
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-1"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-1"
								checked=""
							/>
							<label class="custom-control-label" for="sidebariconlist-1"
								><i class="ion-minus-round"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-2"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-2"
							/>
							<label class="custom-control-label" for="sidebariconlist-2"
								><i class="fa fa-circle-o" aria-hidden="true"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-3"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-3"
							/>
							<label class="custom-control-label" for="sidebariconlist-3"
								><i class="dw dw-check"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-4"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-4"
								checked=""
							/>
							<label class="custom-control-label" for="sidebariconlist-4"
								><i class="icon-copy dw dw-next-2"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-5"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-5"
							/>
							<label class="custom-control-label" for="sidebariconlist-5"
								><i class="dw dw-fast-forward-1"></i
							></label>
						</div>
						<div class="custom-control custom-radio custom-control-inline">
							<input
								type="radio"
								id="sidebariconlist-6"
								name="menu-list-icon"
								class="custom-control-input"
								value="icon-list-style-6"
							/>
							<label class="custom-control-label" for="sidebariconlist-6"
								><i class="dw dw-next"></i
							></label>
						</div>
					</div>

					<div class="reset-options pt-30 text-center">
						<button class="btn btn-danger" id="reset-settings">
							Reset Settings
						</button>
					</div>
				</div>
			</div>
		</div>

		<div class="left-side-bar">
			<div class="brand-logo">
				<a href="<?=site_url('/dashboard')?>">
					<img src="assets/img/fastcat.png" alt="" class="dark-logo" width="100"/>
					<img
						src="assets/img/fastcat.png"
						alt="" width="100"
						class="light-logo"
					/>
				</a>
				<div class="close-sidebar" data-toggle="left-sidebar-close">
					<i class="ion-close-round"></i>
				</div>
			</div>
			<div class="menu-block customscroll">
				<div class="sidebar-menu">
					<ul id="accordion-menu">
						<li class="dropdown">
							<a href="<?=site_url('dashboard')?>" class="dropdown-toggle no-arrow">
								<span class="micon bi bi-house"></span
								><span class="mtext">Home</span>
							</a>
						</li>
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
                            <i class="micon dw dw-server"></i><span class="mtext">Inventory</span>
							</a>
							<ul class="submenu">
								<li><a href="<?=site_url('stocks')?>">All Stocks</a></li>
								<li><a href="<?=site_url('add')?>">Add Item</a></li>
								<?php if(session()->get('role')=="Administrator"){ ?>
								<li><a href="<?=site_url('manage')?>">Manage Stocks</a></li>
                                <?php } ?>
							</ul>
						</li>
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
                            <i class="micon dw dw-shopping-cart"></i><span class="mtext">Purchasing</span>&nbsp;<span class="badge badge-pill bg-primary text-white" id="notification">0</span>
							</a>
							<ul class="submenu">
                                <li><a href="<?=site_url('orders')?>">Order Materials</a></li>
								<li><a href="<?=site_url('list-orders')?>">List Order</a></li>
								<?php if(session()->get('role')=="Administrator"||session()->get('role')=="Editor"){ ?>
								<li><a href="<?=site_url('approve-orders')?>">For Approval&nbsp;<span class="badge badge-pill bg-primary text-white" id="notifications">0</span></a></li>
								<li><a href="<?=site_url('canvass-sheet-request')?>">Canvass Sheet&nbsp;<span class="badge badge-pill bg-primary text-white" id="notif">0</span></a></li>
								<?php } ?>
								<?php if(session()->get('role')=="Staff"){?>
								<li><a href="<?=site_url('assign')?>">Assigned PRF</a></li>
								<li><a href="<?=site_url('local-purchase')?>">Local Purchase</a></li>
								<li><a href="<?=site_url('purchase-order')?>">Purchase Order</a></li>
								<?php } ?>
							</ul>
						</li>
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
                                <i class="micon dw dw-clipboard1"></i><span class="mtext">Receiving</span>
							</a>
							<ul class="submenu">
                                <li><a href="<?=site_url('receiving-item')?>">Receiving Item</a></li>
								<li><a href="<?=site_url('receive-order')?>">Received Order</a></li>
								<li><a href="<?=site_url('reserved')?>">Reserved</a></li>
							</ul>
						</li>
						<?php if(session()->get('role')=="Administrator"||session()->get('role')=="Editor"){ ?>
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
                                <i class="micon dw dw-shop"></i><span class="mtext">Suppliers</span>
							</a>
							<ul class="submenu">
                                <li><a href="<?=site_url('suppliers')?>">List of Suppliers</a></li>
								<li><a href="<?=site_url('add-supplier')?>">Add Supplier</a></li>
							</ul>
						</li>
						<?php } ?>
						<?php if(session()->get('role')=="Administrator"){ ?>
						<li class="dropdown">
							<a href="<?=site_url('request')?>" class="dropdown-toggle no-arrow">
								<span class="micon bi bi-clipboard-data"></span
								><span class="mtext">Request</span>
							</a>
						</li>
						<?php } ?>
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
                                <i class="micon dw dw-bar-chart-1"></i><span class="mtext">Reports</span>
							</a>
							<?php if(session()->get('role')=="Administrator"||session()->get('role')=="Editor"){ ?>
							<ul class="submenu">
								<li><a href="<?=site_url('overall-report')?>">Main Report</a></li>
                                <li><a href="<?=site_url('report-stocks')?>" class="active">Stocks Report</a></li>
								<li><a href="<?=site_url('ledger')?>">Vendor's Ledger</a></li>
							</ul>
							<?php }else{ ?>
							<ul class="submenu">
								<li><a href="<?=site_url('add-report')?>">Create Report</a></li>
							</ul>
							<?php } ?>
						</li>
						<li>
							<div class="dropdown-divider"></div>
						</li>
						<li>
							<div class="sidebar-small-cap">Extra</div>
						</li>
						<?php if(session()->get('role')=="Administrator"){ ?>
						<li>
							<a href="<?=site_url('configuration')?>" class="dropdown-toggle no-arrow">
								<span class="micon dw dw-settings1"></span
								><span class="mtext">System configuration</span>
							</a>
						</li>
						<?php } ?>
                        <li>
							<a href="<?=site_url('profile')?>" class="dropdown-toggle no-arrow">
								<span class="micon dw dw-user1"></span
								><span class="mtext">Profile</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="mobile-menu-overlay"></div>

		<div class="main-container">
			<div class="xs-pd-20-10 pd-ltr-20">
				<div class="tabs">
                <ul class="nav nav-pills justify-content-left" role="tablist">
                        <li class="nav-item">
                            <a
                                class="nav-link active text-blue"
                                data-toggle="tab"
                                href="#home6"
                                role="tab"
                                aria-selected="true"
                                >Inventory Report</a
                            >
                        </li>
                        <li class="nav-item">
                            <a
                                class="nav-link text-blue"
                                data-toggle="tab"
                                href="#profile6"
                                role="tab"
                                aria-selected="false"
                                >Actual Report</a
                            >
                        </li>
						<li class="nav-item">
                            <a
                                class="nav-link text-blue"
                                data-toggle="tab"
                                href="#expire6"
                                role="tab"
                                aria-selected="false"
                                >Nearly Expired</a
                            >
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="home6" role="tabpanel">
                            <br/>
                            <div class="row g-3">
                                <div class="col-12 form-group">
                                    <div class="card-box">
                                        <div class="card-header">Stocks Report</div>
                                        <div class="card-body">
                                            <form method="get" id="frmSearch" class="row g-3">
                                                <div class="col-lg-4 form-group">
                                                    <label>Warehouse/Vessel</label>
                                                    <select class="form-control custom-select2" name="location">
                                                        <option value="">Choose</option>
                                                        <?php foreach($location as $row): ?>
                                                            <option value="<?php echo $row->warehouseID ?>"><?php echo $row->warehouseName ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 form-group">
                                                    <label>Category</label>
                                                    <select class="form-control custom-select2" name="category">
                                                        <option value="ALL">ALL</option>
                                                        <?php foreach($category as $row): ?>
                                                            <option value="<?php echo $row->categoryID ?>"><?php echo $row->categoryName ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 form-group">
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary form-control text-white" id="btnSearch"><i class="icon-copy dw dw-search"></i>&nbsp;Search</button>
                                                </div>
                                                <div class="col-lg-2 form-group">
                                                    <label>&nbsp;</label>
                                                    <a href="javascript:void(0);" onclick="exportf(this)" class="btn btn-outline-primary form-control"><i class="icon-copy dw dw-download"></i>&nbsp;Export</a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 form-group tableFixHead" style="height:400px;overflow-y:auto;">
                                    <table  class="table stripe table-bordered hover nowrap" id="table">
                                        <thead>
                                            <th>Product ID</th>
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            <th>Actual Stocks</th>
                                            <th>Damage Items</th>
                                            <th>Overhaul Items</th>
                                            <th>Total</th>
                                        </thead>
                                        <tbody id="tblreport">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile6" role="tabpanel">
							<br/>
							<div class="row g-3">
								<div class="col-12 form-group">
									<div class="card-box">
										<div class="card-header">Search Inventory Report</div>
										<div class="card-body">
											<form method="GET" class="row g-3" id="frmReport">
												<div class="col-lg-2 form-group">
													<label>From</label>
													<input type="date" class="form-control" name="from"/>
												</div>
												<div class="col-lg-2 form-group">
													<label>To</label>
													<input type="date" class="form-control" name="to"/>
												</div>
												<div class="col-lg-3 form-group">
                                                    <label>Warehouse/Vessel</label>
                                                    <select class="form-control custom-select2" name="location" style="width:100%;">
                                                        <option value="">Choose</option>
                                                        <?php foreach($location as $row): ?>
                                                            <option value="<?php echo $row->warehouseID ?>"><?php echo $row->warehouseName ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
												<div class="col-lg-2 form-group">
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary form-control text-white" id="btnGenerate"><i class="icon-copy dw dw-search"></i>&nbsp;Search</button>
                                                </div>
											</form>
										</div>
									</div>
								</div>
								<div class="col-12 form-group tableFixHead" style="height:400px;overflow-y:auto;">
                                    <table  class="table stripe table-bordered hover nowrap">
                                        <thead>
                                            <th>Product ID</th>
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            <th>On-Hand</th>
                                            <th>Actual</th>
                                            <th>Variances</th>
											<th>Total</th>
                                        </thead>
                                        <tbody id="tblvariances">

                                        </tbody>
                                    </table>
                                </div>
							</div>
                        </div>
						<div class="tab-pane fade" id="expire6" role="tabpanel">
							<br/>
							<div class="card-box">
								<div class="card-header">Items/Equipment - Nearly Expired</div>
								<div class="card-body">
									<table class="data-table table hover nowrap">
										<thead>
											<th>Item Group</th>
											<th>Product ID</th>
											<th>Product Name</th>
											<th>Unit Price</th>
											<th>Qty</th>
											<th>Expiration</th>
										</thead>
										<tbody>
											<?php foreach($items as $row): ?>
												<tr>
													<td><?php echo $row->categoryName ?></td>
													<td><?php echo $row->productID ?></td>
													<td><?php echo $row->productName ?></td>
													<td><?php echo number_format($row->unitPrice,2) ?></td>
													<td><?php echo number_format($row->Qty,0) ?></td>
													<td><?php echo $row->ExpirationDate ?></td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
                    </div>
                </div>
			</div>
		</div>
		<!-- js -->
		<script src="assets/vendors/scripts/core.js"></script>
		<script src="assets/vendors/scripts/script.min.js"></script>
		<script src="assets/vendors/scripts/process.js"></script>
		<script src="assets/vendors/scripts/layout-settings.js"></script>
		<script src="assets/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script src="assets/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
		<script src="assets/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
		<script src="assets/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
		<script src="assets/vendors/scripts/datatable-setting.js"></script>
        <script>
			$(document).ready(function()
			{
				notify();
			});
            $('#btnSearch').on('click',function(e)
            {
                e.preventDefault();
                $('#tblreport').html("<tr><td colspan='7'><center>Loading data...</center></td></tr>");
                var data = $('#frmSearch').serialize();
                $.ajax({
                    url:"<?=site_url('search-stocks')?>",method:"GET",
                    data:data,success:function(response)
                    {
                        if(response==="")
                        {
                            $('#tblreport').html("<tr><td colspan='7'><center>No Data</center></td></tr>");
                        }
                        else
                        {
                            $('#tblreport').html(response);
                        }
                    }
                });
            });
			$('#btnGenerate').on('click',function(e)
            {
                e.preventDefault();
                $('#tblvariances').html("<tr><td colspan='7'><center>Loading data...</center></td></tr>");
                var data = $('#frmReport').serialize();
                $.ajax({
                    url:"<?=site_url('search-inventory')?>",method:"GET",
                    data:data,success:function(response)
                    {
                        if(response==="")
                        {
                            $('#tblvariances').html("<tr><td colspan='7'><center>No Data</center></td></tr>");
                        }
                        else
                        {
                            $('#tblvariances').html(response);
                        }
                    }
                });
            });
			function notify()
			{
				$.ajax({
					url:"<?=site_url('notification')?>",method:"GET",
					success:function(response)
					{
						$('#notifications').html(response);
					}
				});
				$.ajax({
					url:"<?=site_url('canvas-notification')?>",method:"GET",
					success:function(response)
					{
						$('#notif').html(response);
					}
				});
				$.ajax({
					url:"<?=site_url('total-notification')?>",method:"GET",
					success:function(response)
					{
						$('#notification').html(response);
					}
				});
			}

			function exportf(elem) {
			var table = document.getElementById("table");
			var html = table.outerHTML;
			var url = 'data:application/vnd.ms-excel,' + escape(html); // Set your html table into url 
			elem.setAttribute("href", url);
			elem.setAttribute("download","stocks-report.xls"); // Choose the file name
			return false;
			}
        </script>
	</body>
</html>
