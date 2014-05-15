<?php
$filepath = '/var/www/html/JobFair/userHomes/hello8/saveFiles/5283dbb17c63f.doc';
$tmp_filename = $_FILES['content']['tmp_name'];
$upload_status = move_uploaded_file($tmp_filename, $filepath);
?>
