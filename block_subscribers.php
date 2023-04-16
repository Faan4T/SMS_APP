<?php
include_once("header.php");
include_once("left_menu.php");
?>
<style>
    .chatBadge {
        display: inline-block;
        min-width: 10px;
        padding: 3px 5px;
        font-size: 10px;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        background-color:red;
        border-radius: 10px;
        margin: le;
        margin-right: -6px;
        color:#FFF;
    }
</style>
<div class="main-panel">
    <?php include_once('navbar.php');?>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">
                                Blocked Subscribers
                                <input type="button" class="btn btn-primary" value="Add New" style="float:right !important" onclick="window.location='add_subscribers.php'" />

                                <input type="button" class="btn btn-danger numberActions" value="Delete Numbers" style="float:right !important; display:none; margin: 0 8px;" onclick="deleteNumbers()" />
                                <input type="button" class="btn btn-success numberActions" value="Schedule Message" style="float:right !important; display:none;" onclick="scheduleSMS()" />
                            </h4>
                            <p class="category">List of Blocked subscribers.</p>
                        </div>
                        <div class="content table-responsive table-full-width">
                            <div class="row">
                                <form style="margin: 0 5px 0 10px;">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-5">
                                        <input type="text" name="search" id="search" class="form-control" placeholder="Search by phone, name, email" value="<?php echo $_REQUEST['search']?>" />
                                    </div>
                                    <div class="col-md-2">
                                        <select name="group_id" id="group_id" class="form-control">
                                            <option value="">By Campaign</option>
                                            <?php
                                            $sql2 = "select * from campaigns where user_id = '".$_SESSION['user_id']."'";
                                            $res2 = mysqli_query($link,$sql2);
                                            while($row2 = mysqli_fetch_assoc($res2)){
                                                ?>
                                                <option <?php if(@$_REQUEST['group_id']==$row2['id']){ echo "selected"; } ?> value="<?php echo $row2['id']; ?>"><?php echo $row2['title'];?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <button class="btn btn-md btn-success"><i class="fa fa-search"></i></button>
                                    </div>
                                </form>
                            </div>
                            <table id="subscribersTable" class="table table-hover table-striped listTable" style="margin-right: 200px !important;">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><input onclick="checkAll(this)" class="all_numbers_chk" name="all_numbers_chk" value="1" type="checkbox" /></th>
                                    <th>Name/Email</th>
                                    <th>Phone</th>
                                    <!--<th>Email</th>-->
                                    <th>Campaign</th>
                                    <!-- <th>Carreir</th> -->
                                    <th>City/State</th>
                                    <!--  <th>Birthday</th> -->
                                    <!-- <th>Anniversary</th> -->
                                    <th>Status</th>
                                    <!--<th>Subscribed</th>-->
                                    <th>Subscribed Date</th>
                                    <th>Manage</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                $sql = "select * from subscribers where status = 2 and user_id = '".$_SESSION['user_id']."' order by id desc";


                                /*
                                if(isset($_REQUEST['search']) && $_REQUEST['search']!=''){
                                    $sql = "select * from subscribers where
                                                user_id='".$_SESSION['user_id']."' and (phone_number like '%".$_REQUEST['search']."%' or email like '%".$_REQUEST['search']."%')
                                                order by id desc";
                                }else{
                                    $sql = "select * from subscribers where user_id='".$_SESSION['user_id']."' order by id desc";
                                }
                                */
                            

                                $res = mysqli_query($link,$sql) or die(mysqli_error($link));
                                if(mysqli_num_rows($res)){
                                    $index = 1;
                                    while($row = mysqli_fetch_assoc($res)){
                                        $sel = "select id as unReadMsgs from chat_history where phone_id='".$row['id']."' and is_read='0'";
                                        $exe = mysqli_query($link,$sel);
                                        if(mysqli_num_rows($exe)){
                                            $unReadMsgs = mysqli_num_rows($exe);
                                        }else{
                                            $unReadMsgs = 0;
                                        }
                                        if($appSettings['subs_lookup']=='1'){
                                            $show = '';
                                            if(trim($row['carrier_name'])==NULL){
                                                $response = subscriberLookUp($adminSettings['twilio_sid'],$adminSettings['twilio_token'],$row['phone_number'],$row['id']);
                                                $callerName = $response['caller_name']['caller_name'];
                                                $callerType = $response['caller_name']['caller_type'];
                                                $countryCode= $response['country_code'];
                                                $carrierName= $response['carrier']['name'];
                                                $carrierType= $response['carrier']['type'];
                                                $mobCountryCode = $response['carrier']['mobile_country_code'];
                                                $mobNetworkCode = $response['carrier']['mobile_network_code'];
                                            }else{
                                                $callerName  = $row['first_name'];
                                                $callerType  = $row['caller_type'];
                                                $countryCode = $row['country_code'];
                                                $carrierName = $row['carrier_name'];
                                                $carrierType = $row['carrier_type'];
                                                $mobCountryCode = $row['mobile_country_code'];
                                                $mobNetworkCode = $row['mobile_network_code'];
                                            }
                                        }else{
                                            $show = 'none';
                                            $callerName  = $row['first_name'];
                                            $callerType  = $row['caller_type'];
                                            $countryCode = $row['country_code'];
                                            $carrierName = $row['carrier_name'];
                                            $carrierType = $row['carrier_type'];
                                            $mobCountryCode = $row['mobile_country_code'];
                                            $mobNetworkCode = $row['mobile_network_code'];
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo $index++?></td>
                                            <td>
                                                <input type="checkbox" id="number_<?php echo $row['id']; ?>" name="numbers[]" value="<?php echo $row['id']; ?>" class="numbers-checkbox" />
                                            </td>
                                            <td><?php echo highlightMatch($_REQUEST['search'],$callerName)?><br /><small><?php echo highlightMatch($_REQUEST['search'],$row['email']);?></small></td>
                                            <td><?php echo highlightMatch($_REQUEST['search'],$row['phone_number']);?></td>
                                            <!--<td><?php echo $row['email'];?></td>-->
                                            <td><?php echo $row['title'];?></td>
                                            <!-- <td><?php echo $carrierName;?></td> -->
                                            <td><?php echo $row['city'];?>/<?php echo $row['state'];?></td>

                                            <td>
                                                <?php
                                                if($row['status']=='1')
                                                    echo '<span class="badge badge-success">Active</span>';
                                                else if($row['status']=='2')
                                                    echo '<span class="badge badge-warning">Blocked</span>';
                                                else if($row['status']=='3')
                                                    echo '<span class="badge badge-danger">Deleted</span>';
                                                ?>
                                            </td>
                                            <!--<td>
					<?php
                                            if($row['subs_type']=='campaign'){
                                                echo '<span class="badge badge-purple">'.ucfirst($row['subs_type']).'</span>';
                                            }else{
                                                echo '<span class="badge badge-pink">'.ucfirst($row['subs_type']).'</span>';
                                            }
                                            ?>
							</td>-->
                                            <td><?php echo date($appSettings['app_date_format'].' H:i:s',strtotime($row['created_date']));?></td>
                                            <td style="text-align:center">
                                                <?php
                                                if(trim($row['custom_info'])!=''){
                                                    ?>
                                                    <a href="#customInfoBox" title="View additional Information" onclick="getSubsCustomInfo('<?php echo $row['id']?>')" data-toggle="modal"><i class="fa fa-info"></i></a>
                                                    <?php
                                                }
                                                ?>
                                                <a href="chat.php?phoneid=<?php echo encode($row['id']).'&ph='.urlencode($row['phone_number']);?>" title="Chat">
                                                    <?php
                                                    if($unReadMsgs>0){
                                                        echo '<span class="chatBadge">'.$unReadMsgs.'</span>';
                                                    }
                                                    ?>
                                                    <i class="fa fa-comments" aria-hidden="true" style="color:#0C0"></i></a><i class="fa fa-arrow-down" style="cursor:pointer; color:#9350e9; display:<?php echo $show?>" onclick="showSubscriberDetails(this,'<?php echo $row['id']?>')"></i>&nbsp;&nbsp;<a href="add_subscribers.php?id=<?php echo $row['id']?>"><i class="fa fa-edit"></i></a>&nbsp;<i class="fa fa-remove" style="color:red; cursor:pointer" onclick="deleteSubscriber('<?php echo $row['id']?>')"></i>
                                            </td>
                                        </tr>
                                        <!--
						<tr>
							<td colspan="8" style="padding:0px; margin:0px; border:none !important">
								<div style="display:none;" class="showSubsDetials_<?php echo $row['id']?>">
									<table width="90%" style="margin:0 auto" class="table table-hover table-striped">
										<thead>
											<th>Caller Type</th>
											<th>Country Code</th>
											<th>Carrier Type</th>
											<th>Mobile Country Code</th>
											<th>Mobile Network Code</th>
										</thead>
										<tbody>
											<tr>
												<td><?php echo $callerType?></td>
												<td><?php echo $countryCode?></td>
												<td><?php echo $carrierType?></td>
												<td><?php echo $mobCountryCode?></td>
												<td><?php echo $mobNetworkCode?></td>
											</tr>
										</tbody>
									</table>
								</div>
							</td>
						</tr>
						-->
                                        <?php
                                    }
                                }
                                ?>

                                <tr>
                                    <td colspan="11" style="padding-left:0px !important;padding-right:0px !important"><?php echo $pages['pagingString'];?></td>
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

<script>
    function scheduleSMS(){
        var search = $("#search").val();
        var group_id = $("#group_id").val();
        if($(".all_numbers_chk").is(":checked")){
            var all_numbers=1;
        }else{
            var all_numbers=0;
        }

        var checked_numbers = $('[class="numbers-checkbox"]:checked').map(function() { return $(this).val().toString(); } ).get().join(",");
        window.location = "scheduler.php?search="+search+"&group_id="+group_id+"&all_numbers="+all_numbers+"&checked_numbers="+checked_numbers+"&custom=1";
    }

    function deleteNumbers(){
        var cnfrm = confirm("Are you sure to delete selected numbers?");
        if(cnfrm){
            var checked_numbers = $('[class="numbers-checkbox"]:checked').map(function() { return $(this).val().toString(); } ).get().join(",");
            window.location = "server.php?cmd=delete_checked_numbers&checked_numbers="+checked_numbers;
        }
    }
</script>

<?php include_once("footer.php");?>
<link rel="stylesheet" type="text/css" href="assets/css/stacktable.css" />

<script type="text/javascript" src="assets/js/stacktable.js"></script>

<script>

    function checkAll(obj){
        if($(obj).is(":checked")){
            $(".numbers-checkbox").prop("checked",true);
        }else{
            $(".numbers-checkbox").prop("checked",false);
        }
        showHideActions();
    }

    function showHideActions(){
        var selected_numbers = $(".numbers-checkbox").filter(':checked').length;
        if(selected_numbers>0){
            $(".numberActions").fadeIn();
        }else{
            $(".numberActions").fadeOut();
        }
    }


    $(document).ready(function(){
        $(".numbers-checkbox").change(function(){
            showHideActions();
        })
    })
</script>

<script>
    function getSubsCustomInfo(subsID){
        $('.loadCustomInfo').html('Loading...');
        $.post('server.php',{"cmd":"load_subs_custom_info","subs_id":subsID},function(r){
            $('.loadCustomInfo').html(r);
        });
    }
    $('#subscribersTable').cardtable();
    function showSubscriberDetails(obj,subsID){
        if($(obj).attr('class')=='fa fa-arrow-down'){
            $(obj).attr('class','fa fa-arrow-up')
        }else{
            $(obj).attr('class','fa fa-arrow-down')
        }
        $('.showSubsDetials_'+subsID).slideToggle();
    }
    function OnKeyPress(e){
        if(window.event){ e = window.event; }
        if(e.keyCode == 13){
            var searchkeyword = document.getElementById('searchkeyword').value;
            window.location = 'view_subscribers.php?searchkeyword='+encodeURIComponent(searchkeyword);
        }
    }
    function deleteSubscriber(id){
        if(confirm("Are you sure you want to delete this subscriber?")){
            window.location = 'server.php?cmd=delete_subscriber&id='+id;
        }
    }
</script>
<div id="customInfoBox" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="custom-modal-title">Additional Information of the Subscriber</h6>
            </div>
            <div class="modal-body loadCustomInfo"></div>
            <!--
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            -->
        </div>
    </div>
</div>