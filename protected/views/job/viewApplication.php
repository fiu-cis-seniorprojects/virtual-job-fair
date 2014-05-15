<br/><br/><br/>

<?php 
$applicant = User::getUser($application->userid);
$username = $applicant->username;
?>
<div id="jobInfo">
<div class="titlebox">Application Info</div>
<name><?php echo $applicant->first_name .' ' . $applicant->last_name;?></name><br>
<?php 
$postdate = strtotime($job->post_date);
$deadline = strtotime($job->deadline);

?>
<br>
<jobdetail>JOB:</jobdetail> <a href="/JobFair/index.php/job/view/jobid/<?php echo $job->id ?>"><?php echo $job->title; ?> </a></br>
<jobdetail>APPLY DATE:</jobdetail> <?php echo date('F j, Y', strtotime($application->application_date)); ?></br>

<br/>
<jobdetail>DESCRIPTION:</jobdetail> <br>
<p style="margin-left: 10px;"><?php echo $application->coverletter ?></p>



<div style=clear:both></div>
</div>