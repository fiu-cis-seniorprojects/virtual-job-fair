<?php

$output_dir = "/var/www/html/JobFair/userHomes/" . $_POST["local_user"] . "/saveFiles/";
// used to be txt and docx, too close to release to take a chance, future releases may want to implement this
$allowedExts = array("doc");
$temp = explode(".", $_FILES["fileContents"]["name"]);
$extension = end($temp);
$group_name = "developers";

// if user's document folder doesn't exist, create it
if (!file_exists($output_dir)) {
	// create folders
	mkdir($output_dir, 0770);
	// set correct group
	chgrp($output_dir, $group_name);
	// failsafe set correct permissions
	system("/bin/chmod -R ug+rwx $output_dir");
	system("/bin/chmod -R o+rx $output_dir");
}

if(isset($_FILES["fileContents"])) {

	if ((($_FILES["fileContents"]["type"] == "text/plain") || ($_FILES["fileContents"]["type"] == "application/msword") || ($_FILES["fileContents"]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")) && in_array($extension, $allowedExts)) {

		//Filter the file types , if you want.
		if ($_FILES["fileContents"]["error"] > 0) {
			echo "Error: " . $_FILES["fileContents"]["error"] . "<br>";
		} else {
			//move the uploaded file to uploads folder;
			move_uploaded_file($_FILES["fileContents"]["tmp_name"],$output_dir. $_FILES["fileContents"]["name"]);
			echo "File uploaded successfully :".$_FILES["fileContents"]["name"];
		}
	} else {
		echo "Please upload a valid file" . "<br>" . "Valid Files are: txt, doc, docx";
	}
}

?>