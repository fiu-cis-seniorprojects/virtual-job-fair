<?php

$dir = "/var/www/html/JobFair/images/";
$writable = is_writable( $dir  );

$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png"))
&& in_array($extension, $allowedExts))
  {
  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    }
  else
    {

    $session = $_POST["sessionID"];
    $fileName = $session . substr( $_FILES["file"]["name"], strpos( $_FILES["file"]["name"], "." ) );
    $finalDestination =  $dir . $fileName;

        if( file_exists( $finalDestination  ) )
        {
                unlink( $finalDestination );
        }

//	echo $finalDestination;
        move_uploaded_file( $_FILES["file"]["tmp_name"], $finalDestination );
//	echo $finalDestination;
    }
  }
else
  {
  //echo "This file is not an image. Please try another file. ";
  }
?>
