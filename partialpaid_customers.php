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

                                Partial Paid Customers

                               <input type="button" class="btn btn-danger numberActions move-right" id="display-none" value="Delete Numbers" onclick="deleteNumbers()" />

                                <input type="button" class="btn btn-success numberActions move-right" id="display-none" value="Schedule Message" onclick="scheduleSMS()" />

                            </h4>

                            <p class="category">list of your paid customers.</p>

                        </div>

                        <?php



                        $sql_subscribers = mysqli_query($link,sprintf("select * from subscribers s, subscribers_group_assignment sga where s.id = sga.subscriber_id and sga.payment_status = 1 and s.user_id = %s",

                            mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))

                        ));

                        ?>

                        <div class="col-md-4"><span class="badge badge-success"><?php echo 'Total : '.mysqli_num_rows($sql_subscribers); ?></span></div><br>

                        <div class="content table-responsive table-full-width">

                            <div class="row">

                                <form class="view_subscriber_class">

                                    <div class="col-md-4"></div>

                                    <div class="col-md-5">

                                        <input type="text" name="search" id="search" class="form-control" placeholder="Search by phone, name, email" value="<?php echo DBout($_REQUEST['search'])?>" />

                                    </div>

                                    <div class="col-md-2">

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

                                    <div class="col-md-1">

                                        <button class="btn btn-md btn-success"><i class="fa fa-search"></i></button>

                                    </div>

                                </form>

                            </div>

                            <table id="subscribersTable" class="table table-hover table-striped listTable margin-right-200">

                                <thead>

                                <tr>

                                    <th>#</th>

                                    <th>Name/Email</th>

                                    <th>Phone</th>

                                    <th>Campaign</th>

                                    <th>City/State</th>

                                    <!--<th>Amount</th>-->

                                    <th>Payment Status</th>

                                    <th>Subscribed Date</th>

                                </tr>

                                </thead>

                                <tbody>

                                <?php



                                $where = "where s.user_id='".DBout($_SESSION['user_id'])."' and s.id=sga.subscriber_id and sga.group_id=c.id and sga.payment_status = 1";
                                

                                $where = "where 1 ";


                                if(isset($_REQUEST['search']) && $_REQUEST['search']!=''){

                                    $search = DBin($_REQUEST['search']);

                                    $where .= " and (s.phone_number like '%".$search."%' or s.email like '%".$search."%' or s.first_name like '%".$search."%' or s.last_name like '%".$search."%' or s.custom_info like '%".$search."%')";

                                }



                                if(isset($_REQUEST['group_id']) && $_REQUEST['group_id']!=''){

                                    $group_id = DBin($_REQUEST['group_id']);

                                    $where .= " and sga.group_id = $group_id";

                                }



                                $sql ="select s.*, c.title, sga.payment_status from subscribers s, subscribers_group_assignment sga, campaigns c $where order by s.id desc";
                                 
                                $sql = "SELECT s.* , campaigns.title  , SUM(subscribers_payments.amount) AS amount FROM subscribers s
                                INNER JOIN subscribers_payments on s.id = subscribers_payments.subscriber_id
                                LEFT JOIN subscribers_group_assignment on s.id = subscribers_group_assignment.subscriber_id
                                LEFT JOIN campaigns on campaigns.id = subscribers_group_assignment.group_id
                                $where
                                GROUP BY s.id";


                                if(is_numeric($_GET['page']))

                                    $pageNum = DBin($_GET['page']);

                                else

                                    $pageNum = 1;

                                $max_records_per_page = 20;

                                $pagelink 	= "paid_subscribers.php?search=".$_REQUEST['search']."&group_id=".$_REQUEST['group_id']."&";

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
                                        if ($row['amount_to_be_paid'] != $row['amount']){
                                        ?>

                                        <tr>

                                            <td><?php echo DBout($index++)?></td>

                                            <td><?php echo DBout(highlightMatch($_REQUEST['search'],$callerName))?><br /><small><?php echo DBout(highlightMatch($_REQUEST['search'],$row['email']));?></small></td>

                                            <td><?php echo DBout(highlightMatch($_REQUEST['search'],$row['phone_number']));?></td>

                                            <td><?php echo DBout($row['title']);?></td>

                                            <td><?php echo DBout($row['city']);?>/<?php echo DBout($row['state']);?></td>

                                            <!--<td><?php echo '$'.DBout($row['amount_to_be_paid']);?></td>-->

                                            <td>

                                            <?php
                                            if ($row['amount_to_be_paid'] == $row['amount']){
                                                ?> <span class="badge badge-success">Paid</span> <?php
                                            }  else {
                                                ?> <span class="badge badge-success">Partial Paid</span> <?php
                                            }
                                            ?>

                                            <!--

                                                <?php

                                                if($row['payment_status']=='1'){ ?>

                                                    <span class="badge badge-success">Active</span>

                                                <?php }

                                                else if($row['payment_status']=='2'){ ?>

                                                    <span class="badge badge-warning">Blocked</span>

                                                <?php	} else if($row['status']=='3') { ?>

                                                    <span class="badge badge-danger">Deleted</span>

                                                <?php    }?>

                                            -->

                                            </td>

                                            <td><?php echo DBout(date($appSettings['app_date_format'].' H:i:s',strtotime($row['created_date'])));?></td>

                                        </tr>

                                        <?php
                                        }
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