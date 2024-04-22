
<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title><?php if(session()->get('role')=="Staff"){ ?>Create Quotation<?php }else {?>Create Canvass Sheet<?php }?></title>

		<!-- Site favicon -->
		<link
			rel="apple-touch-icon"
			sizes="180x180"
			href="/assets/img/fastcat.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="32x32"
			href="/assets/img/fastcat.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="/assets/img/fastcat.png"
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
		<link rel="stylesheet" type="text/css" href="/assets/vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="/assets/vendors/styles/icon-font.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="/assets/src/plugins/datatables/css/dataTables.bootstrap4.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="/assets/src/plugins/datatables/css/responsive.bootstrap4.min.css"
		/>
		<link rel="stylesheet" type="text/css" href="/assets/vendors/styles/style.css" />
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
					<img src="/assets/img/fastcat.png" alt="Fastcat" width="100"/>
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
					<img src="/assets/img/fastcat.png" alt="" class="dark-logo" width="100"/>
					<img
						src="/assets/img/fastcat.png"
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
								<?php if(session()->get('role')=="Administrator"||session()->get('role')=="Planner"){ ?>
								<li><a href="<?=site_url('manage')?>">Manage Stocks</a></li>
                                <?php } ?>
							</ul>
						</li>
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
                            <i class="micon dw dw-shopping-cart"></i><span class="mtext">Purchasing</span>
							<?php if(session()->get('role')=="Administrator"||session()->get('role')=="Editor"){ ?>
							&nbsp;<span class="badge badge-pill bg-primary text-white" id="notification">0</span>
							<?php } ?>
							</a>
							<ul class="submenu">
                                <li><a href="<?=site_url('orders')?>">Order Materials</a></li>
								<li><a href="<?=site_url('list-orders')?>">List Order</a></li>
                                <li><a href="javascript:void(0);" class="active"><?php if(session()->get('role')=="Staff"){ ?>Create Quotation<?php }else {?>Create Canvass Sheet<?php }?></a></li>
								<?php if(session()->get('role')=="Administrator"||session()->get('role')=="Editor"){ ?>
								<li><a href="<?=site_url('approve-orders')?>">For Approval&nbsp;<span class="badge badge-pill bg-primary text-white" id="notifications">0</span></a></li>
								<li><a href="<?=site_url('canvass-sheet-request')?>">Canvass Sheet&nbsp;<span class="badge badge-pill bg-primary text-white" id="notif">0</span></a></li>
								<?php } ?>
								<?php if(session()->get('role')=="Staff"||session()->get('role')=="Administrator"){?>
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
						<?php if(session()->get('role')=="Administrator"||session()->get('role')=="Staff"){ ?>
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
                                <li><a href="<?=site_url('report-stocks')?>">Stocks Report</a></li>
								<li><a href="<?=site_url('ledger')?>">Vendor's Ledger</a></li>
								<li><a href="<?=site_url('return-order-summary')?>">Return Order Report</a></li>
								<li><a href="<?=site_url('issuance')?>">Issuance Report</a></li>
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
				<?php if(!empty(session()->getFlashdata('fail'))) : ?>
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<?= session()->getFlashdata('fail'); ?>
					</div>
				<?php endif; ?>
                <div class="card-box">
                    <div class="card-header"><?php if(session()->get('role')=="Staff"||session()->get('role')=="Administrator"){ ?>Create Quotation<?php }else {?>Create Canvass Sheet<?php }?>
					<?php if(session()->get('role')=="Staff"||session()->get('role')=="Administrator"){ ?>
						<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#addRegularModal" style="float:right;"><i class="icon-copy dw dw-add"></i>&nbsp;Add</a>
					<?php }else {?>	
						<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#addModal" style="float:right;"><i class="icon-copy dw dw-add"></i>&nbsp;Add</a>
					<?php }?>
					</div>
                    <div class="card-body">
                        <?php foreach($prf as $row): ?>
                        <form method="post" class="row g-3" enctype="multipart/form-data" action="<?=base_url('save-form')?>" id="frmCanvass">
							<input type="hidden" name="requestor" value="<?php echo $row->accountID ?>"/>
							<input type="hidden" name="type_purchase" value="<?php echo $row->PurchaseType ?>"/>
                            <div class="col-12 form-group">
                                <div class="row g-3">
                                    <div class="col-lg-3">
                                        <label>Date Prepared</label>
                                        <input type="date" class="form-control" name="datePrepared" value="<?php echo date('Y-m-d') ?>"/>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Date Needed</label>
                                        <input type="date" class="form-control" name="dateNeeded" value="<?php echo $row->DateNeeded ?>"/>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>PRF #</label>
                                        <input type="text" class="form-control" name="OrderNo" id="OrderNo" value="<?php echo $row->OrderNo ?>"/>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Department</label>
                                        <input type="text" class="form-control" name="department" value="<?php echo $row->Department ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <table class="table table-bordered table-striped hover nowrap">
                                    <thead>
                                        <th class="bg-primary text-white">Qty</th>
                                        <th class="bg-primary text-white">UOM</th>
                                        <th class="bg-primary text-white">Item Description</th>
                                        <th class="bg-primary text-white">Unit Price</th>
                                        <th class="bg-primary text-white">Supplier</th>
                                        <th class="bg-primary text-white">Contact Person</th>
                                        <th class="bg-primary text-white">Contact #</th>
                                        <th class="bg-primary text-white">Terms</th>
                                        <th class="bg-primary text-white">Warranty</th>
										<th class="bg-primary text-white"><span class="dw dw-more"></span></th>
                                    </thead>
                                    <tbody id="tbl_supplier">

                                    </tbody>
                                </table>
                            </div>
							<div class="col-12 form-group">
								<label>Attachment</label> 
                                <input type="file" class="form-control" name="file" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" required/>
							</div>
							<div class="col-12 form-group">
								<label>Material Department Head</label>
								<select class="form-control custom-select2" name="approver" id="approver" required>
									<option value="">Choose</option>
									<?php foreach($approver as $row): ?>
										<option value="<?php echo $row->accountID ?>"><?php echo $row->Fullname ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-12 form-group">
								<button type="submit" class="btn btn-primary" id="btnSubmit">Submit Form</button>
							</div>
						</form>
                        <?php endforeach; ?>
                    </div>
                </div>
			</div>
		</div>
		<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
							Add Vendor/Supplier
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" class="row g-3" id="frmAdd">
							<input type="hidden" name="orderNo" value="<?=$id ?>"/>
							<div class="col-12">
								<label>VAT (Value Added Tax)</label>
								<select class="form-control" name="vatable">
									<option value="">Choose</option>
									<option>VAT INC</option>
									<option>VAT EX</option>
									<option>Non-Vatable</option>
								</select>
							</div>
							<div class="col-12 form-group">
								<label>Item/Equipment</label>
								<select class="form-control" name="item">
									<option value="">Choose</option>
									<?php foreach($item as $row): ?>
										<option value="<?php echo $row->orderID ?>"><?php echo $row->Item_Name ?> - <?php echo $row->Specification ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-12 form-group">
								<label>Vendors/Supplier's Name</label>
								<input type="search" class="form-control" id="supplier" name="supplier" required/>
								<div id="listOfName"></div>
							</div>
							<div class="col-12 form-group">
								<div class="row g-3">
									<div class="col-lg-6">
										<label>Contact Person</label>
										<input type="text" class="form-control" name="contactPerson" id="person" required/>
									</div>
									<div class="col-lg-3">
										<label>Unit Price</label>
										<input type="text" class="form-control" name="unitPrice" required/>
									</div>
									<div class="col-lg-3">
										<label>Contact Number</label>
										<input type="phone" class="form-control" maxlength="11" minlength="11" name="phone" id="phone" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" required/>
									</div>
								</div>
							</div>
							<div class="col-12 form-group">
								<label>Address</label>
								<textarea class="form-control" style="height:120px;" name="address" id="address" required></textarea>
							</div>
							<div class="col-12 form-group">
								<div class="row g-3">
									<div class="col-lg-6">
										<label>Terms</label>
										<input type="text" class="form-control" name="terms" required/>
									</div>
									<div class="col-lg-6">
										<label>Warranty</label>
										<input type="text" class="form-control" name="warranty" required/>
									</div>
								</div>
							</div>
							<div class="col-12 form-group">
								<input type="submit" class="btn btn-primary text-white" value="Add Entry" id="btnAdd"/>
							</div>
						</form>
                    </div>
                </div>
            </div>
        </div>

		<div class="modal fade" id="addRegularModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
							Add Vendor/Supplier
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" class="row g-3" id="frmEntries">
							<input type="hidden" name="orderNo" value="<?=$id ?>"/>
							<div class="col-12 form-group">
								<div class="row g-3">
									<div class="col-lg-9">
										<label>VAT (Value Added Tax)</label>
										<select class="form-control" name="vatable">
											<option value="">Choose</option>
											<option>VAT INC</option>
											<option>VAT EX</option>
											<option>Non-Vatable</option>
										</select>
									</div>
									<div class="col-lg-3">
										<label>Currency</label>
										<select class="form-control" name="currency">
											<option>PHP</option>
											<option>USD</option>
											<option>EUR</option>
											<option>YEN</option>
											<option>AUD</option>
											<option>GBP</option>
											<option>SGD</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-12 form-group">
								<label>Vendors/Supplier's Name</label>
								<input type="search" class="form-control" id="suppliers" name="supplier" required/>
								<div id="listOfNames"></div>
							</div>
							<div class="col-12 form-group">
								<div class="row g-3">
									<div class="col-lg-6">
										<label>Contact Person</label>
										<input type="text" class="form-control" name="contactPerson" id="persons" required/>
									</div>
									<div class="col-lg-6">
										<label>Contact Number</label>
										<input type="phone" class="form-control" maxlength="11" minlength="11" name="phone" id="phones" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" required/>
									</div>
								</div>
							</div>
							<div class="col-12 form-group">
								<label>Address</label>
								<textarea class="form-control" style="height:120px;" name="address" id="addresses" required></textarea>
							</div>
							<div class="col-12 form-group">
								<div class="row g-3">
									<div class="col-lg-6">
										<label>Terms</label>
										<input type="text" class="form-control" name="terms" required/>
									</div>
									<div class="col-lg-6">
										<label>Warranty</label>
										<input type="text" class="form-control" name="warranty" required/>
									</div>
								</div>
							</div>
							<div class="col-12 form-group">
								<label>Ordered Items/Materials</label>
								<div class="tableFixHead" style="height:300px;overflow-y:auto;">
									<table class="table-bordered">
										<thead>
											<th>#</th>
											<th>Item Name</th>
											<th>Specification</th>
											<th>Unit Price</th>
										</thead>
										<tbody id="tbl_entries">
										
										</tbody>
									</table>
								</div>
							</div>
							<div class="col-12 form-group">
								<input type="submit" class="btn btn-primary text-white" value="Save Entry" id="btnSaveEntry"/>
							</div>
						</form>
                    </div>
                </div>
            </div>
        </div>
		<!-- js -->
		<script src="/assets/vendors/scripts/core.js"></script>
		<script src="/assets/vendors/scripts/script.min.js"></script>
		<script src="/assets/vendors/scripts/process.js"></script>
		<script src="/assets/vendors/scripts/layout-settings.js"></script>
		<script src="/assets/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script src="/assets/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
		<script src="/assets/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
		<script src="/assets/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
		<script src="/assets/vendors/scripts/datatable-setting.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<script>
			$(document).ready(function()
			{
				loadSuppliers();entries();
			});
			function entries()
			{
				var val = $('#OrderNo').val();
				$.ajax({
					url:"<?=site_url('load-entries')?>",method:"GET",
					data:{value:val},
					success:function(response)
					{
						$('#tbl_entries').html(response);
					}
				});
			}
			function loadSuppliers()
			{
				var val = $('#OrderNo').val();
				$.ajax({
					url:"<?=site_url('fetch-added-supplier')?>",method:"GET",
					data:{value:val},
					success:function(response)
					{
						if(response==="")
						{
							$('#tbl_supplier').html("<tr><td colspan='10'><center>No Data</center></td></tr>");
							$('#btnSubmit').attr("disabled",true);
						}
						else
						{
							$('#tbl_supplier').html(response);
							$('#btnSubmit').attr("disabled",false);
						}
					}
				});
			}

			$('#btnSaveEntry').on('click',function(e)
			{
				e.preventDefault();
				$(this).attr("value","Saving! Please wait");
				var data = $('#frmEntries').serialize();
				$.ajax({
					url:"<?=site_url('save-entries')?>",method:"POST",
					data:data,
					success:function(response)
					{
						//console.log(response);
						if(response==="success")
						{
							loadSuppliers();entries();
							$('#addRegularModal').modal('hide');
							$('#frmEntries')[0].reset();
						}
						else
						{
							Swal.fire({
								title: "Error",
								text: response,
								icon: "error"
								});
						}
						$('#btnSaveEntry').attr("value","Save Entry");
					}
				});
			});

			$('#btnAdd').on('click',function(e)
			{
				e.preventDefault();
				$(this).attr("value","Adding! Please wait");
				var data = $('#frmAdd').serialize();
				$.ajax({
					url:"<?=site_url('add-entry')?>",method:"POST",
					data:data,
					success:function(response)
					{
						if(response==="success")
						{
							loadSuppliers();$('#addModal').modal('hide');
							$('#frmAdd')[0].reset();
						}
						else
						{
							Swal.fire({
								title: "Error",
								text: response,
								icon: "error"
								});
						}
						$('#btnAdd').attr("value","Add Entry");
					}
				});
			});
			$(document).on('click','.delete',function()
			{
				Swal.fire({
					title: "Are you sure?",
					text: "Do you want to remove this selected request?",
					icon: "question",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes!"
					}).then((result) => {
					if (result.isConfirmed) {
						var val = $(this).val();
						$.ajax({
							url:"<?=site_url('remove-item')?>?",method:"POST",
							data:{value:val},
							success:function(response)
							{
								if(response==="success")
								{
									loadSuppliers();entries();
								}
								else
								{
									Swal.fire({
										title: "Error",
										text: response,
										icon: "error"
									});
								}
							}
						});
					}
				});
			});
			$('#supplier').keyup(function()
			{
				var val = $(this).val();
				if(val !=='')
				{
					$.ajax({
						url:"<?=site_url('search-vendor')?>",type:"GET",
						data:{keyword:val},
						success:function(data)
						{
							$('#listOfName').fadeIn();
							$('#listOfName').html(data);
						}
					});
				}
			});
			$(document).on('click','.li',function()
			{
				$('#supplier').val($(this).text());
				$('#listOfName').fadeOut();
				var val = $('#supplier').val();
				$.ajax({
						url:"<?=site_url('vendor-information')?>",type:"GET",
						data:{value:val},dataType:"json",
						success:function(data)
						{
							$('#address').val(data["Address"]);
							$('#phone').val(data["contactNumber"]);
							$('#person').val(data["contactPerson"]);
						}
					});
			});

			$('#suppliers').keyup(function()
			{
				var val = $(this).val();
				if(val !=='')
				{
					$.ajax({
						url:"<?=site_url('search-vendor')?>",type:"GET",
						data:{keyword:val},
						success:function(data)
						{
							$('#listOfNames').fadeIn();
							$('#listOfNames').html(data);
						}
					});
				}
			});
			$(document).on('click','.li',function()
			{
				$('#suppliers').val($(this).text());
				$('#listOfNames').fadeOut();
				var val = $('#suppliers').val();
				$.ajax({
						url:"<?=site_url('vendor-information')?>",type:"GET",
						data:{value:val},dataType:"json",
						success:function(data)
						{
							$('#addresses').val(data["Address"]);
							$('#phones').val(data["contactNumber"]);
							$('#persons').val(data["contactPerson"]);
						}
					});
			});
		</script>
	</body>
</html>
