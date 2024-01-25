
<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>System Configuration</title>

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
                width: 0px;               /* width of vertical scrollbar */
                border: 1px solid #d5d5d5;
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
							</ul>
						</li>
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
                                <i class="micon dw dw-shop"></i><span class="mtext">Suppliers</span>
							</a>
							<ul class="submenu">
                                <li><a href="<?=site_url('suppliers')?>">List of Suppliers</a></li>
								<li><a href="<?=site_url('add-supplier')?>">Add Supplier</a></li>
							</ul>
						</li>
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
							<ul class="submenu">
								<li><a href="<?=site_url('overall-report')?>">Main Report</a></li>
                                <li><a href="<?=site_url('report-stocks')?>">Stocks Report</a></li>
								<li><a href="<?=site_url('ledger')?>">Vendor's Ledger</a></li>
							</ul>
						</li>
						<li>
							<div class="dropdown-divider"></div>
						</li>
						<li>
							<div class="sidebar-small-cap">Extra</div>
						</li>
						<?php if(session()->get('role')=="Administrator"){ ?>
						<li>
							<a href="<?=site_url('configuration')?>" class="active dropdown-toggle no-arrow">
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
				<?php if(!empty(session()->getFlashdata('fail'))) : ?>
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<?= session()->getFlashdata('fail'); ?>
					</div>
				<?php endif; ?>
				<?php if(!empty(session()->getFlashdata('success'))) : ?>
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						<?= session()->getFlashdata('success'); ?>
					</div>
				<?php endif; ?>
                <div class="tab">
                    <ul class="nav nav-pills justify-content-left" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active text-blue" data-toggle="tab" href="#home6" role="tab" aria-selected="true">Inventory Setup</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-blue" data-toggle="tab" href="#profile6" role="tab" aria-selected="false">User Management</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-blue" data-toggle="tab" href="#contact6" role="tab" aria-selected="false">System Logs/Activities</a>
                        </li>
						<li class="nav-item">
                            <a class="nav-link text-blue" data-toggle="tab" href="#purchase6" role="tab" aria-selected="false">System Setup</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="home6" role="tabpanel">
                            <div class="pd-20">
                                <div class="row g-3">
                                    <div class="col-lg-4 form-group">
                                        <div class="card-box">
                                            <div class="card-header"><i class="icon-copy dw dw-building1"></i>&nbsp;Industry</div>
                                            <div class="card-body">
                                                <div class="user-list" style="height:400px;overflow-y:auto;">
                                                    <ul id="listindustry"></ul>
                                                </div>
                                                <br/>
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#industryModal"><i class="icon-copy dw dw-add"></i> Add</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <div class="card-box">
                                            <div class="card-header"><i class="icon-copy dw dw-list"></i>&nbsp;Product Category</div>
                                            <div class="card-body">
                                                <div class="user-list" style="height:400px;overflow-y:auto;">
                                                    <ul id="listcategory"></ul>
                                                </div>
                                                <br/>
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#categoryModal"><i class="icon-copy dw dw-add"></i> Add</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <div class="card-box">
                                            <div class="card-header"><i class="icon-copy dw dw-house-11"></i>&nbsp;Assignment</div>
                                            <div class="card-body">
                                                <div class="user-list" style="height:400px;overflow-y:auto;">
                                                    <ul id="listwarehouse"></ul>
                                                </div>
                                                <br/>
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#warehouseModal"><i class="icon-copy dw dw-add"></i> Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile6" role="tabpanel">
                            <div class="pd-20">
								<div class="card-box">
									<div class="card-header"><i class="icon-copy dw dw-user-13"></i>&nbsp;User Accounts
										<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#accountModal" style="float:right;"><i class="icon-copy dw dw-add-user"></i>&nbsp;Add</a>
									</div>
									<div class="card-body">
										<table class="data-table table stripe hover nowrap">
											<thead>
												<th>Date Created</th>
												<th>Username</th>
												<th>Fullname</th>
												<th>Email</th>
												<th>Department</th>
												<th>Status</th>
												<th>User Role</th>
												<th>&nbsp;</th>
											</thead>
											<tbody>
												<?php foreach($account as $row): ?>
													<tr>
														<td><?php echo $row->DateCreated ?></td>
														<td><?php echo $row->username ?></td>
														<td><?php echo $row->Fullname ?></td>
														<td><?php echo $row->Email ?></td>
														<td><?php echo $row->Department ?></td>
														<td><?php if($row->Status==1){echo "<span class='badge bg-success text-white'>Active</span>";}else{echo "<span class='badge bg-danger text-white'>Inactive</span>";} ?></td>
														<td><?php echo $row->systemRole ?></td>
														<td>
															<div class="dropdown">
																<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
																	href="#" role="button" data-toggle="dropdown">
																	<i class="dw dw-more"></i>
																</a>
																<div class="dropdown-menu dropdown-menu-left dropdown-menu-icon-list">
																	<a class="dropdown-item" href="<?=site_url('edit-account/')?><?php echo $row->accountID ?>"><i class="icon-copy dw dw-edit"></i>Edit</a>
																	<button type="button" class="dropdown-item deadstock" value="<?php echo $row->accountID ?>"><i class="icon-copy dw dw-reload"></i>Reset</button>
																</div>
															</div>
														</td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="contact6" role="tabpanel">
                            <div class="pd-20">
								<div class="card-box">
									<div class="card-header">System Logs</div>
									<div class="card-body">
										<table class="data-table table stripe hover nowrap">
											<thead>
												<th>Date</th>
												<th>Fullname</th>
												<th>Activities</th>
											</thead>
											<tbody>
												<?php foreach($logs as $row): ?>
													<tr>
														<td><?php echo $row->Date ?></td>
														<td><?php echo $row->Fullname ?></td>
														<td><?php echo $row->Activity ?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
                            </div>
                        </div>
						<div class="tab-pane fade" id="purchase6" role="tabpanel">
							<div class="pd-20">
								<div class="card-box">
									<div class="card-header">User Account Control</div>
									<div class="card-body">
										<form method="post" class="row g-3" action="<?=base_url('save-settings')?>">
											<div class="col-12 form-group">
												<label>Employee Name</label>
												<select class="form-control custom-select2" name="user" style="width:100%;" required>
													<option value="">Choose</option>
													<?php foreach($admin as $row): ?>
														<option value="<?php echo $row->accountID ?>"><?php echo $row->Fullname ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											<div class="col-12 form-group">
												<button type="submit" class="btn btn-primary" id="btnSave">Save Changes</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
                    </div>
                </div>
			</div>
		</div>
        <div class="modal fade" id="industryModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            New Industry
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <form method="post" class="row g-3" id="frmIndustry">
                            <div class="col-12 form-group">
                                <label>Industy Name</label>
                                <input type="text" class="form-control" name="industryName" required/>
                            </div>
                            <div class="col-12 form-group">
                                <input type="submit" class="btn btn-primary" value="Add Entry" id="btnAdd"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            New Category
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <form method="post" class="row g-3" id="frmCategory">
                            <div class="col-12 form-group">
                                <label>Category Name</label>
                                <input type="text" class="form-control" name="categoryName" required/>
                            </div>
							<div class="col-12 form-group">
                                <label>Alias</label>
                                <input type="text" class="form-control" name="alias" required/>
                            </div>
                            <div class="col-12 form-group">
                                <label>Description</label>
                                <input type="text" class="form-control" name="description" required/>
                            </div>
                            <div class="col-12 form-group">
                                <input type="submit" class="btn btn-primary" value="Add Entry" id="btnAddCategory"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="warehouseModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            New Assignment
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <form method="post" class="row g-3" id="frmWarehouse">
                            <div class="col-12 form-group">
                                <label>Assignment</label>
                                <input type="text" class="form-control" name="warehouseName" required/>
                            </div>
                            <div class="col-12 form-group">
                                <label>Location</label>
                                <textarea class="form-control" name="address"></textarea>
                            </div>
                            <div class="col-12 form-group">
                                <input type="submit" class="btn btn-primary" value="Add Entry" id="btnAddWarehouse"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
		<div class="modal fade" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            New Account
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <form method="post" class="row g-3" id="frmAccount">
							<div class="col-12 form-group">
								<label>Complete Name</label>
								<input type="text" class="form-control" name="fullname" required/>
							</div>
							<div class="col-12 form-group">
								<label>Email Address</label>
								<input type="email" class="form-control" name="email" required/>
							</div>
							<div class="col-12 form-group">
								<label>Username</label>
								<input type="text" class="form-control" name="username" required/>
							</div>
							<div class="col-12 form-group">
								<label>Assignment</label>
								<select class="form-control" name="assignment" id="assignment">
									<option value="">Choose</option>
								</select>
							</div>
							<div class="col-12 form-group">
								<label>Department</label>
								<input type="text" class="form-control" name="department" required/>
							</div>
							<div class="col-12 form-group">
								<label>System Role</label>
								<select class="form-control" name="systemRole">
									<option value="">Choose</option>
									<option>Administrator</option>
									<option>Editor</option>
									<option>Standard User</option>
									<option>Staff</option>
								</select>
							</div>
							<div class="col-12 form-group">
								<input type="submit" class="btn btn-primary" id="btnAddAccount" value="Register"/>
							</div>
                        </form>
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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function(){listIndustry();listCategory();listWarehouse();assignment();notify();});
            function listCategory(){$.ajax({url:"<?=site_url('list-category')?>",method:"GET",success:function(response){$('#listcategory').html(response);}});}
            function listIndustry(){$.ajax({url:"<?=site_url('list-industry')?>",method:"GET",success:function(response){$('#listindustry').html(response);}});}
            function listWarehouse(){$.ajax({url:"<?=site_url('list-warehouse')?>",method:"GET",success:function(response){$('#listwarehouse').html(response);}});}
			function assignment(){$.ajax({url:"<?=site_url('assignment')?>",method:"GET",success:function(response){$('#assignment').append(response);}});}
			$('#btnAdd').on('click',function(e)
            {
                e.preventDefault();
                var data = $('#frmIndustry').serialize();
                $.ajax({
                    url:"<?=site_url('save-industry')?>",method:"POST",data:data,success:function(response)
                    {
                        if(response==="success"){
                            Swal.fire(
                                'Great',
                                'Successfully added',
                                'success'
                            );$('#frmIndustry')[0].reset();listIndustry();
                        }else{alert(response);}
                    }
                });
            });
            $('#btnAddCategory').on('click',function(e)
            {
                e.preventDefault();
                var data = $('#frmCategory').serialize();
                $.ajax({
                    url:"<?=site_url('save-category')?>",method:"POST",data:data,success:function(response)
                    {
                        if(response==="success"){
                            Swal.fire(
                                'Great',
                                'Successfully added',
                                'success'
                            );$('#frmCategory')[0].reset();listCategory();
                        }else{alert(response);}
                    }
                });
            });
            $('#btnAddWarehouse').on('click',function(e)
            {
                e.preventDefault();
                var data = $('#frmWarehouse').serialize();
                $.ajax({
                    url:"<?=site_url('save-warehouse')?>",method:"POST",data:data,success:function(response)
                    {
                        if(response==="success"){
                            Swal.fire(
                                'Great',
                                'Successfully added',
                                'success'
                            );$('#frmWarehouse')[0].reset();listWarehouse();assignment();
                        }else{alert(response);}
                    }
                });
            });
			$('#btnAddAccount').on('click',function(e)
            {
                e.preventDefault();
                var data = $('#frmAccount').serialize();
                $.ajax({
                    url:"<?=site_url('save-account')?>",method:"POST",data:data,success:function(response)
                    {
                        if(response==="success"){
                            Swal.fire(
                                'Great',
                                'Successfully added',
                                'success'
                            );$('#frmAccount')[0].reset();location.reload();
                        }else{alert(response);}
                    }
                });
            });
			$(document).on('click','.removeIndustry',function(e)
			{
				e.preventDefault();
				Swal.fire({
					title: "Are you sure?",
					text: "Do you want to remove this selected industry?",
					icon: "question",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes!"
					}).then((result) => {
					if (result.isConfirmed) {
						var val = $(this).val();
						$.ajax({
							url:"<?=site_url('remove-industry')?>",method:"POST",
							data:{value:val},success:function(response)
							{
								if(response==="success")
								{
									listIndustry();
								}
								else
								{
									alert(response);
								}
							}
						});
					}
				});
			});
			$(document).on('click','.removeLocation',function(e)
			{
				e.preventDefault();
				Swal.fire({
					title: "Are you sure?",
					text: "Do you want to remove this selected assignment?",
					icon: "question",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes!"
					}).then((result) => {
					if (result.isConfirmed) {
						var val = $(this).val();
						$.ajax({
							url:"<?=site_url('remove-location')?>",method:"POST",
							data:{value:val},success:function(response)
							{
								if(response==="success")
								{
									listWarehouse();
								}
								else
								{
									alert(response);
								}
							}
						});
					}
				});
			});
			$(document).on('click','.removeCategory',function(e)
			{
				e.preventDefault();
				Swal.fire({
					title: "Are you sure?",
					text: "Do you want to remove this selected category?",
					icon: "question",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes!"
					}).then((result) => {
					if (result.isConfirmed) {
						var val = $(this).val();
						$.ajax({
							url:"<?=site_url('remove-category')?>",method:"POST",
							data:{value:val},success:function(response)
							{
								if(response==="success")
								{
									listCategory();
								}
								else
								{
									alert(response);
								}
							}
						});
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

			$(document).on('click','.reset',function(e)
			{
				e.preventDefault();
				Swal.fire({
					title: "Are you sure?",
					text: "Do you want to reset the password?",
					icon: "question",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes!"
					}).then((result) => {
					if (result.isConfirmed) {
						var val = $(this).val();
						$.ajax({
							url:"<?=site_url('reset-account')?>",method:"POST",
							data:{value:val},success:function(response)
							{
								if(response==="success")
								{
									location.reload();
								}
								else
								{
									alert(response);
								}
							}
						});
					}
				});
			});
			
        </script>
	</body>
</html>
