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

								Payment Reciets Report

								<input type="button" class="btn btn-primary" value="Back" style="float:right !important" onclick="window.location=history.go(-1)" />

                            <!--    

                                <form action="server.php" style="margin: 0 8px;padding: 0;float: right;">

                                    <button class="btn btn-primary" style="float:right !important;" type="submit"> <i class="fa fa-download"></i> Export History </button>

                                    <input name="cmd" type="hidden" value="export_history" />

                                </form>

                            -->

							</h4>

							<p class="category">Your prevoisly sent reciets report.</p>

						</div>



						<div class="content table-responsive table-full-width">

						<!--	

							<form style="margin: 0 5px 0 10px;">

                            <div class="row">

								<div class="col-md-5">

									<input type="text" name="search" id="search" class="form-control" placeholder="Search by phone, text, sid" value="<?php echo $_REQUEST['search']?>" />

								</div>

								<div class="col-md-2">

									<select name="group_id" id="group_id" class="form-control">

										<option value="">By campaign</option>

									<?php

									$sql2 = "select * from campaigns where user_id = '".$_SESSION['user_id']."'";

									$res2 = mysqli_query($link,$sql2);

									while($row2 = mysqli_fetch_assoc($res2)){

										?>

											<option <?php if($_REQUEST['group_id']==$row2['id']){ echo "selected"; } ?> value="<?php echo $row2['id']; ?>"><?php echo $row2['title'];?></option>

										<?php

									}

									?>

									</select>

								</div>

								<div class="col-md-2">

									<select name="direction" id="direction" class="form-control">

										<option value="">By Direction</option>

										<option <?php if($_REQUEST['direction']=="in-bound"){ echo "selected"; } ?> value="in-bound">In-bound</option>

										<option <?php if($_REQUEST['direction']=="out-bound"){ echo "selected"; } ?> value="out-bound">Out-bound</option>

									</select>

								</div>

								<div class="col-md-2">

									<select name="is_sent" id="is_sent" class="form-control">

										<option value="">By Status</option>

										<option <?php if($_REQUEST['is_sent']=="true"){ echo "selected"; } ?> value="true">Sent</option>

										<option <?php if($_REQUEST['is_sent']=="false"){ echo "selected"; } ?> value="false">failed</option>

									</select>

								</div>

								<div class="col-md-1">

									<button class="btn btn-md btn-success"><i class="fa fa-search"></i></button>

								</div>

                            </div>

                            </form>

                        -->

                            <table id="smsReportTable" class="table table-hover table-striped listTable">

								<thead>

									<tr>

										<th>#</th>

										<th>From</th>

										<th>To</th>

										<th>Text</th>

										<th>Sent Date</th>

										<th style="width: 80px;">Info</th>

									</tr>

								</thead>

								<tbody>

			<?php

				

                $where = "where user_id='".$_SESSION['user_id']."' and type='2'";

				$where = "where  type='2'";




                if(isset($_REQUEST['search']) && $_REQUEST['search']!=''){

                    $where .= " and (to_number like '%".$_REQUEST['search']."%' or from_number like '%".$_REQUEST['search']."%' or text like '%".$_REQUEST['search']."%' or sms_sid like '%".$_REQUEST['search']."%')";

                }

                

                if(isset($_REQUEST['group_id']) && $_REQUEST['group_id']!=''){

                    $where .= " and group_id = $_REQUEST[group_id]";

                }

                

                if(isset($_REQUEST['direction']) && $_REQUEST['direction']!=''){

                    $where .= " and direction = '".$_REQUEST['direction']."'";

                }

                

                if(isset($_REQUEST['is_sent']) && $_REQUEST['is_sent']!=''){

                    $where .= " and is_sent = '".$_REQUEST['is_sent']."'";

                }

                

                $sql = "select * from sms_history $where order by id desc";

                $_SESSION['sql_history'] = $sql; 

				if(is_numeric($_GET['page']))

					$pageNum = $_GET['page'];

				else

					$pageNum = 1;

				$max_records_per_page = 20;

				$pagelink 	= "reciets_report.php?search=".$_REQUEST['search']."&group_id=".$_REQUEST['group_id']."&direction=".$_REQUEST['direction']."&is_sent=".$_REQUEST['is_sent']."&";

				$pages 		= generatePaging($sql,$pagelink,$pageNum,$max_records_per_page);

				$limit 		= $pages['limit'];

				$sql 	   .= $limit;

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

						if(($row['trumpia_error_code']=='') && ($row['is_sent']=='false')){ 

							$trumpiaStatus = checkTrumpiaMessageStatus($row['sms_sid']);

						}

			?>

						<tr>

							<td><?php echo $index++; ?></td>

							<td><?php echo $row['from_name']; ?></td>

							<td><?php echo $row['to_name']; ?></td>

							<td><?php echo $row['text']?></td>

							<td><?php echo date($appSettings['app_date_format'].' H:i:s',strtotime($row['created_date']))?></td>

							<td style="text-align: center">

								<?php

									if($row['is_sent']=='false'){

								?>

								<a href="#smsInfoModel" data-toggle="modal" onclick="getMessageDetails('<?php echo $row['id']?>')"><i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size:22px; color:orange"></i></a>

								<?php

									}else{

								?>

								<a href="#smsInfoModel" data-toggle="modal" onclick="getMessageDetails('<?php echo $row['id']?>')"><i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size:22px; color:green"></i></a>

								<?php		

									}

								?>

							</td>

						</tr>

			<?php			

					}

				}

			?>

				<tr>

					<td colspan="8" style="padding-left:0px !important;padding-right:0px !important"><?php echo $pages['pagingString'];?></td>

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

<div id="smsInfoModel" class="modal fade" role="dialog">

	<div class="modal-dialog"> 

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h6 class="custom-modal-title" style="color:red">Message Details <span id="loading" style="display:none"><img src="images/busy.gif"></span></h6>

			</div>

			<div class="modal-body loadMsgDetails" style="overflow:auto"></div>

		</div>

	</div>

</div>

<?php include_once("footer.php");?>

<link rel="stylesheet" type="text/css" href="assets/css/stacktable.css" />

<script type="text/javascript" src="assets/js/stacktable.js"></script>

<script>

	$('#smsReportTable').cardtable();

	function getMessageDetails(msgID){

		$('#loading').show();

		$.post('server.php',{"cmd":"get_message_details","msg_id":msgID},function(r){

			$('#loading').hide();

			$('.loadMsgDetails').html(r);

		});

	}

</script>