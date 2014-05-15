<?php
$filepath = '/var/www/html/JobFair/userHomes/hello8/saveFiles/528e4b8a63c9d.doc';
$tmp_filename = $_FILES['content']['tmp_name'];
$upload_status = move_uploaded_file($tmp_filename, $filepath);
?>
