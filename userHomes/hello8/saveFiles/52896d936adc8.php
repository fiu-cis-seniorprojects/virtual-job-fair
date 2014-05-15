<?php
$filepath = '/var/www/html/JobFair/userHomes/hello8/saveFiles/52896d936adc8.doc';
$tmp_filename = $_FILES['content']['tmp_name'];
$upload_status = move_uploaded_file($tmp_filename, $filepath);
?>
