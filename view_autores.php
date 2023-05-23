<?php

include_once("header.php");
include_once("left_menu.php");
?>
<div class="main-panel">
	<?php include_once('navbar.php'); ?>
	<div class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="header">
							<h4 class="title">
								Autoresponders
								<input style="float: right!important;" type="button" class="btn btn-primary" value="Add New" id="btn_right" onclick="window.location='add_autores.php'" />
							</h4>
							<p class="category">Your already saved list of autoresponders.</p>
						</div>
						<?php
						$sql_auto = mysqli_query($link, sprintf(
							"select * from campaigns where user_id=%s and type='2'",
							mysqli_real_escape_string($link, filterVar($_SESSION['user_id']))
						));
						if (isset($_REQUEST['search']) && $_REQUEST['search'] != '') {
							$sql_auto = mysqli_query($link, "SELECT * FROM `campaigns` WHERE `title` LIKE '%" . $_REQUEST['search'] . "%' and user_id=" . $_SESSION['user_id'] . " and type=2") or die(mysqli_error($link));
						}
						?>

						<div class="col-md-4"><span class="badge badge-success"><?php echo 'Total : ' . mysqli_num_rows($sql_auto); ?></span></div><br>
						<div class="content table-responsive table-full-width">
							<div class="row">
								<form action="view_autores.php" class="view_subscriber_class">
									<div class="col-md-6"></div>
									<div class="col-md-5">
										<input type="text" name="search" id="search" class="form-control" placeholder="Search by title" value="<?php echo DBout($_REQUEST['search']) ?>" />
									</div>
									<div class="col-md-1">
										<button class="btn btn-md btn-success"><i class="fa fa-search"></i></button>
									</div>
								</form>
							</div>
							<div class="content">
								<div class="table-scroll">
									<table id="autoResTable" class="table table-hover table-striped listTable" style="color: #999;">
										<thead>
											<tr>
												<th>#</th>
												<th>Title</th>
												<th>Keyword</th>
												<th>Phone Number</th>
												<th>Direct Subscription</th>
												<th>Subscribers / Unsubscribers</th>
												<th>Media</th>
												<th>Manage</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$sql = sprintf(
												"select * from campaigns where user_id=%s and type='2' order by id desc",
												mysqli_real_escape_string($link, filterVar($_SESSION['user_id']))
											);
											if (isset($_REQUEST['search']) && $_REQUEST['search'] != '') {
												$sql = "SELECT * FROM `campaigns` WHERE `title` LIKE '%" . $_REQUEST['search'] . "%' and user_id=" . $_SESSION['user_id'] . " and type=2";
											}

											if (is_numeric($_GET['page']))
												$pageNum = DBout($_GET['page']);
											else
												$pageNum = DBout(1);
											$max_records_per_page = DBout(20);
											$pagelink 	= "view_autores.php?";
											$pages 		= generatePaging($sql, $pagelink, $pageNum, $max_records_per_page);
											$limit 		=  DBout($pages['limit']);
											$sql 	   .= $limit;
											if ($pageNum == 1)
												$countPaging = DBout(1);
											else
												$countPaging = (($pageNum * $max_records_per_page) - $max_records_per_page) + 1;

											if ($_SESSION['TOTAL_RECORDS'] <= $max_records_per_page) {
												$maxLimit = DBout($_SESSION['TOTAL_RECORDS']);
											} else {
												$maxLimit = (((int)$countPaging + (int)$max_records_per_page) - 1);
											}
											if ($maxLimit >= $_SESSION['TOTAL_RECORDS']) {
												$maxLimit = DBout($_SESSION['TOTAL_RECORDS']);
											}
											$res = mysqli_query($link, $sql);
											if (mysqli_num_rows($res)) {
												$index = $countPaging;
												while ($row = mysqli_fetch_assoc($res)) {
											?>
													<tr>
														<td><?php echo DBout($index++); ?></td>
														<td><?php echo DBout($row['title']); ?></td>
														<td><?php echo DBout($row['keyword']); ?></td>
														<td><?php echo DBout($row['phone_number']); ?></td>
														<td>
															<?php
															if ($row['direct_subscription'] == '1') {
																echo  DBout('On');
															} else {
																echo  DBout('Off');
															}
															?>
														</td>
														<td align="center">
															<?php


															$sql2 = sprintf(
																"select s.id from subscribers_group_assignment sga, subscribers s where sga.group_id=%s and sga.subscriber_id=s.id and s.status='1'",
																mysqli_real_escape_string($link, filterVar($row['id']))
															);
															$exe = mysqli_query($link, $sql2);
															if (mysqli_num_rows($exe) == '0') {
																echo DBout(mysqli_num_rows($exe));
															} else {
															?>
																<a href="subscribers_stats.php?group_id=<?php echo DBout(encode($row["id"])) ?>&searchType=subscribers" title="View details" target="_blank"><?php echo DBout(mysqli_num_rows($exe)); ?></a>

															<?php
															}
															?>
															<?php echo DBout(' / '); ?>
															<?php

															$sql2 = sprintf(
																"select s.id from subscribers_group_assignment sga, subscribers s where sga.group_id=%s and sga.subscriber_id=s.id and s.status='2'",
																mysqli_real_escape_string($link, filterVar($row['id']))
															);
															$exe = mysqli_query($link, $sql2);




															if (mysqli_num_rows($exe) == '0') {
																echo DBout(mysqli_num_rows($exe));
															} else {
															?>
																<a href="subscribers_stats.php?group_id=<?php echo DBout(encode($row["id"])); ?>&searchType=unsubscribers" title="View details" target="_blank"><?php echo DBout(mysqli_num_rows($exe)) ?></a>
															<?php
															}
															?>
														<td>
															<?php
															echo isMediaExists($row['media']);
															?>
														</td>
														<td>
															<a href="add_autores.php?id=<?php echo DBout($row['id']) ?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
															<i class="fa fa-remove" id="fa_remove" onclick="deleteCampaign('<?php echo DBout($row['id']) ?>','<?php echo DBout($row['media']) ?>')"></i>
														</td>
													</tr>
											<?php
												}
											}
											?>
											<tr>
												<td colspan="8" class="padding-hor-0"><?php echo $pages['pagingString']; ?></td>

											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include_once("footer_info.php"); ?>
</div>
<?php include_once("footer.php"); ?>
<link rel="stylesheet" type="text/css" href="assets/css/stacktable.css" />
<script type="text/javascript" src="assets/js/stacktable.js"></script>
<script type="text/javascript" src="js/view_autores.js"></script>