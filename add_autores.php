<?php



include_once("header.php");

include_once("left_menu.php");

if($_REQUEST['id']!=''){

    $sql = sprintf("select * from campaigns where id=%s ",

            mysqli_real_escape_string($link,DBin($_REQUEST['id']))

            );

    

    $res = mysqli_query($link,$sql);

    if(mysqli_num_rows($res)){

        $row = mysqli_fetch_assoc($res);

        $cmd = DBout('update_autores');

        $buttonText = DBout('Update');

        $heading = DBout('Edit Autoresponder');

    }else

        $row = array();

}else{

    $row = array();

    $cmd = DBout('create_autores');

    $buttonText = DBout('Save');

    $heading = DBout('Create Autoresponder');

}

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

                                    <?php echo DBout($heading);?>

                                    <input type="button" class="btn btn-primary" value="Back" id="btn_right" onclick="window.location='view_autores.php'" />

                                </h4>

                                <p class="category">Create your awesome autoresponders here.</p>

                            </div>

                            <div class="content table-responsive">

                                <form action="server.php" data-parsley-validate novalidate enctype="multipart/form-data" method="post">

                                    <div class="form-group">

                                        <label>Title*</label>

                                        <input type="text" name="title" parsley-trigger="change" required placeholder="Enter title..." class="form-control" value="<?php echo DBout($row['title'])?>">

                                    </div>

                                    <div class="form-group">

                                        <label>Keyword*</label>

                                        <input type="text" name="keyword" parsley-trigger="change" required placeholder="Enter keyword..." class="form-control" value="<?php echo DBout($row['keyword']);?>">

                                    </div>

                                    <div class="form-group">

                                        <label><input name="direct_subscription" <?php if($row['direct_subscription']=='1')echo DBout('checked="checked"');else echo DBout('')?> value="1" type="checkbox" /> Direct subscription</label><br />

                                        <span class="category">Check this box to enable subscription without receiving a keyword, any message received on its number will be considered as a keyword and sender will receive messages of this autoresponder.</span>

                                    </div>

                                    <div class="form-group">

                                        <label>Phone Number*</label>

                                        <select name="phone_number" class="form-control">

                                            <option value="">- Select One -</option>

                                           <?php

                                        if($appSettings['sms_gateway']=='twilio'){

                                            

                                            $sel = sprintf("select * from users_phone_numbers where user_id=%s and ( type='1' or type='4' )",

                                                mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))

                                            );

                                            

                                        }else if($appSettings['sms_gateway']=='plivo'){

                                            

                                            $sel = sprintf("select * from users_phone_numbers where user_id=%s and type='2'",

                                                mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))

                                            );

                                            

                                        }

                                        else if($appSettings['sms_gateway']=='nexmo'){

                                           $sel = sprintf("select * from users_phone_numbers where user_id=%s and type='3'",

                                                mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))

                                            );

                                        }else{

                                            $sel = sprintf("select * from users_phone_numbers where user_id=%s",

                                                mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))

                                            );

                                        }

                                        $rec = mysqli_query($link,$sel);

                                        if(mysqli_num_rows($rec)){

                                            while($numbers = mysqli_fetch_assoc($rec)){

                                                if($row['phone_number']==$numbers['phone_number']){

                                                    $selected = DBout('selected="selected"');

                                                }else{

                                                    $selected = DBout('');

                                                }

                                                ?>

                                              

                                        <option <?php echo DBout($selected) ?> value="<?php echo DBout($numbers['phone_number'])?>"><?php echo DBout($numbers['phone_number'])?></option>

                                                <?php

                                            }

                                        }

                                        ?>

                                        </select>

                                    </div>

                                    <div class="form-group">

                                        <label><input name="attach_mobile_device" <?php if($row['attach_mobile_device']=='1')echo DBout('checked="checked"');else echo DBout('');?> value="1" type="checkbox" /> Attach mobile device</label>

                                    </div>



                                    <?php if($_SESSION['user_type']=='1'){?>

                                        <div class="form-group">

                                            <label><input name="share_with_subaccounts" <?php if($row['share_with_subaccounts']=='1')echo DBout('checked="checked"');else echo DBout('');?> value="1" type="checkbox" /> Share Campaign With Subaccounts</label>

                                        </div>

                                    <?php } ?>



                                    <div class="form-group">

                                        <label>Welcome SMS*</label>

                                        <textarea name="welcome_sms" parsley-trigger="change" required placeholder="Enter welcome sms text..." class="form-control"><?php echo DBout($row['welcome_sms'])?></textarea>

                                    </div>

                                    <div class="form-group">

                                        <label>Office SMS*</label>

                                        <textarea name="already_member_sms" parsley-trigger="change" required placeholder="Enter sms text for office..." class="form-control"><?php echo DBout($row['already_member_msg'])?></textarea>

                                    </div>

                                    <div class="form-group">

                                        <label>Select Media</label>

                                        <input type="file" name="campaign_media" id="file" />

                                        <input type="hidden" name="hidden_campaign_media" value="<?php echo DBout($row['media'])?>" />

                                        <?php

                                        echo isMediaExists($row['media']);

                                        ?>

                                    </div>

                                    <div class="form-group text-right m-b-0">

                                        <button class="btn btn-primary waves-effect waves-light" type="submit"> <?php echo DBout($buttonText)?> </button>

                                        <button type="reset" class="btn btn-default waves-effect waves-light m-l-5" onclick="window.location = 'javascript:history.go(-1)'"> Cancel </button>

                                        <input type="hidden" name="cmd" value="<?php echo DBout($cmd)?>" />

                                        <input type="hidden" name="campaign_id" value="<?php echo DBout($row['id'])?>" />

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <?php include_once("footer_info.php");?>

    </div>
    <script>
        var camp;
        <?php if(isset($_GET['camp'])){ ?>
        camp = true
        <?php } ?>
    </script>

    <script src="js/add_autores.js"></script>

<?php include_once("footer.php");?>