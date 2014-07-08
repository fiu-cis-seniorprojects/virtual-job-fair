<?php
/* @var $this ProfileController */

$this->breadcrumbs=array(
	'Profile'=>array('/profile'),
	'Student',
);
$resume = $user->resume;
$basicInfo = $user->basicInfo;

function getExperienceEnd($date){
	if ($date == 0) {
		return "Present";
	} else {
		return formatDate($date);
	}
}

function formatDate($date) {
	$time = strtotime($date);
	return date("F Y", $time);
}

?>



<div id="fullcontent">

<div id="basicInfo">

<div style=clear:both></div>

<div  id="profileImage">
<img style="width:200px; height:215px;" src="<?php echo $user->image_url ?>" />
</div>

<!-- Student Profile -->

<div id="insidebasicinfo" >
<?php $form = $this->beginWidget('CActiveForm', array(
   'id'=>'user-EditStudent-form', 'action'=> '/JobFair/index.php/Profile/EditBasicInfo',
   'enableAjaxValidation'=>false,
   'htmlOptions' => array('enctype' => 'multipart/form-data',),
)); ?>

			<div style="margin-top: 50px;
			width: 600px;
			border-right: 1px solid #eeeeee;
			height: 200px;">
			<name><?php echo $user->first_name ." ". $user->last_name?></name><br>
			<aboutme>
				<?php echo $form->textArea($user->basicInfo,'about_me',array('rows'=>3, 'cols'=>75, 'border'=>0, 'class'=>'ta','disabled'=>'true')); ?>
			</aboutme><br>
			<lab>EMAIL:</lab> <?php echo $user->email; ?><br/>
			<lab>PHONE:</lab> <?php echo $basicInfo->phone; ?><br/>
			<lab>LOCATION:</lab> <?php echo $basicInfo->city . ', ' . $basicInfo->state; ?><br/>
			</div>
				<?php $this->endWidget(); ?>
</div>

<div id="contactlinks">


			<div style="margin-top: 30px;">
			
			<?php $this->widget('bootstrap.widgets.TbButton', array(
		    'label'=>'Video Interview',
		    'type'=>'primary',
		    'htmlOptions'=>array(
		        'data-toggle'=>'modal',
		        'data-target'=>'#myModal',
				'style'=>'width: 100px',
		    	),
			)); ?>
			
		<div style="clear:both"></div>
		
			
			<?php 
			$var = "/JobFair/index.php/message/send?username=" . $user->username;
			
			$this->widget('bootstrap.widgets.TbButton', array(
			    'label'=>'Message',
			    'type'=>'primary',
				'htmlOptions'=>array('style'=>'width: 100px; margin-top:10px'),
				'url'=>$var ,
			)); ?>
			
		</div>
</div><!--  END CONTACT LINKS -->

	<div style="clear:both"></div>

	<hr>


<div id="leftside">


<div id="menutools"> <!--  Start Documents -->
		<div class="titlebox">DOCUMENTS</div>
			<div style="clear:both"></div>
			<div id="inside">
			<?php
				if (isset($resume->resume)){
					echo CHtml::link(CHtml::encode('Resume'), $resume->resume, array('target'=>'_blank', 'style' =>'float:left'));
				} else {
					echo 'No Resume Uploaded Yet!';
				}
			?> 
			</div>
		
		<br><br><hr>
		<div id="inside">
			<?php
				if (isset($videoresume->video_path)){
					echo CHtml::link(CHtml::encode('VideoResume'), $videoresume->video_path, array('target'=>'_blank', 'style' =>'float:left'));
				} else {
					echo 'No Video Yet!';
				}
			?> 
		</div>
</div> <!--  End Documents -->
<?php if (User::isCurrentUserEmployer()) { ?>


<div id="menutools">
<div class="titlebox">Cover Letters</div>
		<?php foreach($user->applications as $application) {
			$job = Job::getJobById($application->jobid);
			if ((strlen($application->coverletter) > 1) && ($job->FK_poster == User::getCurrentUser()->id)) {
			?>
				<br/><hr>
				<div id="inside">
					<a href="/JobFair/index.php/job/viewApplication?jobid=<?php echo $job->id ?>&userid=<?php echo $application->userid; ?>"><?php echo $job->title; ?></a>
				</div>
		
		 <?php }
		} ?>
</div>
</div>

<?php } ?>
<div id="subcontent">

	<div id="education">
			<div class="titlebox">EDUCATION</div>	
			<div style=clear:both></div>
			
			<?php foreach ($user->educations as $education) {?>
			<div style="clear:both;"></div>
				<hr>
				<div style="margin-bottom:15px; margin-left:10px;">
				<h5><?php echo $education->fKSchool->name; ?></h5>
				<?php echo $education->additional_info; ?><br />
				<lab> Graduation:</lab><?php echo formatDate($education->graduation_date); ?> <br/>
				<lab> Degree:</lab><?php echo $education->degree; ?> <br/>
				<lab> Major:</lab><?php echo $education->major ?> <br/>
				
				</div>
				
			<?php 
			}
			?>
		</div> <!-- END EDUCATION -->
		
		<div id="experience">
			<div class="titlebox">EXPERIENCE</div>	
			
			<div style=clear:both></div>
			
			<?php foreach ($user->experiences as $experience) {?>
			<div style="clear:both;"></div>
				<hr>
				<div style="margin-bottom:15px; margin-left:10px;"">
				<h5><?php echo $experience->job_title; ?></h5>
				<?php echo $experience->job_description ?> <br />
				<lab>Start:</lab><?php echo formatDate($experience->startdate); ?><br />
				<lab>End:</lab><?php echo getExperienceEnd($experience->enddate); ?><br />
				<lab>Employer:</lab><?php echo $experience->company_name ?><br />
				</div>
				
				
				<?php 
				}
			?>
	
		</div>

