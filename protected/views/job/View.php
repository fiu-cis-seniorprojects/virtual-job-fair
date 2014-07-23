<?php
/* @var $this JobController */
/* @var $job User */
$this->breadcrumbs=array(
	'Job'=>array('/job'),
	'View',
);
$username = $job->fKPoster->username;
$user = User::getCurrentUser();
?>
<script src="/JobFair/themes/bootstrap/js/jquery.bpopup-0.8.0.min.js"></script>
<script>
(function($) {

   $(function() {

//       $('#applybutton').bind('click', function(e) {
//
//           e.preventDefault();
//           $('#applybox').bPopup();
//
//           $('#applybox').show();
//       });

   });
  
})(jQuery);
</script>
<?php if ($user) {?>
	<div id="applybox" style="display:none;">
	    <a class="bClose">x</a>
	    <br>
	    <?php $form = $this->beginWidget('CActiveForm', array(
	   'id'=>'schedule-interview', 'action'=> '/JobFair/index.php/job/apply/jobid/' . $job->id,
	   'enableAjaxValidation'=>false,
	   'htmlOptions' => array('enctype' => 'multipart/form-data',),
	)); ?>
	    
	    <?php $application = new Application;?>
	    <p>Please take the time to write a cover letter for this application</p>

		<?php echo $form->textArea($application,'coverletter',array('cols'=>100, 'rows'=>20)); ?>

		<div style="clear:both"></div>
		<input type="submit" value= "Submit" class="btn btn-primary"/>
		
		<?php $this->widget('bootstrap.widgets.TbButton', array(
		    'label'=>'Skip',
		    'type'=>'primary',
		    'htmlOptions'=>array(
		        'data-toggle'=>'modal',
		        'data-target'=>'#myModal',
				'style'=>'width: 120px;',
				'id' => "skip",
		    	'style' => "margin-top: 5px; margin-bottom: 5px;width: 120px;",
		    	'onclick' =>'$(this).closest("form").submit();',
		    	
		    	),
			)); ?>
		
	    
	    <?php $this->endWidget(); ?>
	    
				    
	</div>
<?php }?>	
			
			
<div id="fullcontent">
<div id="leftside">

<?php if ((User::getCurrentUser()->id == $job->FK_poster) || (User::isCurrentUserAdmin())) { ?>
	<div id="applicants" style="text-align: left;">
	<div class="titlebox">APPLICANTS</div>
	<div style=clear:both></div>
	<br>
	<?php foreach ($job->applications as $application) {?>
		<?php $applicant = User::getUser($application->userid);?>
		<a  style="margin:0px 10px 0px 10px;; display:block" href="/JobFair/index.php/profile/student/user/<?php echo $applicant->username ?>"> <img src='/JobFair/images/imgs/user-default.png' height="20" width="20"/> <?php echo $applicant->first_name . $applicant->last_name;?></a><br>
	<?php }?>
	</div>
	<br/>

	
<?php } ?>
<br/>



<div id="skills">
<div class="titlebox">SKILLS</div>
	<ul id="sortable">
	<?php foreach ($job->jobSkillMaps as $jobskill) {?>
		<li >
		<span class="skilldrag">
			<?php echo $jobskill->skill->name; ?>
		</span>
		</li>
	<?php } ?>
	</ul>
</div><!--  END SKILLS -->

<div style=clear:both></div>

</div>
<div id="subcontent">

<div id="jobInfo">
<div class="titlebox">JOB INFO.</div>
<name><?php echo $job->title?></name><br>
<?php 
$postdate = strtotime($job->post_date);
$deadline = strtotime($job->deadline);

?>
<br>
<jobdetail>JOB TYPE:</jobdetail> <?php echo $job->type; ?> </br>
<jobdetail>OPEN DATE:</jobdetail> <?php echo date('F j, Y', $postdate);?></br>
<jobdetail>CLOSE DATE:</jobdetail><?php echo date('F j, Y', $deadline);?></br>
<jobdetail>JOB POSTER:</jobdetail> <?php print "<a href='/JobFair/index.php/Profile/employer/user/$username'>$username</a>"?></br>
<jobdetail>COMPENSATION:</jobdetail> <?php echo $job->compensation;?></br>
<br/>
<jobdetail>DESCRIPTION:</jobdetail> <br>
<p style="margin-left: 10px;"><?php echo $job->description; ?></p>



<div style=clear:both></div>
</div>
</div>

		<div id="rightside" style="margin-top: 20px;
		border-left: 1px solid #eeeeee;
		padding-top: 10px;
		padding-left: 10px;
		height: 150px;
		width: 150px;">
<?php if (User::isCurrentUserStudent()) {?>
	<div id="application" style="text-align:center">
	<?php if (Application::hasApplied($job->id)) {?>
		<p>You have already applied</p>
	<?php } elseif ($job->active) {?>
	<?php //href="/JobFair/index.php/Job/Apply/jobid/<?php echo $job->id?>
<!--		<a id="applybutton" class="btn btn-primary" > Apply</a>-->
	<?php } else {?>
		<p>This posting is closed</p>
		<?php //echo Yii::app()->getBaseUrl(true);?>
	<?php }?>
	</div>
<?php }?>

<?php if (($user != null) && ($job->FK_poster == $user->id)) {?>
	<?php if ($job->active) {?>
		<a class="btn btn-primary" href="/JobFair/index.php/Job/Close/jobid/<?php echo $job->id?>"> Close Posting</a>
	<?php } else {?>
		<p>This posting is closed</p>
	<?php } ?>

<?php }?>
</div>

</div>

