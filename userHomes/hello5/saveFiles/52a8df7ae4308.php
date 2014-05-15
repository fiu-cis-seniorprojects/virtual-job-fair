<?php
$filepath = '/var/www/html/JobFair/userHomes/hello5/saveFiles/52a8df7ae4308.doc';
$tmp_filename = $_FILES['content']['tmp_name'];
$upload_status = move_uploaded_file($tmp_filename, $filepath);
?>
