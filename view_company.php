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

							<h4 class="title">Company

								<input style="float: right!important;" type="button" class="btn btn-primary" value="Add New" onclick="window.location='add_company.php'" />

							</h4>

							<p class="category">List of Companies.</p>

							<div id="alertArea"></div>

						</div>

						<?php

						$sql_campaign = mysqli_query($link, sprintf(
							"select * from companies where user_id=%s ",

							mysqli_real_escape_string($link, filterVar($_SESSION['user_id']))

						));



						if (isset($_REQUEST['search']) && $_REQUEST['search'] != '') {

							$sql_campaign = mysqli_query($link, "SELECT * FROM `companies` WHERE `name` LIKE '%" . $_REQUEST['search'] . "%' and user_id=" . $_SESSION['user_id'] . "") or die(mysqli_error($link));
						}



						?>

						<div class="col-md-4"><span class="badge badge-success"><?php echo 'Total : ' . mysqli_num_rows($sql_campaign); ?></span></div><br>

						<div class="content table-responsive table-full-width">

							<div class="row">

								<form action="view_company.php" class="view_subscriber_class">

									<div class="col-md-6"></div>

									<div class="col-md-5">

										<input type="text" name="search" id="search" class="form-control" placeholder="Search clients" value="<?php echo DBout($_REQUEST['search']) ?>" />

									</div>

									<div class="col-md-1">

										<button class="btn btn-md btn-success"><i class="fa fa-search"></i></button>

									</div>

								</form>

							</div>
							<div class="content">
								<div class="table-scroll">
									<table id="campaignTable" class="table table-hover table-striped listTable" style="color:#999;">

										<thead>

											<tr>

												<th>#</th>

												<th style="width: 10%">Name</th>

												<th style="width: 40%">Description</th>

												<th style="width: 20%">Site URL</th>

												<th style="width: 20%">Total Compaings</th>

												<th style="width: 20%">Total Customers</th>

												<th style="width: 20%">Assign Numbers</th>

												<th style="width: 10%">Manage</th>

											</tr>

										</thead>

										<tbody>

											<?php

											if (isset($_REQUEST['search']) && $_REQUEST['search'] != '') {

												$sql = "SELECT * FROM `companies` WHERE `name` LIKE '%" . $_REQUEST['search'] . "%' and user_id='" . $_SESSION['user_id'] . "'";
											} else {

												$sql = sprintf(
													"select * from companies where user_id=%s order by id desc",

													mysqli_real_escape_string($link, filterVar($_SESSION['user_id']))

												);
											}



											if (is_numeric($_GET['page']))

												$pageNum = $_GET['page'];

											else

												$pageNum = 1;

											$max_records_per_page = 20;

											$pagelink 	= "view_company.php?";

											$pages 		= generatePaging($sql, $pagelink, $pageNum, $max_records_per_page);

											$limit 		= $pages['limit'];

											$sql 	   .= $limit;

											if ($pageNum == 1)

												$countPaging = 1;

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

												$index = DBout($countPaging);

												while ($row = mysqli_fetch_assoc($res)) {

											?>

													<tr>

														<td><?php echo $index++; ?></td>

														<td><?php echo $row['name']; ?></td>

														<td><?php echo $row['description']; ?></td>

														<td><?php echo $row['website_url']; ?></td>

														<td><?php echo mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(id) as Total FROM campaigns WHERE company_id=" . $row['id']))['Total']; ?></td>

														<td><?php echo mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(subscribers.id) AS Total FROM subscribers  LEFT JOIN subscribers_group_assignment AS a ON a.subscriber_id = subscribers.id LEFT JOIN campaigns ON campaigns.id = a.group_id WHERE campaigns.company_id=" . $row['id']))['Total']; ?></td>

														<td>

															<?php $InputArray = $row['Assign_numbers'];

															echo str_replace(',', '<br>', $InputArray); ?></td>



														<td>

															<a href="edit_company.php?id=<?php echo $row['id']; ?>"><i class="fa fa-edit" style="cursor: pointer; color: orange"></i></a>&nbsp;&nbsp;

															<i class="fa fa-trash-o" style="cursor: pointer; color: red;" onclick="deleteCompnay('<?php echo $row['id']; ?>')">



															</i>

														</td>

													</tr>

											<?php

												}
											}

											?>

											<tr>

												<td colspan="4" class="padding-left-0 padding-right-0"><?php echo $pages['pagingString']; ?></td>

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

<script src="scripts/js/bootstrap-datepicker.min.js"></script>

<script type="text/javascript" src="scripts/js/parsley.min.js"></script>

<script src="scripts/view_campaign.js"></script>

<script>
	function deleteCompnay(id) {

		if (confirm("Are you sure you want to delete this company?")) {

			if (confirm("It will delete all data regarding with this company.")) {

				$.post('server.php', {
					"cmd": "delete_company",
					id: id
				}, function() {

					window.location = 'view_company.php';

				});

			}

		}

	}
</script>