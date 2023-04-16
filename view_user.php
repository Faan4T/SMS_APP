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
								Application Sub-Accounts
								<input type="button" class="btn btn-primary move-right" value="Add New" onclick="window.location='add_app_user.php'" />
							</h4>
							<p class="category">Application sub-accounts.</p>
						</div>
						<div class="content table-responsive table-full-width">
							<table id="accountsTable" class="table table-hover table-striped listTable">
								<thead>
									<tr>
										<th>#</th>
										<th>Name</th>
										<th>Email</th>
										<th>Client</th>
										<th>Plan</th>
										<th>Status</th>
										<th>Manage</th>
									</tr>
								</thead>
								<tbody>
			<?php
				$sql = sprintf("select * from users where parent_user_id=%s and type='2' order by id desc",
                        mysqli_real_escape_string($link,DBin($_SESSION['user_id']))
                    );
				if(is_numeric($_GET['page']))
					$pageNum = DBin($_GET['page']);
				else
					$pageNum = 1;
				$max_records_per_page = 20;
				$pagelink 	= DBout("view_user.php?");
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
				$res = mysqli_query($link,$sql);
				if(mysqli_num_rows($res)){
					$index = DBout($countPaging);
					while($row = mysqli_fetch_assoc($res)){
			?>
						<tr>
							<td><?php echo DBout($index++)?></td>
							<td><?php echo DBout($row['first_name'].' '.$row['last_name']);?></td>
							<td><?php echo DBout($row['email']);?></td>
							<td><?php echo DBout($row['Client']);?></td>
							<td>
								<?php
									$sel = sprintf("select pp.title from package_plans pp, user_package_assignment upa where upa.pkg_id=pp.id and upa.user_id=%s ",
                                                mysqli_real_escape_string($link,DBin($row['id']))
                                        );
									$exe = mysqli_query($link,$sel);
									if(mysqli_num_rows($exe)==0)
										echo DBout('N/A');
									else{
										$rec = mysqli_fetch_assoc($exe);
										echo DBout($rec['title']);
									}
									?>
									
								</td>
							<td>
								<?php 
									if($row['status']=='1'){ ?>
										<span class="badge badge-success">Active</span>
								<?php
									}
									else if($row['status']=='2'){ ?>
										<span class="badge badge-warning">Blocked</span>
								<?php
									}
									else if($row['status']=='3'){ ?>
                                        <span class="badge badge-danger">Deleted</span>
											
								<?php	}
										
								?>
							</td>
							<td class="text-left">
								<a href="edit_app_user.php?id=<?php echo encode(DBout($row['id']))?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
								<i class="fa fa-remove red pointer" onclick="deleteAppUser('<?php echo encode(DBout($row['id']))?>')"></i>
							</td>
						</tr>
			<?php
					}
				}
			?>
				<tr>
					<td colspan="8" class="padding-right-0 padding-left-0"><?php echo $pages['pagingString'];?></td>
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
<script src="scripts/view_user.js">
</script>