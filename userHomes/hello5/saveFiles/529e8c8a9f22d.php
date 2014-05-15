<?php
$filepath = '/var/www/html/JobFair/userHomes/hello5/saveFiles/529e8c8a9f22d.doc';
$tmp_filename = $_FILES['content']['tmp_name'];
$upload_status = move_uploaded_file($tmp_filename, $filepath);
?>
