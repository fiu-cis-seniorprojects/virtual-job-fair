<?php
$filepath = '/var/www/html/JobFair/userHomes/hello8/saveFiles/527d606719945.doc';
$tmp_filename = $_FILES['content']['tmp_name'];
$upload_status = move_uploaded_file($tmp_filename, $filepath);
?>
