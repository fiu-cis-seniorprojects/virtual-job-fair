<?php
$filepath = '/var/www/html/JobFair/userHomes/Wayne001/saveFiles/52a9d4437ab37.doc';
$tmp_filename = $_FILES['content']['tmp_name'];
$upload_status = move_uploaded_file($tmp_filename, $filepath);
?>
