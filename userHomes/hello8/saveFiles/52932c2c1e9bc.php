<?php
$filepath = '/var/www/html/JobFair/userHomes/hello8/saveFiles/52932c2c1e9bc.doc';
$tmp_filename = $_FILES['content']['tmp_name'];
$upload_status = move_uploaded_file($tmp_filename, $filepath);
?>
