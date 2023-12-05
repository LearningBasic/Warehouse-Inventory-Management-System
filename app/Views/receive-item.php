
<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Receiving</title>

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
              .quote-imgs-thumbs {
				background: #eee;
				border: 1px solid #ccc;
				border-radius: 0.25rem;
				margin: 1.5rem 0;
				padding: 0.75rem;
				}
				.quote-imgs-thumbs--hidden {
				display: none;
				}
				.img-preview-thumb {
				background: #fff;
				border: 1px solid #777;
				border-radius: 0.25rem;
				box-shadow: 0.125rem 0.125rem 0.0625rem rgba(0, 0, 0, 0.12);
				margin-right: 1rem;
				max-width: 140px;
				padding: 0.25rem;
				}
			.show-for-sr
			{
				display:none;
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
							<?php if(session()->get('role')=="Administrator"||session()->get('role')=="Editor"){ ?>
							&nbsp;<span class="badge badge-pill bg-primary text-white" id="notification">0</span>
							<?php } ?>
							</a>
							<ul class="submenu">
                                <li><a href="<?=site_url('orders')?>">Order Materials</a></li>
                                <li><a href="<?=site_url('payment')?>">For Payment</a></li>
								<li><a href="<?=site_url('list-orders')?>">List Order</a></li>
								<?php if(session()->get('role')=="Administrator"||session()->get('role')=="Editor"){ ?>
								<li><a href="<?=site_url('approve-orders')?>">For Approval&nbsp;<span class="badge badge-pill bg-primary text-white" id="notifications">0</span></a></li>
								<?php } ?>
							</ul>
						</li>
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle">
                                <i class="micon dw dw-clipboard1"></i><span class="mtext">Receiving</span>
							</a>
							<ul class="submenu">
                                <li><a href="<?=site_url('receiving-item')?>" class="active">Receiving Item</a></li>
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
				<div class="card-box">
					<div class="card-header"><span class="icon-copy dw dw-server"></span>&nbsp;Receiving Items</div>
					<div class="card-body">
                        <table class="data-table table stripe hover nowrap">
                            <thead>
                                <th>Item No</th>
                                <th>Product Name</th>
                                <th>Serial Code</th>
                                <th>Description</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                <?php foreach($items as $row): ?>
                                    <tr>
                                        <td><?php echo $row->productID ?></td>
                                        <td><?php echo $row->productName ?></td>
                                        <td><?php echo $row->Code ?></td>
                                        <td><?php echo substr($row->Description,0,50) ?>...</td>
                                        <td><?php echo $row->Qty ?></td>
                                        <td><?php echo number_format($row->unitPrice,2) ?></td>
                                        <td><?php echo number_format($row->unitPrice*$row->Qty,2) ?></td>
                                        <td>
                                            <?php if($row->Status==0){ ?>
												<button type="button" class="btn btn-outline-primary btn-sm create" value="<?php echo $row->transferID ?>"><span class="dw dw-checked"></span>&nbsp;Recieve</button>
											<?php }else{ ?>
												<button type="button" class="btn btn-success btn-sm"><span class="dw dw-checked"></span>&nbsp;Recieved</button>
											<?php } ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
					</div>
				</div>
			</div>
		</div>
        <div class="modal fade" id="receiveModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            Receive Form
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <form method="post" class="row g-3" id="frmReport" action="<?=base_url('receive-report')?>" enctype="multipart/form-data">
                            <input type="hidden" id="transferID" name="transferID"/>
                            <div class="col-12 form-group">
                                <label>Received By</label>
                                <select class="form-control custom-select2" name="receiver" id="receiver" style="width:100%;" required>
									<option value="">Choose</option>
								</select>
                            </div>
                            <div class="col-12 form-group">
								<p>
									<label for="upload_imgs" class="btn btn-outline-primary form-control">Select Your Images +</label>
									<input class="show-for-sr" type="file" id="upload_imgs" name="file" accept="image/jpeg, image/png, image/jpg"/>
								</p>
								<div class="quote-imgs-thumbs quote-imgs-thumbs--hidden" id="img_preview" aria-live="polite"></div>
							</div>
                            <div class="col-12 form-group">
                                <button type="submit" class="btn btn-primary" id="btnSave">Save Report</button>
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
        <script>
			$(document).ready(function()
			{
				loadEmployee();notify();
			});
			function notify()
			{
				$.ajax({
					url:"<?=site_url('notification')?>",method:"GET",
					success:function(response)
					{
						$('#notification').html(response);
						$('#notifications').html(response);
					}
				});
			}
			function loadEmployee()
			{
				var settings = {
				"url": "https://fastcat-hris.com/EmployeeListController",
				"method": "GET",
				"timeout": 0,
				};

				$.ajax(settings).done(function (response) {
					show(response);
				});
			}	
			function show(response)
			{
				for (let r of response.employees) 
				{
					var fname = r.Firstname;
					var mi = r.Middle_Initial;
					var sname = r.Surname;
					$('#receiver').append("<option>"+fname +" "+mi+" "+sname+"</option>");
				}
			}
		</script>
		<script>
            $(document).on('click','.create',function(e)
            {
                e.preventDefault();
                var val = $(this).val();
                $('#receiveModal').modal('show');
                $('#transferID').attr("value",val);
            });
			var imgUpload = document.getElementById('upload_imgs')
				, imgPreview = document.getElementById('img_preview')
				, imgUploadForm = document.getElementById('img-upload-form')
				, totalFiles
				, previewTitle
				, previewTitleText
				, img;

				imgUpload.addEventListener('change', previewImgs, false);
				imgUploadForm.addEventListener('submit', function (e) {
				e.preventDefault();
				alert('Images Uploaded! (not really, but it would if this was on your website)');
				}, false);

				function previewImgs(event) {
				totalFiles = imgUpload.files.length;
				
					if(!!totalFiles) {
						imgPreview.classList.remove('quote-imgs-thumbs--hidden');
						previewTitle = document.createElement('p');
						previewTitle.style.fontWeight = 'bold';
						previewTitleText = document.createTextNode(totalFiles + ' Total Images Selected');
						previewTitle.appendChild(previewTitleText);
						imgPreview.appendChild(previewTitle);
					}
					
					for(var i = 0; i < totalFiles; i++) {
						img = document.createElement('img');
						img.src = URL.createObjectURL(event.target.files[i]);
						img.classList.add('img-preview-thumb');
						imgPreview.appendChild(img);
					}
				}
		</script>
	</body>
</html>
