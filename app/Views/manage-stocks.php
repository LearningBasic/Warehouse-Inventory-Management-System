
<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Manage Stocks</title>

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
								<li><a href="<?=site_url('manage')?>" class="active">Manage Stocks</a></li>
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
                                <li><a href="<?=site_url('report-stocks')?>">Stocks Report</a></li>
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
				<div class="card-box">
					<div class="card-header"><i class="icon-copy dw dw-box"></i>&nbsp;Manage Stocks</div>
					<div class="card-body">
						<?php if(!empty(session()->getFlashdata('success'))) : ?>
							<div class="alert alert-success alert-dismissible fade show" role="alert">
								<?= session()->getFlashdata('success'); ?>
							</div>
						<?php endif; ?>
						<?php if(!empty(session()->getFlashdata('fail'))) : ?>
							<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<?= session()->getFlashdata('fail'); ?>
							</div>
						<?php endif; ?>
						<div class="tabs">
							<ul class="nav nav-pills justify-content-left" role="tablist">
								<!-- <li class="nav-item">
									<a
										class="nav-link active text-blue"
										data-toggle="tab"
										href="#home6"
										role="tab"
										aria-selected="true"
										>Damaged Items</a
									>
								</li>
								<li class="nav-item">
									<a
										class="nav-link text-blue"
										data-toggle="tab"
										href="#profile6"
										role="tab"
										aria-selected="false"
										>Overhaul Items</a
									>
								</li> -->
								<li class="nav-item">
									<a
										class="nav-link active text-blue"
										data-toggle="tab"
										href="#others6"
										role="tab"
										aria-selected="false"
										>Transfer Items</a
									>
								</li>
								<li class="nav-item">
									<a
										class="nav-link text-blue"
										data-toggle="tab"
										href="#addstock"
										role="tab"
										aria-selected="false"
										>Add Stocks</a
									>
								</li>
								<li class="nav-item">
									<a
										class="nav-link text-blue"
										data-toggle="tab"
										href="#contact6"
										role="tab"
										aria-selected="false"
										>Return Order(s)</a
									>
								</li>
							</ul>
							<div class="tab-content">
								<!-- <div class="tab-pane fade show active" id="home6" role="tabpanel">
									<br/>
									<table class="data-table table stripe hover nowrap">
										<thead>
											<th>Date Reported</th>
											<th>Defect Type</th>
											<th>Product Name</th>
											<th>Qty</th>
											<th>Details</th>
											<th>Remarks</th>
											<th>Action</th>
										</thead>
										<tbody>
											<?php if($items): ?>
												<?php foreach($items as $row): 
												if($row->Remarks=="Replacement"){
													?>
													<tr>
														<td><?php echo $row->DateReport ?></td>
														<td><?php echo $row->DamageRate ?></td>
														<td><?php echo $row->productName ?></td>
														<td><?php echo number_format($row->Qty,0) ?></td>
														<td><?php echo $row->Details ?></td>
														<td><span class="badge bg-primary text-white"><?php echo $row->Remarks ?></span></td>
														<td>
														<a href="/Damage_Files/<?php echo $row->Image ?>" target="_BLANK" class="btn btn-outline-primary btn-sm"><i class="icon-copy dw dw-image"></i>&nbsp;View</a>
														</td> 
													</tr>
												<?php }else{ 
													if($row->Status==0){
													?>
													<tr>
														<td><?php echo $row->DateReport ?></td>
														<td><?php echo $row->DamageRate ?></td>
														<td><?php echo $row->productName ?></td>
														<td><?php echo number_format($row->Qty,0) ?></td>
														<td><?php echo $row->Details ?></td>
														<td><span class="badge bg-primary text-white"><?php echo $row->Remarks ?></span></td>
														<td>
															<a href="/Damage_Files/<?php echo $row->Image ?>" target="_BLANK" class="btn btn-outline-primary btn-sm"><i class="icon-copy dw dw-image"></i>&nbsp;View</a>
															<a href="<?=site_url('create-report/')?><?php echo $row->damageID ?>" class="btn btn-outline-primary btn-sm"><i class="icon-copy dw dw-add"></i>&nbsp;Create</a>
														</td> 
													</tr>
													<?php }else{ ?>
														<tr>
														<td><?php echo $row->DateReport ?></td>
														<td><?php echo $row->DamageRate ?></td>
														<td><?php echo $row->productName ?></td>
														<td><?php echo number_format($row->Qty,0) ?></td>
														<td><?php echo $row->Details ?></td>
														<td><span class="badge bg-primary text-white"><?php echo $row->Remarks ?></span></td>
														<td>
															<a href="/Damage_Files/<?php echo $row->Image ?>" target="_BLANK" class="btn btn-outline-primary btn-sm"><i class="icon-copy dw dw-image"></i>&nbsp;View</a>
														</td> 
													</tr>
													<?php } ?>
												<?php } ?>
												<?php endforeach; ?>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
								<div class="tab-pane fade" id="profile6" role="tabpanel">
									<br/>
									<table class="data-table table stripe hover nowrap">
										<thead>
											<th>Date Repaired</th>
											<th>Product Name</th>
											<th>Qty</th>
											<th>Details</th>
											<th>Date Accomplished</th>
											<th>Status</th>
											<th>Action</th>
										</thead>
										<tbody>
											<?php if($archive): ?>
												<?php foreach($archive as $row): ?>
													<?php if($row->Status==0){ ?>
													<tr>
														<td><?php echo $row->repairDate ?></td>
														<td><?php echo $row->productName ?></td>
														<td><?php echo number_format($row->Qty,0) ?></td>
														<td><?php echo $row->Details ?></td>
														<td><?php echo $row->dateAccomplished ?></td>
														<td><span class="badge bg-warning text-white">PENDING</span></td>
														<td>
															<button type="button" class="btn btn-outline-primary btn-sm file" value="<?php echo $row->repairID ?>"><i class="icon-copy dw dw-add"></i>&nbsp;Create</button>
														</td>
													</tr>
													<?php }else if($row->Status==1){ ?>
													<tr>
														<td><?php echo $row->repairDate ?></td>
														<td><?php echo $row->productName ?></td>
														<td><?php echo number_format($row->Qty,0) ?></td>
														<td><?php echo $row->Details ?></td>
														<td><?php echo $row->dateAccomplished ?></td>
														<td><span class="badge bg-success text-white">DONE</span></td>
														<td>
															<button type="button" class="btn btn-outline-primary btn-sm view" value="<?php echo $row->repairID ?>"><i class="icon-copy dw dw-view"></i>&nbsp;Details</button>
														</td>
													</tr>
													<?php }else if($row->Status==2){  ?>
														<tr>
														<td><?php echo $row->repairDate ?></td>
														<td><?php echo $row->productName ?></td>
														<td><?php echo number_format($row->Qty,0) ?></td>
														<td><?php echo $row->Details ?></td>
														<td><?php echo $row->dateAccomplished ?></td>
														<td><span class="badge bg-danger text-white">UNDONE</span></td>
														<td>-</td>
													</tr>
													<?php } ?>
												<?php endforeach; ?>
											<?php endif; ?>
										</tbody>
									</table>
								</div> -->
								<div class="tab-pane fade show active" id="others6" role="tabpanel">
									<br/>
									<table class="data-table table stripe hover nowrap">
										<thead>
											<th>Date Prepared</th>
											<th>Item No</th>
											<th>Product Name</th>
											<th>Qty</th>
											<th>Effective Date</th>
											<th>Status</th>
											<th>Delivery via</th>
											<th>Track #/Driver</th>
											<th><span class="dw dw-more"></span></th>
										</thead>
										<tbody>
											<?php if($transfer): ?>
												<?php foreach($transfer as $row): ?>
													<?php if($row->Status==0){ ?>
													<tr>
														<td><?php echo $row->datePrepared ?></td>
														<td><?php echo $row->productID ?></td>
														<td><?php echo $row->productName ?></td>
														<td><?php echo number_format($row->Qty,0) ?></td>
														<td><?php echo $row->dateEffective ?></td>
														<td><span class="badge bg-warning text-white">For Delivery</span></td>
														<td><?php echo $row->cargo_type ?></td>
														<td><?php echo $row->TrackingNumber ?><?php echo $row->Driver ?></td>
														<td>
															<button type="button" class="btn btn-danger btn-sm cancel" value="<?php echo $row->transferID ?>">
															Cancel
															</button>
														</td>
													</tr>
													<?php }else if($row->Status==1){ ?>
													<tr>
														<td><?php echo $row->datePrepared ?></td>
														<td><?php echo $row->productID ?></td>
														<td><?php echo $row->productName ?></td>
														<td><?php echo number_format($row->Qty,0) ?></td>
														<td><?php echo $row->dateEffective ?></td>
														<td><span class="badge bg-success text-white">Delivered</span></td>
														<td><?php echo $row->cargo_type ?></td>
														<td><?php echo $row->TrackingNumber ?><?php echo $row->Driver ?></td>
														<td>-</td>
													</tr>
													<?php }else if($row->Status==2){ ?>
													<tr>
														<td><?php echo $row->datePrepared ?></td>
														<td><?php echo $row->productID ?></td>
														<td><?php echo $row->productName ?></td>
														<td><?php echo number_format($row->Qty,0) ?></td>
														<td><?php echo $row->dateEffective ?></td>
														<td><span class="badge bg-danger text-white">Cancelled</span></td>
														<td><?php echo $row->cargo_type ?></td>
														<td><?php echo $row->TrackingNumber ?><?php echo $row->Driver ?></td>
														<td></td>
													</tr>
													<?php } ?>
												<?php endforeach; ?>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
								<div class="tab-pane fade" id="addstock" role="tabpanel">
									<br/>
									<form method="post" class="row g-3" id="frmStock" action="<?=base_url('save-stocks')?>">
										<div class="col-12 form-group">
											<label>Product Name</label>
											<select class="form-control custom-select2" name="product" style="width:100%;" required>
												<option value="">Choose</option>
												<?php foreach($product as $row): ?>
													<option value="<?php echo $row->inventID ?>"><?php echo $row->productName ?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<div class="col-12 form-group">
											<div class="row g-3">
												<div class="col-lg-3">
													<label>Date Added</label>
													<input type="date" class="form-control" name="dateAdded" value="<?php echo date('Y-m-d') ?>" required/>
												</div>
												<div class="col-lg-3">
													<label>Number of Stocks</label>
													<input type="number" class="form-control" name="num_stocks" id="num_stocks" required/>
												</div>
												<div class="col-lg-3">
													<label>Unit Price</label>
													<input type="text" class="form-control" name="unitPrice" id="unitPrice" required/>
												</div>
												<div class="col-lg-3">
													<label>Total Price</label>
													<input type="text" class="form-control" name="totalPrice" id="totalPrice" required/>
												</div>
											</div>
										</div>
										<div class="col-12 form-group">
											<label>Details/Reason</label>
											<textarea class="form-control" name="details" required></textarea>
										</div>
										<div class="col-12 form-group">
											<input type="submit" class="btn btn-primary" id="btnSave" value="Save Entry"/>
										</div>
									</form>
								</div>
								<div class="tab-pane fade" id="contact6" role="tabpanel">

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="accomplishModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            Accomplishment Report Form
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
						<div class="alert alert-success alert-dismissible fade show" id="success" style="display:none;" role="alert">
							<label id="successMessage"></label>
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="alert alert-danger alert-dismissible fade show" id="error" style="display:none;" role="alert">
							<label id="errorMessage"></label>
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
                        <form method="post" class="row g-3" id="frmReport" enctype="multipart/form-data">
							<input type="hidden" id="itemID" name="itemID"/>
							<div class="col-12 form-group">
								<label>Accomplished Date</label>
								<input type="date" class="form-control" name="accomplish_date" required/>
							</div>
							<div class="col-12 form-group">
								<label>Repaired By:</label>
								<textarea class="form-control" name="involveWorkers" placeholder="Enter their complete name" required></textarea>
							</div>
							<div class="col-12 form-group">
								<label>Attachment/Proof</label>
								<input type="file" class="form-control" name="file" accept="image/png, image/gif, image/jpeg" required/>
							</div>
							<div class="col-12 form-group">
								<input type="submit" class="btn btn-primary" value="Send Report" id="btnSend"/>
							</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
		<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            Accomplishment Report
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div id="result"></div>
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
			$(document).ready(function()
			{
				notify();
			});
			$('#unitPrice').keyup(function()
			{
				var val = $(this).val();
				var qty = $('#num_stocks').val();
				var total = qty*val;
				$('#totalPrice').attr("value",total);
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
			$(document).on('click','.file',function(e){
				e.preventDefault();
				$('#itemID').attr("value",$(this).val());
				$('#accomplishModal').modal('show');
			});
			$(document).on('click','.view',function(e){
				e.preventDefault();
				$.ajax({url:"<?=site_url('view-report')?>",method:"GET",data:{value:$(this).val()},success:function(response)
				{
					$('#viewModal').modal('show');
					$('#result').html(response);
				}});
			});
			$('#frmReport').on('submit',function(e)
			{
				e.preventDefault();
				$.ajax({
					type: 'POST',
					url: '<?=site_url('send-report')?>',
					data: new FormData(this),
					contentType: false,
					cache: false,
					processData:false,
					beforeSend: function(){
						$('#btnSend').attr("disabled","disabled");
						$('#frmReport').css("opacity",".5");
					},
					success: function(response){
						if(response==="success"){
							$('#frmReport')[0].reset();
							document.getElementById('success').style="display:block";
							$('#successMessage').html("Great! Successfully reported. Please refresh the page");
						}else{
							document.getElementById('error').style="display:block";
							$('#errorMessage').html(response);
						}
						$('#frmReport').css("opacity","");
						$("#btnSend").removeAttr("disabled");
					}
				});
			});
			$(document).on('click','.cancel',function(e)
			{
				e.preventDefault();
				Swal.fire({
					title: "Are you sure?",
					text: "Do you want to cancel this selected request?",
					icon: "question",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes!"
					}).then((result) => {
					if (result.isConfirmed) {
						var val = $(this).val();
						$.ajax({
							url:"<?=site_url('cancel-transfer')?>",method:"POST",
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
