<?php
$filepath = '/var/www/html/JobFair/userHomes/hello5/saveFiles/5276de20a6c82.doc';
$tmp_filename = $_FILES['content']['tmp_name'];
$upload_status = move_uploaded_file($tmp_filename, $filepath);
?>
