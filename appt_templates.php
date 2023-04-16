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

								Appointment Templates

								<input type="button" class="btn btn-primary" value="Add New" id="btn_right" onclick="window.location='add_appt_template.php'" />

							</h4>

							<p class="category">List of appointment templates</p>

						</div>

						<div class="content table-responsive table-full-width">

							<table id="aptTable" class="table table-hover table-striped listTable">

								<thead>

									<tr>

										<th>Sr#</th>

										<th>Title</th>

                                        <th>Created Date</th>

										<th>Manage</th>

									</tr>

								</thead>

								<tbody>

								<?php

									

									$sql = sprintf("select * from appointment_templates where user_id=%s",

											mysqli_real_escape_string($link,filterVar($_SESSION['user_id']))

										);



									$res = mysqli_query($link,$sql);

									if(mysqli_num_rows($res)){

										$index = 1;

										while($row = mysqli_fetch_assoc($res)){

								?>

										<tr>

											<td><?php echo DBout($index++); ?></td>

											<td><?php echo DBout($row['title']);?></td>

											<td><?php echo DBout($row['created_date']); ?></td>

                                            <td align="center">

												<a href="add_appt_template.php?id=<?php echo DBout($row['id'])?>"><i class="fa fa-edit" id="orange"></i></a>

												<i class="fa fa-trash-o" id="fa_trashs" onclick="deleteApt('<?php echo DBout($row['id'])?>')"></i>

											</td>

										</tr>

								<?php			

										}	

									}

								?>

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

<div id="loadAptAlerOrFollowUp" class="modal fade" role="dialog">

	<div class="modal-dialog"> 

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h6 class="custom-modal-title typeTitle">Alerts/FollowUps</h6>

			</div>

			<div class="modal-body loadAptAlerts">Loading...</div>

			<div class="modal-footer">

				<span id="duplicateCampaignloading" >Loading...</span>

				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

			</div>

		</div>

	</div>

</div>

<?php include_once("footer.php");?>

<link rel="stylesheet" type="text/css" href="assets/css/stacktable.css" />

<script type="text/javascript" src="assets/js/stacktable.js"></script>

<script src="scripts/js/appt_templates.js" >

	

</script>