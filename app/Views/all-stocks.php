
<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Inventory</title>

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
								<li><a href="<?=site_url('stocks')?>" class="active">All Stocks</a></li>
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
								<li><a href="<?=site_url('receive-report')?>">Receive Order Report</a></li>
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
			<div class="xs-pd-20-10 pd-ltr-10">
				<div class="card-box">
					<div class="card-header"><span class="icon-copy dw dw-server"></span>&nbsp;Inventory</div>
					<div class="card-body">
						<?php if(!empty(session()->getFlashdata('fail'))) : ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('fail'); ?>
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
                            </div>
                        <?php endif; ?>
                        <?php if(!empty(session()->getFlashdata('success'))) : ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('success'); ?>
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
                            </div>
                        <?php endif; ?>
						<table class="data-table table stripe hover nowrap">
							<thead>
								<th>Product Name</th>
								<th>Item Group</th>
								<th>Product ID</th>
								<th>Details</th>
								<th>Unit Price</th>
								<th>Qty</th>
								<th>Status</th>
								<th>Expiration</th>
								<th>Assignment</th>
								<th>Action</th>
							</thead>
							<tbody>
								<?php if(session()->get('role')=="Administrator"){ ?>
									<?php foreach($items as $row): 
										if($row->Qty>$row->ReOrder){?>
										<?php $imgURL = "Products/".$row->Image; ?>
										<tr>
											<td>
												<button type="button" class="btn avatar mr-2 flex-shrink-0" value="<?php echo $row->inventID ?>">
													<img src="<?php echo $imgURL ?>" class="border-radius-100 shadow" width="40" height="40"/>
												</button>&nbsp;<?php echo $row->productName ?>
											</td>
											<td><?php echo $row->categoryName ?></td>
											<td><?php echo $row->productID ?></td>
											<td><?php echo substr($row->Description,0,20) ?>...</td>
											<td><?php echo number_format($row->unitPrice,2) ?></td>
											<td><?php echo number_format($row->Qty,0) ?></td>
											<td><span class='badge bg-success text-white'>Available</span></td>
											<td><?php echo $row->ExpirationDate ?></td>
											<td style="word-wrap: break-word;"><?php echo $row->warehouseName ?></td>
											<td>
												<div class="dropdown">
													<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
														href="#" role="button" data-toggle="dropdown">
														<i class="dw dw-more"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-left dropdown-menu-icon-list">
														<a class="dropdown-item" href="edit/<?php echo $row->inventID ?>"><i class="icon-copy dw dw-edit"></i>Edit</a>
														<!--<button type="button" class="dropdown-item deadstock" value="<?php echo $row->inventID ?>"><i class="icon-copy dw dw-file-19"></i>Damage</button>-->
														<a class="dropdown-item" href="transfer/<?php echo $row->inventID ?>"><i class="icon-copy dw dw-message-1"></i>Transfer</a>
														<a class="dropdown-item" href="generate-qrcode/<?php echo $row->inventID ?>"><i class="icon-copy bi bi-qr-code"></i>&nbsp;Generate</a>
													</div>
												</div>
											</td>
										</tr>
										<?php }else if($row->Qty<=$row->ReOrder){?>
										<?php $imgURL = "Products/".$row->Image; ?>
										<tr>
											<td>
												<button type="button" class="btn avatar mr-2 flex-shrink-0" value="<?php echo $row->inventID ?>">
													<img src="<?php echo $imgURL ?>" class="border-radius-100 shadow" width="40" height="40"/>
												</button>&nbsp;<?php echo $row->productName ?>
											</td>
											<td><?php echo $row->categoryName ?></td>
											<td><?php echo $row->productID ?></td>
											<td><?php echo substr($row->Description,0,20) ?>...</td>
											<td><?php echo number_format($row->unitPrice,2) ?></td>
											<td><?php echo number_format($row->Qty,0) ?></td>
											<td>
												<?php if($row->Qty==0){ ?>
													<span class='badge bg-danger text-white'>Out-of-Stock</span>
												<?php }else {?>
													<span class='badge bg-warning text-white'>Critical</span>
												<?php } ?>
											</td>
											<td><?php echo $row->ExpirationDate ?></td>
											<td style="word-wrap: break-word;"><?php echo $row->warehouseName ?></td>
											<td>
												<div class="dropdown">
													<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
														href="#" role="button" data-toggle="dropdown">
														<i class="dw dw-more"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-left dropdown-menu-icon-list">
														<a class="dropdown-item" href="edit/<?php echo $row->inventID ?>"><i class="icon-copy dw dw-edit"></i>Edit</a>
														<!--<button type="button" class="dropdown-item deadstock" value="<?php echo $row->inventID ?>"><i class="icon-copy dw dw-file-19"></i>Damage</button>-->
														<a class="dropdown-item" href="transfer/<?php echo $row->inventID ?>"><i class="icon-copy dw dw-message-1"></i>Transfer</a>
														<a class="dropdown-item" href="generate-qrcode/<?php echo $row->inventID ?>"><i class="icon-copy bi bi-qr-code"></i>&nbsp;Generate</a>
													</div>
												</div>
											</td>
										</tr>
										<?php } ?>
									<?php endforeach; ?>
								<?php }else{ ?>
									<?php foreach($items as $row): 
										if($row->Qty>$row->ReOrder){?>
										<?php $imgURL = "Products/".$row->Image; ?>
										<tr>
											<td>
												<button type="button" class="btn avatar mr-2 flex-shrink-0" value="<?php echo $row->inventID ?>">
													<img src="<?php echo $imgURL ?>" class="border-radius-100 shadow" width="40" height="40"/>
												</button>&nbsp;<?php echo $row->productName ?>
											</td>
											<td><?php echo $row->categoryName ?></td>
											<td><?php echo $row->productID ?></td>
											<td><?php echo substr($row->Description,0,20) ?>...</td>
											<td><?php echo number_format($row->unitPrice,2) ?></td>
											<td><?php echo number_format($row->Qty,0) ?></td>
											<td><span class='badge bg-success text-white'>Available</span></td>
											<td><?php echo $row->ExpirationDate ?></td>
											<td style="word-wrap: break-word;"><?php echo $row->warehouseName ?></td>
											<td>-</td>
										</tr>
										<?php }else if($row->Qty<=$row->ReOrder){?>
											<?php $imgURL = "Products/".$row->Image; ?>
										<tr>
											<td>
												<button type="button" class="btn avatar mr-2 flex-shrink-0" value="<?php echo $row->inventID ?>">
													<img src="<?php echo $imgURL ?>" class="border-radius-100 shadow" width="40" height="40"/>
												</button>&nbsp;<?php echo $row->productName ?>
											</td>
											<td><?php echo $row->categoryName ?></td>
											<td><?php echo $row->productID ?></td>
											<td><?php echo substr($row->Description,0,20) ?>...</td>
											<td><?php echo number_format($row->unitPrice,2) ?></td>
											<td><?php echo number_format($row->Qty,0) ?></td>
											<td>
												<?php if($row->Qty==0){ ?>
													<span class='badge bg-danger text-white'>Out-of-Stock</span>
												<?php }else {?>
													<span class='badge bg-warning text-white'>Critical</span>
												<?php } ?>
											</td>
											<td><?php echo $row->ExpirationDate ?></td>
											<td style="word-wrap: break-word;"><?php echo $row->warehouseName ?></td>
											<td>-</td>
										</tr>
										<?php } ?>
									<?php endforeach; ?>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="damageModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            Damage Report Form
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
						<div class="alert alert-success alert-dismissible fade show" id="success" style="display:none;" role="alert">
							<b>Great!</b>&nbsp;Successfully reported. Please refresh the page
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
							<input type="hidden" name="itemID" id="itemID"/>
                            <div class="col-12 form-group">
                                <label>Product Name</label>
                                <input type="text" class="form-control" name="productName" id="productName" required/>
                            </div>
							<div class="col-12 form-group">
								<label>Type of Defect/Problem</label><br/>
								<input type="radio" name="defectType" style="width:20px;height:15px;" value="Minor" required/>&nbsp;<label>Minor</label>
								<input type="radio" name="defectType" style="width:20px;height:15px;" value="Major"/>&nbsp;<label>Major</label>
							</div>
							<div class="col-12 form-group">
								<div class="row g-3">
									<div class="col-lg-6">
										<label>Date</label>
										<input type="date" class="form-control" name="dateReport" id="dateReport" required/>
									</div>
									<div class="col-lg-6">
										<label>Qty</label>
										<input type="number" class="form-control" name="qty" id="qty" required/>
									</div>
								</div>
							</div>
							<div class="col-12 form-group">
								<label>Details</label>
								<textarea class="form-control" name="details" required></textarea>
							</div>
							<div class="col-12 form-group">
								<label>Proof/Attachment</label>
								<input type="file" class="form-control" name="file" accept="image/png, image/gif, image/jpeg" required/>
							</div>
							<div class="col-12 form-group">
								<label>Recommendation</label>
								<select class="form-control" name="recommendation" required>
									<option value="">Choose</option>
									<option>For Repair</option>
									<option>Replacement</option>
								</select>
							</div>
                            <div class="col-12 form-group">
                                <input type="submit" class="btn btn-primary" value="Submit Report" id="btnAdd"/>
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
                            View Image
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
		<script>
			$(document).ready(function()
			{
				notify();
			});
			$(document).on('click','.avatar',function(e){
				e.preventDefault();
				var val = $(this).val();
				$.ajax({
					url:"<?=site_url('view-images')?>",method:"GET",
					data:{value:val},
					success:function(response)
					{
						$('#viewModal').modal('show');
						$('#result').html(response);
					}
				});
			});
			$(document).on('click','.deadstock',function(e){
				e.preventDefault();
				var confirmation = confirm("Would you like to tag as damaged this selected item?");
				if(confirmation)
				{
					var val = $(this).val();
					$.ajax({
						url:"<?=site_url('product-information')?>",method:"GET",data:{value:val},success:function(response)
						{
							$('#damageModal').modal('show');
							$('#itemID').attr("value",val);
							$('#productName').attr("value",response);
						}
					});
				}
			});
			$('#frmReport').on('submit',function(e)
			{
				e.preventDefault();
				$.ajax({
					type: 'POST',
					url: '<?=site_url('save-report')?>',
					data: new FormData(this),
					contentType: false,
					cache: false,
					processData:false,
					beforeSend: function(){
						$('#btnAdd').attr("disabled","disabled");
						$('#frmReport').css("opacity",".5");
					},
					success: function(response){
						if(response==="success"){
							$('#frmReport')[0].reset();
							document.getElementById('success').style="display:block";
						}else{
							document.getElementById('error').style="display:block";
							$('#errorMessage').html(response);
						}
						$('#frmReport').css("opacity","");
						$("#btnAdd").removeAttr("disabled");
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
		</script>
	</body>
</html>
