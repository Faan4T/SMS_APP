	</div>



<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

<script src="assets/js/bootstrap-checkbox-radio-switch.js"></script>

<script src="assets/js/chartist.min.js"></script>

<script src="assets/js/bootstrap-notify.js"></script>

    <script src="assets/js/light-bootstrap-dashboard.js"></script>

<script type="text/javascript" src="scripts/js/parsley.min.js"></script>


    <script type="text/javascript" src="scripts/js/parsley.min.js"></script>
    <script src="scripts/js/bootstrap-datepicker.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="scripts/footer.js"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="assets/js/demo.js"></script>
<script type="text/javascript">
var notiType = '';
var noti = '';
    <?php

if(trim($_SESSION['message'])!=''){

$check = strpos($_SESSION['message'],'alert-danger');

if($check==false) { ?>
notiType = 'success';
<?php     } else { ?>

 notiType = 'danger';
<?php  } ?>

noti = "<?php echo strip_tags($_SESSION['message'])?>";
<?php unset($_SESSION['message']); }?>
</script>


<div id="verificationSection" class="modal fade" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h6 class="custom-modal-title">Verify your application</h6>

			</div>

			<div class="modal-body">

				<div class="form-group">

					<label>Enter your envato product purchase code</label>

					<input type="text" name="product_purchase_code" class="form-control" />

				</div>

			</div>

			<div class="modal-footer">

				<span class="display-none" id="verify">Verifying...</span>

				<input type="button" value="Verify" class="btn btn-success" onClick="verifyEnvatoPurchaseCode()" />

				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

			</div>

		</div>

	</div>

</div>

</body>

</html>