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

								Customers

								<input type="button" class="btn btn-primary move-right" value="Add New" onclick="window.location='add_subscribers.php'" />

                                <input type="button" class="btn btn-danger numberActions move-right" id="display-none" value="Delete Numbers" onclick="deleteNumbers()" />

                                <input type="button" class="btn btn-success numberActions move-right" id="display-none" value="Schedule Message" onclick="scheduleSMS()" />

							</h4>

							<p class="category">List of customers.</p>

						</div>

                        <?php

					

if($_SESSION['user_type']=='2'){ 
	$sql_subscribers = mysqli_query($link,"SELECT subscribers.* FROM users 
	LEFT JOIN companies ON companies.name = users.Client
	LEFT JOIN campaigns ON campaigns.company_id = companies.id
	LEFT JOIN subscribers_group_assignment on subscribers_group_assignment.group_id = campaigns.id
	LEFT JOIN subscribers ON subscribers.id = subscribers_group_assignment.subscriber_id
	WHERE users.id=".$_SESSION['user_id']);

}else {
	$sql_subscribers = mysqli_query($link,sprintf("select * from subscribers where user_id = %s",

	mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))

	));
}


                       

                        ?>

                        <div class="col-md-4"><span class="badge badge-success"><?php echo 'Total : '.mysqli_num_rows($sql_subscribers); ?></span></div><br>

                        <div class="content table-responsive table-full-width">

							<div class="row">

                                <form class="view_subscriber_class">

                                    <div class="col-md-4"></div>

        							<div class="col-md-5"style="margin-left:-25px;">

        								<input type="text" name="search" id="search" class="form-control" placeholder="Search by phone, name, email" value="<?php echo DBout($_REQUEST['search'])?>" />

        							</div>

                                    <div class="col-md-2"style="margin-left:15px;">

                                        <select name="group_id" id="group_id" class="form-control">

                                            <option value="">By Campaign</option>

                                        <?php

                                        $sql2 = sprintf("select * from campaigns where user_id =%s ",

                                                mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))

                                            );

                                        $res2 = mysqli_query($link,$sql2);

                                        while($row2 = mysqli_fetch_assoc($res2)){

                                            ?>

                                                <option <?php if($_REQUEST['group_id']==$row2['id']){ echo DBout("selected"); } ?> value="<?php echo DBout($row2['id']); ?>"><?php echo DBout($row2['title']);?></option>

                                            <?php

                                        }

                                        ?>

                                        </select>

                                    </div>

                                    <div class="col-md-1"style="margin-left:9px;">

                                        <button class="btn btn-md btn-success"><i class="fa fa-search"></i></button>

                                    </div>

                                </form>

                            </div>

							<table id="subscribersTable" class="table table-hover table-striped listTable margin-right-200">

								<thead>

									<tr>

										<th>#</th>

                                        <th><input onclick="checkAll(this)" class="all_numbers_chk" name="all_numbers_chk" value="1" type="checkbox" /></th>

										<th>Name/Email</th>

										<th>First initial /<br>Last initial</th>

										<th>Phone</th>

										<th>Campaign</th>

										<th>City/State</th>

										<th>Status</th>

										<th>Subscribed Date</th>

										<th>Manage</th>

									</tr>

								</thead>

								<tbody>

			<?php

			if($_SESSION['user_type']=='2'){ 
				$res2 = mysqli_query($link,"SELECT subscribers.id FROM users 
				LEFT JOIN companies ON companies.name = users.Client
				LEFT JOIN campaigns ON campaigns.company_id = companies.id
				LEFT JOIN subscribers_group_assignment on subscribers_group_assignment.group_id = campaigns.id
				LEFT JOIN subscribers ON subscribers.id = subscribers_group_assignment.subscriber_id
				WHERE users.id=".$_SESSION['user_id']);
				$ccid = [];
				while($row = mysqli_fetch_assoc($res2)){ $ccid[] = $row['id']; } 
				// print_r($ccid);

				$where = "where s.id in (".implode(',',$ccid).") and s.id=sga.subscriber_id and sga.group_id=c.id ";
			}else {   
				$where = "where s.user_id='".DBout($_SESSION['user_id'])."' and s.id=sga.subscriber_id and sga.group_id=c.id ";
			}

                

                

                

                if(isset($_REQUEST['search']) && $_REQUEST['search']!=''){

                    $search = DBin($_REQUEST['search']);

                    $where .= " and (s.phone_number like '%".$search."%' or s.email like '%".$search."%' or s.first_name like '%".$search."%' or s.last_name like '%".$search."%' or s.custom_info like '%".$search."%')";

                }

                

                if(isset($_REQUEST['group_id']) && $_REQUEST['group_id']!=''){

                    $group_id = DBin($_REQUEST['group_id']);

                    $where .= " and sga.group_id = $group_id";

                }

                

                $sql ="select s.*, c.title from subscribers s, subscribers_group_assignment sga, campaigns c $where order by s.id desc";

				if(is_numeric($_GET['page']))

					$pageNum = DBin($_GET['page']);

				else

					$pageNum = 1;

				$max_records_per_page = 20;

				$pagelink 	= "view_subscribers.php?search=".$_REQUEST['search']."&group_id=".$_REQUEST['group_id']."&";

				$pages 		= generatePaging($sql,$pagelink,$pageNum,$max_records_per_page);

				$limit 		= DBout($pages['limit']);

				$sql 	   .= DBout($limit);

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

					$maxLimit = DBout($_SESSION['TOTAL_RECORDS']);

				}

				

				$res = mysqli_query($link,$sql) or die(mysqli_error($link));

				if(mysqli_num_rows($res)){

					$index = DBout($countPaging);

					while($row = mysqli_fetch_assoc($res)){

						$sel = sprintf("select id as unReadMsgs from chat_history where phone_id=%s and is_read='0'",

                                mysqli_real_escape_string($link,filterVar($row['id']))

                            );

						$exe = mysqli_query($link,$sel);

						if(mysqli_num_rows($exe)){

							$unReadMsgs = DBout(mysqli_num_rows($exe));

						}else{

							$unReadMsgs = 0;

						}

						if($appSettings['subs_lookup']=='1'){

							$show = '';

							if(trim($row['carrier_name'])==NULL){

								$response = subscriberLookUp($adminSettings['twilio_sid'],$adminSettings['twilio_token'],$row['phone_number'],$row['id']);

								$callerName = DBout($response['caller_name']['caller_name']);

								$callerType = DBout($response['caller_name']['caller_type']);

								$countryCode= DBout($response['country_code']);

								$carrierName= DBout($response['carrier']['name']);

								$carrierType= DBout($response['carrier']['type']);

								$mobCountryCode = DBout($response['carrier']['mobile_country_code']);

								$mobNetworkCode = DBout($response['carrier']['mobile_network_code']);

							}else{

								$callerName  = DBout($row['first_name']);

								$callerType  =DBout( $row['caller_type']);

								$countryCode = DBout($row['country_code']);

								$carrierName =DBout( $row['carrier_name']);

								$carrierType = DBout($row['carrier_type']);

								$mobCountryCode = DBout($row['mobile_country_code']);

								$mobNetworkCode = DBout($row['mobile_network_code']);

							}

						}else{

							$show = DBout('none');

							$callerName  = DBout($row['first_name']);

							$callerType  = DBout($row['caller_type']);

							$countryCode = DBout($row['country_code']);

							$carrierName = DBout($row['carrier_name']);

							$carrierType = DBout($row['carrier_type']);

							$mobCountryCode = DBout($row['mobile_country_code']);

							$mobNetworkCode = DBout($row['mobile_network_code']);

						}

			?>

						<tr>

							<td><?php echo DBout($index++)?></td>

                            <td>

                                <input type="checkbox" id="number_<?php echo DBout($row['id']); ?>" name="numbers[]" value="<?php echo DBout($row['id']); ?>" class="numbers-checkbox" />

                            </td>

							<td><?php echo DBout(highlightMatch($_REQUEST['search'],$callerName))?><br /><small><?php echo DBout(highlightMatch($_REQUEST['search'],$row['email']));?></small></td>

							<td style="text-align:center;"><?php echo DBout($row['first_initial']);?>/<?php echo DBout($row['last_initial']);?></td>

                            <td><?php echo DBout(highlightMatch($_REQUEST['search'],$row['phone_number']));?></td>

                            <td><?php echo DBout($row['title']);?></td>

							<td><?php echo DBout($row['city']);?>/<?php echo DBout($row['state']);?></td>

                            

							<td>

								<?php 

									if($row['status']=='1'){ ?>

										<span class="badge badge-success">Active</span>

                                <?php }

									else if($row['status']=='2'){ ?>

										<span class="badge badge-warning">Blocked</span>

								<?php	} else if($row['status']=='3') { ?>

                                        <span class="badge badge-danger">Deleted</span>

                                <?php    }?>

							</td>

							<td><?php echo DBout(date($appSettings['app_date_format'].' H:i:s',strtotime($row['created_date'])));?></td>

							<td class="text-center">

								<?php

									if(trim($row['custom_info'])!=''){

								?>

									<a href="#customInfoBox" title="View additional Information" onclick="getSubsCustomInfo('<?php echo DBout($row['id'])?>')" data-toggle="modal"><i class="fa fa-info"></i></a>

								<?php

									}

								?>

								<a href="chat.php?phoneid=<?php echo DBout(encode($row['id']).'&ph='.urlencode($row['phone_number']));?>" title="Chat">

									<?php

										if($unReadMsgs>0){ ?>

                                            <span class="chatBadge"><?php echo DBout($unReadMsgs)?></span>

									<?php	}

									?>

									<i class="fa fa-comments green" aria-hidden="true"></i></a><i class="fa fa-arrow-down pointer pruple" style="display:<?php echo DBout($show)?>" onclick="showSubscriberDetails(this,'<?php echo DBout($row['id'])?>')"></i>&nbsp;&nbsp;<a href="add_subscribers.php?id=<?php echo DBout($row['id'])?>"><i class="fa fa-edit"></i></a>&nbsp;<i class="fa fa-remove red pointer" onclick="deleteSubscriber('<?php echo DBout($row['id'])?>')"></i>

							</td>

						</tr>

			<?php			

					}	

				}

			?>	

				

				<tr>

					<td colspan="11" class="padding-right-0 padding-left-0"><?php echo $pages['pagingString'];?></td>

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

<script src="scripts/view_subscribers.js"></script>

<div id="customInfoBox" class="modal fade" role="dialog">

	<div class="modal-dialog"> 

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h6 class="custom-modal-title">Additional Information of the Subscriber</h6>

			</div>

			<div class="modal-body loadCustomInfo"></div>

		</div>

	</div>

</div>