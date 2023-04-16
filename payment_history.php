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
								Payment History
								<input type="button" class="btn btn-primary move-right" value="Back" onclick="javascript:window.location='history.go(-1)'" />
							</h4>
							<p class="category">Your paypal payment history.</p>
						</div>
						<div class="content table-responsive table-full-width">
							<table id="paymentHistoryTable" class="table table-hover table-striped listTable">
								<thead>
									<tr>
										<th class="width-4-per">#</th>
										<th>Plan/Credits</th>
										<th>Payer Email</th>
										<th>Payment Status</th>
										<th>Payment</th>
									</tr>
								</thead>
								<tbody>
			<?php
				$sql = sprintf("select * from payment_history where user_id=%s order by id desc",
                            mysqli_real_escape_string($link,DBin($_SESSION['user_id']))
                    );
				if(is_numeric($_GET['page']))
					$pageNum = DBin($_GET['page']);
				else
					$pageNum = 1;
				$max_records_per_page = 20;
				$pagelink 	= DBout("payment_history.php?");
				$pages 		= generatePaging($sql,$pagelink,$pageNum,$max_records_per_page);
				$limit 		= DBout($pages['limit']);
				$sql 	   .= DBout($limit);
				if($pageNum==1)
					$countPaging=1;
				else
					$countPaging=(($pageNum*$max_records_per_page)-$max_records_per_page)+1;
							
				if($_SESSION['TOTAL_RECORDS'] <= $max_records_per_page){
					$maxLimit = $_SESSION['TOTAL_RECORDS'];	
				}else{
					$maxLimit = (((int)$countPaging+(int)$max_records_per_page)-1);
				}
				if($maxLimit >= $_SESSION['TOTAL_RECORDS']){
					$maxLimit = $_SESSION['TOTAL_RECORDS'];	
				}
				
				$res = mysqli_query($link,$sql);
				if(mysqli_num_rows($res)){
					$index = $countPaging;
					while($row = mysqli_fetch_assoc($res)){
			?>
						<tr>
							<td><?php echo DBout($index++)?></td>
							<td><?php echo DBout($row['product_name'])?></td>
							<td><?php echo DBout($row['payer_email'])?></td>
							<td><?php echo DBout($row['payment_status'])?></td>
							<td><?php echo DBout('$/'.$row['gross_payment'])?></td>
						</tr>
			<?php			
					}	
				}
			?>
				<tr>
					<td colspan="5"class="padding-left-0 padding-right-0"><?php echo $pages['pagingString'];?></td>
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
<script>
	$('#paymentHistoryTable').cardtable();
</script>