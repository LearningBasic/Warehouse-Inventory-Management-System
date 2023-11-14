
<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>FastCat WMS - Request</title>

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
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 
		<script type="text/javascript">
			google.charts.load('visualization', "1", {
				packages: ['corechart']
			});
		</script>
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
								<?php if(session()->get('role')=="Administrator"){ ?>
								<li><a href="<?=site_url('manage')?>">Manage Stocks</a></li>
                                <?php } ?>
							</ul>
						</li>
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
                            <i class="micon dw dw-shopping-cart"></i><span class="mtext">Purchasing</span>
							</a>
							<ul class="submenu">
                                <li><a href="<?=site_url('orders')?>">Order Materials</a></li>
                                <li><a href="<?=site_url('payment')?>">For Payment</a></li>
								<li><a href="<?=site_url('list-orders')?>">List Order</a></li>
								<?php if(session()->get('role')=="Administrator"){ ?>
								<li><a href="<?=site_url('approve-orders')?>">Orders</a></li>
								<?php } ?>
							</ul>
						</li>
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
                                <i class="micon dw dw-clipboard1"></i><span class="mtext">Receiving</span>
							</a>
							<ul class="submenu">
                                <li><a href="<?=site_url('receiving-item')?>">Receiving Item</a></li>
                                <li><a href="<?=site_url('storage')?>">Item Storage</a></li>
                                <li><a href="<?=site_url('packaging')?>">Packaging</a></li>
                                <li><a href="<?=site_url('shipping')?>">Shipping Items</a></li>
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
						<li class="dropdown">
							<a href="<?=site_url('request')?>" class="dropdown-toggle active no-arrow">
								<span class="micon bi bi-clipboard-data"></span
								><span class="mtext">Request</span>
							</a>
						</li>
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
                                <i class="micon dw dw-bar-chart-1"></i><span class="mtext">Reports</span>
							</a>
							<?php if(session()->get('role')=="Administrator"||session()->get('role')=="Editor"){ ?>
							<ul class="submenu">
                                <li><a href="<?=site_url('report-stocks')?>">Stocks Report</a></li>
								<li><a href="<?=site_url('report-purchase')?>">Purchasing Report</a></li>
								<li><a href="<?=site_url('report-receive')?>">Receiving Report</a></li>
								<li><a href="<?=site_url('report-suppliers')?>">Suppliers Report</a></li>
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
                <div class="row g-3">
                    <div class="col-lg-3 form-group">
                        <div class="card-box">
                            <div class="card-body">
                                <label><b>Damage Item(s)</b></label>
                                <h1 id="totalPendingDamageItem">0</h1>
                                <a href="javascript:void(0);" id="viewDamageItems" class="btn btn-outline-primary btn-sm">View</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 form-group">
                        <div class="card-box">
                            <div class="card-body">
                                <label><b>Overhaul Item(s)</b></label>
                                <h1 id="totalPendingOverhaulItem">0</h1>
                                <a href="javascript:void(0);" id="viewOverhaulItems" class="btn btn-outline-primary btn-sm">View</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 form-group">
                        <div class="card-box">
                            <div class="card-body">
                                <label><b>Transfer Item(s)</b></label>
                                <h1 id="totalPendingTransferItem">0</h1>
                                <a href="javascript:void(0);" id="viewTransferItems" class="btn btn-outline-primary btn-sm">View</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 form-group">
                        <div class="card-box">
                            <div class="card-body">
                                <label><b>Return Order(s)</b></label>
                                <h1>0</h1>
                                <a href="javascript:void(0);" id="viewReturnItems" class="btn btn-outline-primary btn-sm">View</a>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="card-box" id="damageReport" style="display:none;">
                    <div class="card-header">Damage Report</div>
                    <div class="card-body">
                        <table class="data-table table stripe hover nowrap">
                            <thead>
                                <th>Date</th>
                                <th>Defect</th>
                                <th>Product Name</th>
                                <th>Qty</th>
                                <th>Details</th>
                                <th>Remarks</th>
                                <th>Status</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                            <?php foreach($damage as $row): ?>
                                <tr>
                                    <td><?php echo $row->DateReport ?></td>
                                    <td><?php echo $row->DamageRate ?></td>
                                    <td><?php echo $row->productName ?></td>
                                    <td><?php echo number_format($row->Qty,0) ?></td>
                                    <td><?php echo $row->Details ?></td>
                                    <td><?php echo $row->Remarks ?></td>
                                    <td>
                                        <?php if($row->Status==0){ ?>
                                            <span class="badge bg-warning text-white">PENDING</span>
                                        <?php }else{ ?>
                                            <span class="badge bg-primary text-white">ACCEPTED</span>
                                        <?php } ?>
                                    </td>
                                    <td>
									<?php if($row->Status==0){ ?>
										<button type="button" class="btn btn-outline-primary btn-sm accept" value="<?php echo $row->reportID ?>"><i class="icon-copy dw dw-checked"></i>&nbsp;Accept</button>
										<a href="/Damage_Files/<?php echo $row->Image ?>" target="_BLANK" class="btn btn-outline-primary btn-sm"><i class="icon-copy dw dw-image"></i>&nbsp;View</a>
										<?php }else{ ?>
											<a href="/Damage_Files/<?php echo $row->Image ?>" target="_BLANK" class="btn btn-outline-primary btn-sm"><i class="icon-copy dw dw-image"></i>&nbsp;View</a>
										<?php } ?>
									</td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-box" id="overhaulReport" style="display:none;">
                    <div class="card-header">Overhaul Report</div>
                    <div class="card-body">
                        <table class="data-table table stripe hover nowrap">
                            <thead>
                                <th>Date</th>
                                <th>Product Name</th>
                                <th>Details</th>
                                <th>Date Accomplish</th>
                                <th>Status</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
								<?php foreach($repair as $row): ?>
									<?php if($row->Status==0){ ?>
									<tr>
										<td><?php echo $row->repairDate ?></td>
										<td><?php echo $row->productName ?></td>
										<td><?php echo $row->Details ?></td>
										<td><?php echo $row->dateAccomplished ?></td>
										<td><span class="badge bg-warning text-white">PENDING</span></td>
										<td>
											<button type="button" class="btn btn-outline-primary btn-sm accept_repair" value="<?php echo $row->rrID ?>"><i class="icon-copy dw dw-checked"></i>&nbsp;Accept</button>
										</td>
									</tr>
									<?php }else{ ?>
									<tr>
										<td><?php echo $row->repairDate ?></td>
										<td><?php echo $row->productName ?></td>
										<td><?php echo $row->Details ?></td>
										<td><?php echo $row->dateAccomplished ?></td>
										<td><span class="badge bg-primary text-white">ACCEPTED</span></td>
										<td>
										<button type="button" class="btn btn-outline-primary btn-sm view_repair" value="<?php echo $row->rrID ?>"><i class="icon-copy dw dw-image"></i>&nbsp;View</button>
										</td>
									</tr>
									<?php } ?>
								<?php endforeach; ?>
							</tbody>
                        </table>
                    </div>
                </div>
                <div class="card-box" id="transferReport" style="display:none;">
                    <div class="card-header">For Transfer</div>
                    <div class="card-body">
                        <table class="data-table table stripe hover nowrap">
                            <thead>
								<th>Location</th>
								<th>Requestor</th>
								<th>Item No</th>
								<th>Product Name</th>
								<th>Qty</th>
								<th>Effective Date</th>
								<th>Status</th>
								<th>Action</th>
                            </thead>
                            <tbody>
								<?php foreach($transfer as $row): ?>
									<?php if($row->Status==0){ ?>
									<tr>
										<td><?php echo $row->warehouseName ?></td>
										<td><?php echo $row->Fullname ?></td>
										<td><?php echo $row->itemID ?></td>
										<td><?php echo $row->productName ?></td>
										<td><?php echo number_format($row->Qty,0) ?></td>
										<td><?php echo $row->EffectiveDate ?></td>
										<td><span class="badge bg-warning text-white">PENDING</span></td>
										<td>
											<button type="button" class="btn btn-outline-primary btn-sm accept_request" value="<?php echo $row->requestID ?>"><i class="icon-copy dw dw-checked"></i>&nbsp;Accept</button>
										</td>
									</tr>
									<?php }else{ ?>
										<tr>
										<td><?php echo $row->warehouseName ?></td>
										<td><?php echo $row->Fullname ?></td>
										<td><?php echo $row->itemID ?></td>
										<td><?php echo $row->productName ?></td>
										<td><?php echo number_format($row->Qty,0) ?></td>
										<td><?php echo $row->EffectiveDate ?></td>
										<td><span class="badge bg-primary text-white">APPROVED</span></td>
										<td>-</td>
									</tr>
									<?php } ?>
								<?php endforeach; ?>
							</tbody>
                        </table>
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
                totalPendingDamage();
				totalPendingOverhaul();
				totalPendingTransfer();
            });
			$(document).on('click','.view_repair',function(e){
				e.preventDefault();
				$.ajax({url:"<?=site_url('view-accomplishment')?>",method:"GET",data:{value:$(this).val()},success:function(response)
				{
					$('#viewModal').modal('show');
					$('#result').html(response);
				}});
			});
			$(document).on('click','.accept',function()
			{
				Swal.fire({
					title: "Are you sure?",
					text: "Do you want to accept this report?",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes!"
					}).then((result) => {
						if (result.isConfirmed) {
							var val = $(this).val();
							$.ajax({
								url:"<?=site_url('accept-damage-report')?>",method:"POST",
								data:{value:val},
								success:function(response)
								{
									if(response==="Success")
									{
										location.reload();
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
			$(document).on('click','.accept_repair',function()
			{
				Swal.fire({
					title: "Are you sure?",
					text: "Do you want to accept this report?",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes!"
					}).then((result) => {
						if (result.isConfirmed) {
							var val = $(this).val();
							$.ajax({
								url:"<?=site_url('accept-repair-report')?>",method:"POST",
								data:{value:val},
								success:function(response)
								{
									if(response==="Success")
									{
										location.reload();
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
			$(document).on('click','.accept_request',function()
			{
				Swal.fire({
					title: "Are you sure?",
					text: "Do you want to accept this request?",
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Yes!"
					}).then((result) => {
						if (result.isConfirmed) {
							var val = $(this).val();
							$.ajax({
								url:"<?=site_url('accept-transfer-request')?>",method:"POST",
								data:{value:val},
								success:function(response)
								{
									if(response==="Success")
									{
										location.reload();
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
			function totalPendingOverhaul()
			{
				$.ajax({
                    url:"<?=site_url('pending-repair-report')?>",method:"GET",
                    success:function(response)
                    {
                        $('#totalPendingOverhaulItem').html(response);
                    }
                });
			}
            function totalPendingDamage()
            {
                $.ajax({
                    url:"<?=site_url('pending-damage-report')?>",method:"GET",
                    success:function(response)
                    {
                        $('#totalPendingDamageItem').html(response);
                    }
                });
            }
			function totalPendingTransfer()
            {
                $.ajax({
                    url:"<?=site_url('pending-transfer-report')?>",method:"GET",
                    success:function(response)
                    {
                        $('#totalPendingTransferItem').html(response);
                    }
                });
            }
            $('#viewDamageItems').on('click',function(e)
            {
                e.preventDefault();
                $('#damageReport').slideDown();
                $('#overhaulReport').slideUp();
                $('#transferReport').slideUp();
            });
            $('#viewOverhaulItems').on('click',function(e)
            {
                e.preventDefault();
                $('#overhaulReport').slideDown();
                $('#damageReport').slideUp();
                $('#transferReport').slideUp();
            });
            $('#viewTransferItems').on('click',function(e)
            {
                e.preventDefault();
                $('#overhaulReport').slideUp();
                $('#damageReport').slideUp();
                $('#transferReport').slideDown();
            });
        </script>
	</body>
</html>
