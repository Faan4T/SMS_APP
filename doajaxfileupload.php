<?php session_start();
include("database.php");
$con = $link;
sleep(1);
$error = "";
	$msg = DBout("success");
	$fileElementName = DBout('fileToUpload');
	if(!empty($_FILES[$fileElementName]['error']))
	{
		switch($_FILES[$fileElementName]['error'])
		{

			case '1':
				$error = DBout('The uploaded file exceeds file size limit');
				break;
			case '2':
				$error = DBout('The uploaded file exceeds file size limit');
				break;
			case '3':
				$error = DBout('The uploaded file was only partially uploaded');
				break;
			case '4':
				$error = DBout('No file was uploaded.');
				break;

			case '6':
				$error = DBout('Missing a temporary folder');
				break;
			case '7':
				$error = DBout('Failed to write file to disk');
				break;
			case '8':
				$error = DBout('File upload stopped by extension');
				break;
			case '999':
			default:
				$error = DBout('No error code avaiable');
		}
	}elseif(empty($_FILES['fileToUpload']['tmp_name']) || $_FILES['fileToUpload']['tmp_name'] == 'none')
	{
		$error = DBout('No file was uploaded..');
	}else 
	{ 
 if($_POST['select']=="image")
{   if($_POST['image_name']=="")
{
  $img_name=DBin($_POST['page_key']."_".uniqid());
}
else
{
    $img_name=DBin($_POST['image_name']);
}
    $img_path="uploaded_images/".$img_name.".jpg";
    $pos=strrpos($_FILES['fileToUpload']['name'],'.')+1;
   $ext=substr($_FILES['fileToUpload']['name'],$pos,4);
   $ext=strtolower($ext);
   if($ext=="jpg"||$ext=="jpeg")
{
   if(copy($_FILES['fileToUpload']['tmp_name'],$img_path))
{create_thumb($img_name);
$msg=$img_name;
if( $_POST['image_name'] =="")
{
    $sql=sprintf("insert into pages_data(page_key,name,type) values(%s,%s,'image')",
            mysqli_real_escape_string($con,DBin($_POST['page_key'])),
            mysqli_real_escape_string($con,DBin($img_name.'jpg'))
        );
$res=mysqli_query($con,$sql);                
}
}
}
else 
$error="only jpg image allowed";

}
else  if($_POST['select']=="video")
{
     if($_POST['image_name']=="")
{
  $img_name=DBin($_POST['page_key']."_".uniqid());
}
else
{
    $img_name=DBin($_POST['image_name']);
}
    $img_path="uploaded_videos/".$img_name.".mp4";
    $pos=strpos($_FILES['fileToUpload']['name'],'.')+1;
   $ext=substr($_FILES['fileToUpload']['name'],$pos,4);
   $ext=strtolower($ext);
   if($ext=="mp4")
{if(copy($_FILES['fileToUpload']['tmp_name'],$img_path))
{
   if($_POST['image_name'] == "")
{

    $sql=sprintf("insert into pages_data(page_key,name,type) values(%s,%s,'video')",
            mysqli_real_escape_string($con,DBin($_POST['page_key'])),
            mysqli_real_escape_string($con,DBin('$img_name.mp4'))
        );
$res=mysqli_query($con,$sql);    
}
$msg=$img_name;}
                      
}
else 
$error=DBout("only mp4 allowed");
}
    }	
	echo DBout("{");
	echo DBout("error: '" . $error . "',\n");
	echo DBout("msg: '" . $msg ."'\n");
	echo DBout("}");
    function create_thumb($img_name)
    {
     $img_path=DBout("uploaded_images");
   
     $thumb_path=DBout("uploaded_images/thumbs");
   
       $img=DBout($img_path."/".$img_name.".jpg");
        $img = imagecreatefromjpeg($img);
      $width = imagesx( $img );
      $height = imagesy( $img );

      
      $new_width =100;
      $new_height =100;
      if($width>$height)
      {
        $size=$height;
  }
  else
  {$size=$width;}
      $tmp_img = imagecreatetruecolor( $new_width, $new_height );

      
      imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $size, $size );
     
      $save_path=DBout($thumb_path."/".$img_name.".jpg");
      imagejpeg( $tmp_img, $save_path );
    }
  
  
?>
