<?php
$filepath = '/var/www/html/JobFair/userHomes/Wayne001/saveFiles/52a9d42cb9451.doc';
$tmp_filename = $_FILES['content']['tmp_name'];
$upload_status = move_uploaded_file($tmp_filename, $filepath);
?>
