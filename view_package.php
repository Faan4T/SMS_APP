<?php
	include_once("header.php");
	include_once("left_menu.php");
?>
<div class="main-panel">
	<?php include_once('navbar.php');?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title">
								Pricing Plans
								<input type="button" class="btn btn-primary move-right"  value="Add New" onclick="window.location='add_package.php'" />
							</h4>
							<p class="category">Your Already saved sms pricing plans.</p>
						</div>
						<div class="content table-responsive table-full-width">
							<table id="packagesTable" class="table table-hover table-striped listTable">
								<thead>
									<tr>
										<th>#</th>
										<th>Title</th>
										<th>Gateway</th>
										<th>SMS Credits</th>
										<th>Allowed Number</th>
										<th>Country</th>
										<th>Price</th>
										<th>Manage</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$sql = sprintf("select * from package_plans where user_id=%s ",
                                                mysqli_real_escape_string($link,DBin($_SESSION['user_id']))
                                            );
										$res = mysqli_query($link,$sql);
										if(mysqli_num_rows($res)){
											$index = 1;
											while($row = mysqli_fetch_assoc($res)){
									?>
												<tr>
													<td><?php echo DBout($index++)?></td>
													<td><?php echo DBout($row['title'])?></td>
													<td><?php echo ucfirst(DBout($row['sms_gateway']))?></td>
													<td align="center"><?php echo DBout($row['sms_credits'])?></td>
													<td align="center"><?php echo DBout($row['phone_number_limit'])?></td>
													<td align="center"><?php echo DBout($row['country'])?></td>
													<td><?php echo '$/'.DBout($row['price']);?></td>
													<td class="text-center">
														<a href="edit_pkg.php?id=<?php echo DBout($row['id'])?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
														<i class="fa fa-remove red pointer" onclick="deletePlan('<?php echo DBout($row['id'])?>')"></i>
													</td>
												</tr>
									<?php				
											}
										}
										else{?>
                                    <tr><td colspan="7">No plan created yet.</td></tr>
									<?php	}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include_once("footer_info.php");?>
</div>
<?php include_once("footer.php");?>
<link rel="stylesheet" type="text/css" href="assets/css/stacktable.css" />
<script type="text/javascript" src="assets/js/stacktable.js"></script>
<script src="scripts/vie_package.js"></script>