</div>

<div id="rightside">
	<div id="skills">
			<div class="titlebox">SKILLS</div>
			<ul id="sortable">
			<?php foreach ($user->studentSkillMaps as $skill) {?>
				<li >
				<span class="skilldrag">
					<?php echo $skill->skill->name; ?>
				</span>
				</li>
			<?php } ?>
			</ul>
			<div style=clear:both></div>
	</div><!-- End Skills -->

</div>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'myModal')); ?>
 
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h1>Video Interview</h1>
</div>
 
<div class="modal-body">
 <h3>-Interview for: <?php echo $user->first_name;?> <?php echo $user->last_name;?></h3>
	<p>Please specify the date and time you wish the interview to take place.
	Once you schedule the interview you will recieve a reminder, and a notification will be sent to  <?php echo $user->first_name;?> <?php echo $user->last_name;?> with the date and time you specify.</p>
   
</div>


 
  <?php $form = $this->beginWidget('CActiveForm', array(
			   'id'=>'schedule-interview', 'action'=> '/JobFair/index.php/VideoInterview/ScheduleInterview',
			   'enableAjaxValidation'=>false,
			   'htmlOptions' => array('enctype' => 'multipart/form-data'),
  		

			)); ?>
			
				<div style="margin-left:20px">
				<?php $videoInterview = new VideoInterview(); ?>
				
			    <div style="clear:both"></div>
			    <!-- This is Hidden -->
			   	<input style="display:none" type="text" name="user_name" value=<?php echo $user->username;?>></input>
			   	
			   	<!-- LABEL AND INPUT FOR DATE -->
			   	<?php echo $form->labelEx($videoInterview,'date'); ?>
			   	<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name' => 'VideoInterview[date]',
				'options' => array(
					'showAnim' => 'fold',	
					'dateFormat'=> 'yy-mm-dd',
				), 
				));?>
				(yyyy-mm-dd)
				
		        
		        <!-- LABEL AND INPUT FOR TIME-->
				<?php echo $form->labelEx($videoInterview,'time'); ?>
				<?php //echo $form->textField($videoInterview,'time'); ?>
				<input name="VideoInterview[time]" id="VideoInterview_time" type="time">
				(eg. 03:28pm or 3:28am)
				</div>
	

 
<div class="modal-footer">

    <?php $this->widget('bootstrap.widgets.TbButton', array(
    	'buttonType'=>'submit',
        'type'=>'primary',
        'label'=>'Submit',
        'url'=>'#',
        'htmlOptions'=>array('data-dismiss'=>'modal'),	
    )); ?>
   
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>'Close',
        'url'=>'#',
        'htmlOptions'=>array('data-dismiss'=>'modal'),

    )); ?>

	
	<?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>
</div>



<script>
$('#myModal .modal-footer .btn-primary').on('click', function () {

	//REG EXP FOR DATE
	re = /^[0-9]{4}-[0-9]{2}-[0-9]{2}$/;

	//input validation for the date
	if(!$('#VideoInterview_date').val()){
		alert("Date cannot be left blank. Please input a date");

		return false;
		}
	if(!$('#VideoInterview_date').val().match(re)){
		alert("Please input the right format for date");
		return false;
		}

	//input validation for the time
	if(!$('#VideoInterview_time').val()){
		alert("Time cannot be left blank. Please input a time");
		return false;
		}
	//Date Validation against current date
	if($('#VideoInterview_date').val()){
		var inputDate = new Date($('#VideoInterview_date').val() );
		var todaysDate = new Date();

	if(inputDate.setHours(0,0,0,0) < todaysDate.setHours(0,0,0,0)){
		alert("Interview must be scheduled at least 1 day in advance");
		return false;
		};

		}
	

	

	
	$(this).closest('form').submit();	

	})
</script>
			


</div>
</div><!-- END FULL CONTENT -->
			


			