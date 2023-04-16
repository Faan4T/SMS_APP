<?php
	include_once("header.php");
	include_once("left_menu.php");
?>
<style>
	table thead tr th{
		text-align: center
	}
	table tbody tr td{
		text-align: center
	}
</style>
<div class="main-panel">
	<?php include_once('navbar.php'); ?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title">Campaigns <img src="load9.gif" style="margin-left: 20px; display: none" id="showLoading">
								<input type="button" class="btn btn-primary move-right" value="Add New" onclick="window.location='add_client.php'" />
							</h4>
							<p class="category">List of campaigns.</p>
							<div id="alertArea"></div>
						</div>
						<?php
						$sql_campaign = mysqli_query($link,sprintf("select * from campaigns where user_id=%s and type='6'",
							mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))
						));

						if(isset($_REQUEST['search']) && $_REQUEST['search'] != ''){
							$sql_campaign = mysqli_query($link,"SELECT * FROM `campaigns` WHERE `title` LIKE '%".$_REQUEST['search']."%' and user_id=".$_SESSION['user_id']." and type=6") or die(mysqli_error($link));
						}

						?>
						<div class="col-md-4"><span class="badge badge-success"><?php echo 'Total : '.mysqli_num_rows($sql_campaign); ?></span></div><br>
						<div class="content table-responsive table-full-width">
							<div class="row">
								<form action="view_clients.php" class="view_subscriber_class">
									<div class="col-md-6"></div>
									<div class="col-md-5">
										<input type="text" name="search" id="search" class="form-control" placeholder="Search campaigns" value="<?php echo DBout($_REQUEST['search'])?>" />
									</div>
									<div class="col-md-1">
										<button class="btn btn-md btn-success"><i class="fa fa-search"></i></button>
									</div>
								</form>
							</div>
							<table id="campaignTable" class="table table-hover table-striped listTable">
								<thead>
								<tr>
									<th>#</th>
									<th >Client Name</th>
									<th >Campaign Name</th>
									<th style="text-align: center">Number of Customers</th>
									<th style="text-align: center">Send Notification</th>
									<th style="text-align: center" >Manage</th>
								</tr>
								</thead>
								<tbody>
								<?php
								$sql = sprintf("select * from campaigns where user_id=%s and type='6' order by id desc",
									mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))
								);
								if(isset($_REQUEST['search']) && $_REQUEST['search'] != ''){
									$sql ="SELECT * FROM `campaigns` WHERE `title` LIKE '%".$_REQUEST['search']."%' and user_id=".$_SESSION['user_id']." and type=6";
								}
								
								if(is_numeric($_GET['page']))
									$pageNum = $_GET['page'];
								else
									$pageNum = 1;
								$max_records_per_page = 20;
								$pagelink 	= "view_clients.php?";
								$pages 		= generatePaging($sql,$pagelink,$pageNum,$max_records_per_page);
								$limit 		= $pages['limit'];
								$sql 	   .= $limit;
								if($pageNum==1)
									$countPaging=1;
								else
									$countPaging=(($pageNum*$max_records_per_page)-$max_records_per_page)+1;

								if($_SESSION['TOTAL_RECORDS'] <= $max_records_per_page){
									$maxLimit = DBout($_SESSION['TOTAL_RECORDS']);
								}else{
									$maxLimit = (((int)$countPaging+(int)$max_records_per_page)-1);
								}
								if($maxLimit >= $_SESSION['TOTAL_RECORDS']){
									$maxLimit =DBout( $_SESSION['TOTAL_RECORDS']);
								}

								$res = mysqli_query($link,$sql);
								if(mysqli_num_rows($res)){
									$index = DBout($countPaging);
									while($row = mysqli_fetch_assoc($res)){
								?>
										<tr>
											<td><?php echo $index++; ?></td>
											<td>
												<?php 
													$companyDetails = getCompanyDetails($row['company_id']);
													echo $companyDetails['name'];
												?>
											</td>
											<td><?php echo $row['title']; ?></td>
											<td style="text-align: center">
												<?php 
													$sel = "select s.id from subscribers s, subscribers_group_assignment sga where sga.group_id='".$row['id']."' and sga.subscriber_id=s.id";
													$exe = mysqli_query($link,$sel);
													if(mysqli_num_rows($exe)=='0'){
														echo mysqli_num_rows($exe);
													}else{
														echo '<a href="show_customers_detail.php?gid='.$row['id'].'">'.mysqli_num_rows($exe).'</a>';
													}
												?>
											</td>
											<td style="text-align: center"><input type="button" class="btn btn-warning btn-sm" value="Send Now" onClick="sendNotificationMessage(this,'<?php echo $row['id']?>')"></td>
											<td style="text-align: center">
												<a href="edit_client.php?id=<?php echo $row['id'];?>"><i class="fa fa-edit" style="cursor: pointer; color: orange"></i></a>
												&nbsp;&nbsp;
												<i class="fa fa-trash-o" style="cursor: pointer; color: red" onClick="removeClient('<?php echo $row['id']?>')"></i>
											</td>
										</tr>
								<?php
									}
								}
								?>
								<tr>
									<td colspan="5" class="padding-left-0 padding-right-0"><?php echo $pages['pagingString'];?></td>
								</tr>
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
<script src="scripts/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="scripts/js/parsley.min.js"></script>

<script src="scripts/view_campaign.js"></script>
<script>
	function removeClient(clientID){
		if(confirm("Are you sure you want to delete this client?")){
			if(confirm("It will delete all customers with this as well.")){
				$('#showLoading').show();
				$.post("server.php",{"cmd":"delete_client",clientID:clientID},function(r){
					$('#showLoading').hide();
					window.location = 'view_clients.php';
				});
			}
		}
	}
	function sendNotificationMessage(obj,clientID){
		if(confirm("Are you sure you want to send notifications now?")){
			$('#showLoading').show();
			$(obj).prop("disabled",true);
			$.post("server.php",{"cmd":"send_notification_to_customers",clientID:clientID},function(r){
				$('#showLoading').hide();
				window.location = 'view_clients.php';
			});
		}
	}
</script>