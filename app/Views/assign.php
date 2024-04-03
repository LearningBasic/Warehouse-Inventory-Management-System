
<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Assignment</title>

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
								<?php if(session()->get('role')=="Staff"||session()->get('role')=="Administrator"){?>
								<li><a href="<?=site_url('assign')?>" class="active">Assigned PRF</a></li>
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
                <div class="card-box">
                    <div class="card-header">Assignment</div>
                    <div class="card-body">
						<?php if(!empty(session()->getFlashdata('success'))) : ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('success'); ?>
                            </div>
                        <?php endif; ?>
						<div class="tabs">
							<ul class="nav nav-pills justify-content-left" role="tablist">
								<li class="nav-item">
									<a
										class="nav-link active text-blue"
										data-toggle="tab"
										href="#home6"
										role="tab"
										aria-selected="true"
										>All PRF</a
									>
								</li>
								<li class="nav-item">
									<a
										class="nav-link text-blue"
										data-toggle="tab"
										href="#pending6"
										role="tab"
										aria-selected="true"
										>Pending</a
									>
								</li>
								<li class="nav-item">
									<a
										class="nav-link text-blue"
										data-toggle="tab"
										href="#process6"
										role="tab"
										aria-selected="true"
										>On Process</a
									>
								</li>
								<li class="nav-item">
									<a
										class="nav-link text-blue"
										data-toggle="tab"
										href="#completed6"
										role="tab"
										aria-selected="true"
										>Completed</a
									>
								</li>
								<li class="nav-item">
									<a
										class="nav-link text-blue"
										data-toggle="tab"
										href="#rejected6"
										role="tab"
										aria-selected="true"
										>Rejected</a
									>
								</li>
								<li class="nav-item">
									<a
										class="nav-link text-blue"
										data-toggle="tab"
										href="#archive6"
										role="tab"
										aria-selected="true"
										>Archive</a
									>
								</li>
								<li class="nav-item">
									<a
										class="nav-link text-blue"
										data-toggle="tab"
										href="#profile6"
										role="tab"
										aria-selected="false"
										>Quotation</a
									>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane fade show active" id="home6" role="tabpanel">
									<br/>
									<table class="data-table table stripe hover nowrap">
										<thead>
											<th>Date Received</th>
											<th>PRF No</th>
											<th>Assigned To</th>
											<th>Requestor</th>
											<th>Department</th>
											<th>Reason</th>
											<th>Date Needed</th>
											<th>Action Taken</th>
										</thead>
										<tbody>
											<?php foreach($list as $row): ?>
												<tr>
													<?php if($row->Remarks=="OPEN"){ ?>
													<td><?php echo $row->Date ?></td>
													<td><a class="btn btn-link" href="generate/<?php echo $row->OrderNo ?>" target="_blank"><?php echo $row->OrderNo ?></a></td>
													<td><?php echo $row->Staff ?></td>
													<td><?php echo $row->Fullname ?></td>
													<td><?php echo $row->Department ?></td>
													<td><?php echo $row->Reason ?></td>
													<td><?php echo $row->DateNeeded ?></td>
													<td>
														<?php if($row->Status==0){ ?>
															<span class="badge bg-warning text-white">WAITING</span>
														<?php }else if($row->Status==1){ ?>
															<span class="badge bg-success text-white">ACCEPTED</span>
														<?php }else{ ?>
															<span class="badge bg-danger text-white">DECLINED</span>
														<?php } ?>
													</td>
													<?php }else{ ?>
													<td><?php echo $row->Date ?></td>
													<td><?php echo $row->OrderNo ?></td>
													<td><?php echo $row->Staff ?></td>
													<td><?php echo $row->Fullname ?></td>
													<td><?php echo $row->Department ?></td>
													<td><?php echo $row->Reason ?></td>
													<td><?php echo $row->DateNeeded ?></td>
													<td>-</td>
													<?php } ?>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
								<div class="tab-pane fade" id="pending6" role="tabpanel">
									<br/>
									<table class="data-table table stripe hover nowrap">
										<thead>
											<th>Date Received</th>
											<th>PRF No</th>
											<th>Requestor</th>
											<th>Department</th>
											<th>Date Needed</th>
											<th>Action Taken</th>
											<th>Action</th>
										</thead>
										<tbody>
											<?php foreach($pending as $row): ?>
												<tr>
													<td><?php echo $row->Date ?></td>
													<td><a class="btn btn-link" href="generate/<?php echo $row->OrderNo ?>" target="_blank"><?php echo $row->OrderNo ?></a></td>
													<td><?php echo $row->Fullname ?></td>
													<td><?php echo $row->Department ?></td>
													<td><?php echo $row->DateNeeded ?></td>
													<td><span class="badge bg-warning text-white">WAITING</span></td>
													<td>
														<div class="dropdown">
															<a class="btn btn-primary btn-sm dropdown-toggle"
																href="#" role="button" data-toggle="dropdown">
																SELECT
															</a>
															<div class="dropdown-menu dropdown-menu-left dropdown-menu-icon-list">
																<button type="button" class="dropdown-item accept" value="<?php echo $row->assignID ?>"><span class="dw dw-check"></span>&nbsp;Accept</button>
																<button type="button" class="dropdown-item revision" value="<?php echo $row->OrderNo ?>"><span class="dw dw-repeat1"></span>&nbsp;Reject</button>
																<button type="button" class="dropdown-item view" value="<?php echo $row->OrderNo ?>"><span class="dw dw-list"></span>&nbsp;List of Items</button>
															</div>
														</div>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
								<div class="tab-pane fade" id="process6" role="tabpanel">
									<br/>
									<table class="data-table table stripe hover nowrap">
										<thead>
											<th>Date Received</th>
											<th>PRF No</th>
											<th>Requestor</th>
											<th>Department</th>
											<th>Date Needed</th>
											<th>Action</th>
										</thead>
										<tbody>
										<?php foreach($ongoing as $row): ?>
												<tr>
													<td><?php echo $row->Date ?></td>
													<td><a class="btn btn-link" href="generate/<?php echo $row->OrderNo ?>" target="_blank"><?php echo $row->OrderNo ?></a></td>
													<td><?php echo $row->Fullname ?></td>
													<td><?php echo $row->Department ?></td>
													<td><?php echo $row->DateNeeded ?></td>
													<td>
														<?php if($row->Status==1){ ?>
															<div class="dropdown">
																<a class="btn btn-primary btn-sm dropdown-toggle"
																	href="#" role="button" data-toggle="dropdown">
																	SELECT
																</a>
																<div class="dropdown-menu dropdown-menu-left dropdown-menu-icon-list">
																	<a href="create/<?php echo $row->OrderNo ?>" class="dropdown-item"><span class="dw dw-add"></span>&nbsp;Create</a>
																	<a href="edit-order/<?php echo $row->OrderNo ?>" class="dropdown-item" target="_blank"><span class="dw dw-edit-1"></span>&nbsp;Edit Items</a>
																	<button type="button" class="dropdown-item view" value="<?php echo $row->OrderNo ?>"><span class="dw dw-list"></span>&nbsp;List of Item</button>
																	<button type="button" class="dropdown-item archive" value="<?php echo $row->OrderNo ?>"><span class="dw dw-trash"></span>&nbsp;Close</button>
																</div>
															</div>
														<?php }else { ?>
															-
														<?php } ?>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
								<div class="tab-pane fade" id="completed6" role="tabpanel">
									<br/>
									<table class="data-table table stripe hover nowrap">
										<thead>
											<th>Date Received</th>
											<th>PRF No</th>
											<th>Requestor</th>
											<th>Department</th>
											<th>Date Needed</th>
											<th>Action Taken</th>
										</thead>
										<tbody>
											<?php foreach($complete as $row): ?>
												<tr>
													<td><?php echo $row->Date ?></td>
													<td><a class="btn btn-link" href="generate/<?php echo $row->OrderNo ?>" target="_blank"><?php echo $row->OrderNo ?></a></td>
													<td><?php echo $row->Fullname ?></td>
													<td><?php echo $row->Department ?></td>
													<td><?php echo $row->DateNeeded ?></td>
													<td><span class="badge bg-success text-white">Completed</span></td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
								<div class="tab-pane fade" id="rejected6" role="tabpanel">
									<br/>
									<table class="data-table table stripe hover nowrap">
										<thead>
											<th>Date Received</th>
											<th>PRF No</th>
											<th>Requestor</th>
											<th>Department</th>
											<th>Date Needed</th>
											<th>Action Taken</th>
										</thead>
										<tbody>
											<?php foreach($rejected as $row): ?>
												<tr>
													<td><?php echo $row->Date ?></td>
													<td><a class="btn btn-link" href="generate/<?php echo $row->OrderNo ?>" target="_blank"><?php echo $row->OrderNo ?></a></td>
													<td><?php echo $row->Fullname ?></td>
													<td><?php echo $row->Department ?></td>
													<td><?php echo $row->DateNeeded ?></td>
													<td><span class="badge bg-danger text-white">Cancelled</span></td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
								<div class="tab-pane fade" id="archive6" role="tabpanel">
									<br/>
									<table class="data-table table stripe hover nowrap">
										<thead>
											<th>Date Prepared</th>
											<th>PRF No</th>
											<th>Requestor</th>
											<th>Department</th>
											<th>Reason</th>
											<th>Date Needed</th>
										</thead>
										<tbody>
											<?php foreach($archive as $row): ?>
												<tr>
													<td><?php echo $row->DatePrepared ?></td>
													<td><?php echo $row->OrderNo ?></td>
													<td><?php echo $row->Fullname ?></td>
													<td><?php echo $row->Department ?></td>
													<td><?php echo substr($row->Reason,0,30) ?>...</td>
													<td><?php echo $row->DateNeeded ?></td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
								<div class="tab-pane fade" id="profile6" role="tabpanel">
									<br/>
									<table class="data-table table stripe hover nowrap">
										<thead>
											<th>Date Prepared</th>
											<th>Reference</th>
											<th>PRF No</th>
											<th>Department</th>
											<th>Date Needed</th>
											<th>Status</th>
											<th>Comment</th>
										</thead>
										<tbody>
											<?php foreach($canvass as $row):?>
												<tr>
													<td><?php echo $row['DatePrepared'] ?></td>
													<td>
													<?php if($row['Status']==4||$row['Status']==2){?>
														<a class="btn-link" href="export/<?php echo $row['Reference'] ?>" target="_blank"><?php echo $row['Reference'] ?></a>
													<?php }else{ ?>
														<?php echo $row['Reference'] ?>
													<?php } ?>
													</td>
													<td><?php echo $row['OrderNo'] ?></td>
													<td><?php echo $row['Department'] ?></td>
													<td><?php echo $row['DateNeeded'] ?></td>
													<td>
														<?php if($row['Status']==0){ ?>
															<span class="badge bg-warning text-white">PENDING</span>
														<?php }else if($row['Status']==2){?>
															<span class="badge bg-danger text-white">DECLINED</span>
														<?php }else if($row['Status']==4){?>
															<span class="badge bg-success text-white">APPROVED</span>
														<?php }else{ ?>
															<span class="badge bg-info text-white">REVIEWED</span>
														<?php } ?>
													</td>
													<td><?php echo $row['Comment'] ?></td>
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
        <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            View Order(s)
							<a href="javascript:void(0);" onclick="exportf(this)" class="btn btn-outline-primary btn-sm"><span class="dw dw-download"></span>&nbsp;Export</a>
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
			$(document).ready(function(){notify();});
            $(document).on('click','.accept',function(e)
            {
                e.preventDefault();
                Swal.fire({
					title: "Are you sure?",
					text: "Do you want to accept this request?",
					icon: "question",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes!"
					}).then((result) => {
					if (result.isConfirmed) {
						var val = $(this).val();
						$.ajax({
							url:"<?=site_url('accept-assignment')?>",method:"POST",
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
					else
					{
						$('.accept').attr("value","Accept");
					}
				});
            });
			$(document).on('click','.view',function()
			{
				var val = $(this).val();
				$.ajax({
					url:"<?=site_url('view-order')?>",method:"GET",
					data:{value:val},
					success:function(response)
					{
						$('#viewModal').modal('show');
						$('#result').html(response);
					}
				});
			});

			$(document).on('click','.revision',function(e)
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
						var message = prompt("Enter your comment to cancel");
						$.ajax({
							url:"<?=site_url('cancel-purchase')?>",method:"POST",
							data:{value:val,message:message},success:function(response)
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

			$(document).on('click','.archive',function(e)
			{
				e.preventDefault();
				Swal.fire({
					title: "Are you sure?",
					text: "Do you want to tag as close this selected PRF?",
					icon: "question",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes!"
					}).then((result) => {
					if (result.isConfirmed) {
						var val = $(this).val();
						$.ajax({
							url:"<?=site_url('close-purchase')?>",method:"POST",
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

			function exportf(elem) {
			var table = document.getElementById("result");
			var html = table.outerHTML;
			var url = 'data:application/vnd.ms-excel,' + escape(html); // Set your html table into url 
			elem.setAttribute("href", url);
			elem.setAttribute("download","order-list.xls"); // Choose the file name
			return false;
			}

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
