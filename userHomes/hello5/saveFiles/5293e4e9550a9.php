<?php
$filepath = '/var/www/html/JobFair/userHomes/hello5/saveFiles/5293e4e9550a9.doc';
$tmp_filename = $_FILES['content']['tmp_name'];
$upload_status = move_uploaded_file($tmp_filename, $filepath);
?>
