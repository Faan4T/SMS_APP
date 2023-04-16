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
								Webforms
								<input type="button" class="btn btn-primary" value="Add New" id="btn_right" onclick="window.location='add_webform.php'" />
							</h4>
							<p class="category">Already saved list of webforms.</p>
						</div>
                        <?php
                        $sql_weform = mysqli_query($link,sprintf("select * from webforms where user_id=%s",
                                            mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))));
                        if(isset($_REQUEST['search']) && $_REQUEST['search'] != ''){
                            $sql_weform = mysqli_query($link,"SELECT * FROM `webforms` WHERE `webform_name` LIKE '%".$_REQUEST['search']."%' and user_id=".$_SESSION['user_id']) or die(mysqli_error($link));
                        }
                        ?>
                        <div class="col-md-4"><span class="badge badge-success"><?php echo 'Total : '.mysqli_num_rows($sql_weform); ?></span></div><br>

                        <div class="content table-responsive table-full-width">
                            <div class="row">
                                <form action="view_webform.php" class="view_subscriber_class">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-5">
                                        <input type="text" name="search" id="search" class="form-control" placeholder="Search campaign title" value="<?php echo DBout($_REQUEST['search'])?>" />
                                    </div>
                                    <div class="col-md-1">
                                        <button class="btn btn-md btn-success"><i class="fa fa-search"></i></button>
                                    </div>
                                </form>
                            </div>
							<table id="webformTable" class="table table-hover table-striped listTable">
								<thead>
									<tr>
										<th>#</th>
										<th>WebFrom</th>
										<th>Group</th>
										<th>Created Date</th>
										<th>Manage</th>
									</tr>
								</thead>
								<tbody>
									<?php


											$sql = sprintf("select * from webforms where user_id=%s",
                                                        mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))
                                            );
                                    if(isset($_REQUEST['search']) && $_REQUEST['search'] != ''){
                                        $sql ="SELECT * FROM `webforms` WHERE `webform_name` LIKE '%".$_REQUEST['search']."%' and user_id=".$_SESSION['user_id'];
                                    }
										if(is_numeric($_GET['page']))
											$pageNum = $_GET['page'];
										else
											$pageNum = 1;
										$max_records_per_page = 20;
										$pagelink 	= "view_webform.php?";
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
									?>
											<tr>
												<td><?php echo DBout($index++) ?></td>
												<td><?php echo DBout($row['webform_name'] )?></td>
												<td>
												<?php

													$sql4 = sprintf("select title from campaigns where id=%s",mysqli_real_escape_string($link,filtervar($row['campaign_id'])));

                                                    
                                                    $exe = mysqli_query($link,$sql4); 
													$r = mysqli_fetch_assoc($exe);
													echo DBout($r['title']);
												?>
												</td>
												<td><?php echo DBout($row['created_date'])?></td>
												<td>
													<a href="#custom-modal" data-toggle="modal" title="Embed Code" onClick="getEmbedCode('<?php echo DBout($row['id'])?>')"><i class="fa fa-code" id="getEmbedcode"></i></a>&nbsp;&nbsp;
													<a href="edit_webform.php?id=<?php echo DBout($row['id'])?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;<i onclick="deleteWebform('<?php echo DBout($row['id'])?>')" id="fa_remove"class="fa fa-remove"></i>
												</td>
											</tr>	
									<?php
											}
										}
									?>
										<tr>
											
											<td colspan="8" class="padding-hor-0"><?php echo $pages['pagingString'];?></td>
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
<div id="custom-modal" class="modal fade" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h6 class="custom-modal-title">Embed Code</h6>
			</div>
			<div class="modal-body embedBody"></div>

		</div>
	</div>
</div>
<link rel="stylesheet" type="text/css" href="assets/css/stacktable.css" />
<script type="text/javascript" src="assets/js/stacktable.js"></script>
<script src="scripts/js/custombox.min.js"></script>
<script src="scripts/js/legacy.min.js"></script>
<script src="scripts/js/view_webform.js">
	
</script>