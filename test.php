<?php

combineSkills(41,40);
$link = mysql_connect('jobfairdb.db.9862366.hostedresource.com', 'jobfairdb', 'E!qazxsw2') or die(mysql_error());
mysql_select_db('jobfairdb') or die(mysql_error());

function combineSkills($keepskillid, $deleteskillid) {

	$query = "UPDATE student_skill_map SET skillid='$keepskillid' WHERE skillid='$deleteskillid'";
	mysql_query($query) or die(mysql_error());
	$query = "UPDATE job_skill_map SET skillid='$keepskillid' WHERE skillid='$deleteskillid'";
	mysql_query($query) or die(mysql_error());
	$query = "DELETE FROM skillset WHERE id='$deleteskillid'";
	mysql_query($query) or die(mysql_error());
}

?>