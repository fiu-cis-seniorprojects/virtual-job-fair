<?php
$filepath = '/var/www/html/JobFair/userHomes/janedoe001/saveFiles/52a14e6d2b5b7.doc';
$tmp_filename = $_FILES['content']['tmp_name'];
$upload_status = move_uploaded_file($tmp_filename, $filepath);
?>
