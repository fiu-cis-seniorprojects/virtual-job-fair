<?php
$filepath = '/var/www/html/JobFair/userHomes/hello5/saveFiles/52aa044fad7b8.doc';
$tmp_filename = $_FILES['content']['tmp_name'];
$upload_status = move_uploaded_file($tmp_filename, $filepath);
?>
