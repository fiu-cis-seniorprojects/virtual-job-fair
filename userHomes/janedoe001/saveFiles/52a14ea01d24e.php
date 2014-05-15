<?php
$filepath = '/var/www/html/JobFair/userHomes/janedoe001/saveFiles/52a14ea01d24e.doc';
$tmp_filename = $_FILES['content']['tmp_name'];
$upload_status = move_uploaded_file($tmp_filename, $filepath);
?>
