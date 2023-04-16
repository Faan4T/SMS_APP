<?php
	include_once("header.php");
	include_once("left_menu.php");
?>
<style>
.text-white{
    color:#FFF !important;
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
							<h4 class="title">Default Campagins
							</h4>
							<p class="category">System's default list of campaigns you can copy any of the below listed campaign.</p>
							<div id="alertArea"></div>
						</div>
						<div class="content table-responsive table-full-width">
							<table id="campaignTable" class="table table-hover table-striped listTable">
								<thead>
									<tr>
										<th>#</th>
										<th>Title</th>
										<th>Keyword</th>
										<th>Type</th>
										<th>Follow Up</th>
										<th>Media</th>
										<th>Manage</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$sql = "select * from campaigns where user_id='1' and share_with_subaccounts='1' order by id desc";
										$res = mysqli_query($link,$sql);
										if(mysqli_num_rows($res)){
											$index = 1;
											while($row = mysqli_fetch_assoc($res)){
									?>
												<tr>
													<td><?php echo $index++?></td>
													<td style="text-align:left"><?php echo $row['title'];?></td>
													<td align="center"><?php echo $row['keyword'];?></td>
													<td>
                                                        <?php
                                                        if($row['type']=="0"){
                                                            echo '<label class="label text-white label-warning">Contest</label>';
                                                            $edit_url = "add_contest.php?id=".$row['id']."&camp=default";
                                                        }
                                                        else if($row['type']=="1"){
                                                            echo '<label class="label text-white label-success">Campaign</label>';
                                                            $edit_url = "edit_campaign.php?id=".$row['id']."&camp=default";
                                                        }
                                                        else if($row['type']=="2"){
                                                            echo '<label class="label text-white label-danger">AutoResponder</label>';
                                                            $edit_url = "add_autores.php?id=".$row['id']."&camp=default";
                                                        }
                                                        else if($row['type']=="3"){
                                                            echo '<label class="label text-white label-info">Trivia</label>';
                                                            $edit_url = "add_trivia.php?id=".$row['id']."&camp=default";
                                                        }
                                                        else if($row['type']=="4"){
                                                            echo '<label class="label text-white label-primary">Viral</label>';
                                                            $edit_url = "add_viral.php?id=".$row['id']."&camp=default";
                                                        }
                                                        ?>
                                                    
                                                    </td>
													<td align="center">
														<?php
															$f = mysqli_query($link,"select id from follow_up_msgs where group_id='".$row['id']."'");
															echo mysqli_num_rows($f);
														?>
													</td>
													<td align="center">
														<?php 
															echo isMediaExists($row['media']);
														?>
													</td>
													<td style="text-align:center">
														<a href="#copyCampaign" title="Donwload predefined campaign" style="color:orange" onclick="getCampaignID('<?php echo $row['id']?>')" data-toggle="modal">
															<i class="fa fa-download"></i>
														</a>
														<a href="<?php echo $edit_url; ?>" title="Preview campaign"><i class="fa fa-search"></i></a>
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
<input type="hidden" id="duplicate_camp_id" value="" />
<?php include_once("footer.php");?>
<link rel="stylesheet" type="text/css" href="assets/css/stacktable.css" />
<script type="text/javascript" src="assets/js/stacktable.js"></script>
<script src="scripts/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="scripts/js/parsley.min.js"></script>
<div id="copyCampaign" class="modal fade" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="custom-modal-title">Duplicate Campaign</h6>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label>Title</label>
					<input type="text" name="title" class="form-control" />
				</div>
				<div class="form-group">
					<label>Keyword</label>
					<input type="text" name="keyword" class="form-control" placeholder="Keyword should be unique." />
				</div>
			</div>
			<div class="modal-footer">
				<span id="duplicateCampaignloading" style="display:none">Loading...</span>
				<button type="button" class="btn btn-success" onclick="duplicateCampaign()">Save</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div id="blockedNumbersSection" class="modal fade" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="custom-modal-title showSubsType">Unsubscribers</h6>
			</div>
			<div class="modal-body showBlockedNumbers" style="overflow:auto">Loading...</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div id="custom-modal-twitter" class="modal fade" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="custom-modal-title">Post on Twitter</h6>
			</div>
			<div class="modal-body buklSMSBody">
				<div class="form-group">
					<table width="100%" id="">
				<tr>
					<td align="left" width="25%"><label>Message</label></td>
					<td align="left">
					   <textarea name="post_message_tw" id="post_message_tw" class="form-control"></textarea>
                       <input name="camp_id_tw" id="camp_id_tw" type="hidden" />
					</td>
				</tr>
                
				<tr>
					<td>&nbsp;</td>
					<td align="left">
                        <!-- <input type="button" value="Send Now" class="btn btn-danger" onclick="sendBulkSMS()" />-->
                        <input type="button" value="Post on Twitter" class="btn btn-success" style="margin-top:10px" onclick="PostMessage_tw()" />
                        &nbsp;<img src="images/busy.gif" id="loading" style="display:none">&nbsp;<span id="showresponse"></span>
                        <input type="hidden" name="hidden_sms_id" id="hidden_sms_id" value="">
                    </td>
				</tr>
			</table>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div id="custom-modal" class="modal fade" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="custom-modal-title">Post on Facebook</h6>
			</div>
			<div class="modal-body buklSMSBody">
				<div class="form-group">
					<table width="100%" id="">
						<tr>
							<td align="left" width="25%"><label>Message</label></td>
							<td align="left">
							   <textarea name="post_message" id="post_message" class="form-control"></textarea>
							   <input name="camp_id" id="camp_id" type="hidden" />
							</td>
						</tr>
						
						<tr>
							<td>&nbsp;</td>
							<td align="left">
								<!-- <input type="button" value="Send Now" class="btn btn-danger" onclick="sendBulkSMS()" />-->
								<input type="button" value="Post on Facebook" class="btn btn-success" style="margin-top:10px" onclick="PostMessage()" />
								&nbsp;<img src="images/busy.gif" id="loading" style="display:none">&nbsp;<span id="showresponse"></span>
								<input type="hidden" name="hidden_sms_id" id="hidden_sms_id" value="">
							</td>
						</tr>
					</table>
				</div>
			</div>
			<!--
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			-->
		</div>
	</div>
</div>
<script>
	function duplicateCampaign(){
		var campID = $('#duplicate_camp_id').val();
		var title = $('input[name=title]').val();
		var keyword = $('input[name=keyword]').val();
		if(($.trim(title)!="") && ($.trim(keyword)!="")){
			$('#duplicateCampaignloading').show();
			$.post('server.php',{"cmd":"duplicate_campaign",title:title,keyword:keyword,campID:campID},function(r){
				var res = $.parseJSON(r);
				if(res.error=='no'){
					$('#duplicateCampaignloading').html(res.message);
					window.location = 'default_campaigns.php';
				}else{
					$('#duplicateCampaignloading').html(res.message);
				}
			});
		}else{
			alert("All fields are required.");	
		}
	}
	function getCampaignID(campID){
		$('#duplicate_camp_id').val(campID);
	}
	function loadBlockedNumbers(groupID,searchType){
		$('.showSubsType').html(searchType);
		$('.showBlockedNumbers').html('Loading...');
		$.post('server.php',{"cmd":"subscribers_stats",groupID:groupID,searchType:searchType},function(r){
			$('.showBlockedNumbers').html(r);
		});
	}
	$('#campaignTable').cardtable();
    function PostMessage_tw(){
        //Custombox.close();
        $("#alertArea").html('<div class="alert alert-info">Posted On Facebook! Please Hold...</div>');
        var post_message = $("#post_message_tw").val();
        var camp_id = $("#camp_id_tw").val();
        var qr = "camp_id="+camp_id+"&post_message="+post_message;
        $.post('share_on_twitter.php?'+qr ,function(res){
            if(res!=""){
                $("#alertArea").html(res);
            }
        });
    }
    function PostMessage(){
        //Custombox.close();
        $("#alertArea").html('<div class="alert alert-info">Posted On Facebook! Please Hold...</div>');
        var post_message = $("#post_message").val();
        var camp_id = $("#camp_id").val();
        var qr = "camp_id="+camp_id+"&post_message="+post_message;
        $.post('share_on_facebook.php?'+qr ,function(res){
            if(res!=""){
                $("#alertArea").html(res);
            }
        });
    }
    
    function make_post_tw(camp_id){
        $("#post_message_tw").val("");
        $("#camp_id_tw").val(camp_id);
        var qr = "cmd=get_post_message&camp_id="+camp_id
        $.post('server.php?'+qr ,function(res){
            if(res!=""){
                $("#post_message_tw").val(res);
            }
        });
    }
    function make_post_fb(camp_id){
        $("#post_message").val("");
        $("#camp_id").val(camp_id);
        var qr = "cmd=get_post_message&camp_id="+camp_id
        $.post('server.php?'+qr ,function(res){
            if(res!=""){
                $("#post_message").val(res);
            }
        });
    }
    
	function deleteCampaign(id,img){
		if(confirm("Are you sure you want to delete this campagin?")){
			window.location = 'server.php?cmd=delete_campaign&id='+id;
		}
	}
</script>