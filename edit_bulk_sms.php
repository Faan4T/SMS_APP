<?php
	include_once("header.php");
	include_once("left_menu.php");
	$id = DBin($_REQUEST['id']);
	$sql = sprintf("select * from bulk_sms where id=%s",
    mysqli_real_escape_string($link,DBin($id))
        );
	$res = mysqli_query($link,$sql);
	if(mysqli_num_rows($res)==0)
		$data = array();
	else
		$data = mysqli_fetch_assoc($res);
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
								Edit Bulk SMS
								<input type="button" class="btn btn-primary move-right" value="Back" onclick="window.location='bulk_sms.php'" />
							</h4>
							<p class="category">Edit bulk sms here.</p>
						</div>
						<div class="content table-responsive">
							<form method="post" action="server.php" enctype="multipart/form-data">
							<div class="form-group">
								<label>Message</label>
								<textarea class="form-control textCounter" name="bulk_sms" required><?php echo DBout($data['message'])?></textarea>
								<span class="showCounter">
									<span class="showCount"><?php echo DBout($maxLength-strlen($data['message']))?></span> Characters left
								</span>
							</div>
                            
                            <div class="form-group">
								<label>Select Media</label>
								<input type="file" name="bulk_media" class="display-inline" />
							</div>
                            
                            <div class="form-group width-17-per">
			                     <?php 
                                if(isset($data['bulk_media']) && $data['bulk_media']!=""){?>
                                    <img src="<?php echo DBout($data['bulk_media']); ?>" class="img-thumbnail" />
                                <?php    
                                }
                                ?>
							</div>
                                                        
							<div class="form-group">
								<input type="hidden" name="bulk_id" value="<?php echo DBout($data['id'])?>">
                                <input type="hidden" name="hidden_bulk_media" value="<?php echo DBout($data['bulk_media'])?>">
								<input type="hidden" name="cmd" value="update_bulk_sms">
								<input type="submit" value="Update" class="btn btn-primary">
								<input type="button" value="Cancel" class="btn btn-default" onClick="history.go(-1)">
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
<?php include_once("footer.php");?>
<script type="text/javascript" src="assets/plugins/parsleyjs/dist/parsley.min.js"></script>