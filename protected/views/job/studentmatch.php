<br/><br/><br/>
<?php 
$js = Yii::app()->clientScript;
$js->registerCoreScript('jquery.ui');
$js->registerScriptFile(Yii::app()->theme->baseUrl .'/js/jquery.slimscroll.js');
?>

<?php if (isset($students)) {?>
<script>

$(document).ready(function() {
  <?php
  		$i = 0;
		foreach ($students as $student) { $i++;?>
			$("#profilebutton<?php echo $i ?>").click( function(e) {
				$(".studentmatchbox").each(function() {
					$(this).css("background-color", "transparent");
				});
				$("#student<?php echo $i; ?>").css("background-color", "lightyellow");
				$("#tabpage_1").html($("#profile<?php echo $i?>").html());
				$("#tabpage_2").html($("#resume<?php echo $i?>").html());
				$("#tabHeader_1").trigger("click");
			});
			$("#handshake<?php echo $i ?>").click( function(e) {
				$(this).attr('class', $(this).attr("class") + " disabled");
				$.get("/JobFair/index.php/job/virtualHandshake?jobid=<?php echo $_GET['jobid']?>&studentid=<?php echo $student->id; ?>", function (data,status) {
				});
			
			});


			
  <?php } ?>
  <?php if (isset($students)) {?>
			$("#profilebutton1").trigger("click");
  <?php } ?>
  $("#matchboxes").slimScroll({
		height:'800px', 
		width:'450px', 
		float: 'left',
		railVisible: true, 
		alwaysVisible: true,
		size: '10px'
	});	
});


</script>
<?php } ?>
<?php if (isset($students)) { ?>
 
<p class="notice">
Take a look at some students who have matched with your job skills.  Consider giving them a "Virtual Handshake", which sends an email notification that shows you would like for them to apply to this job.
</p>

<div style="float:left;">
<div class="matchboxes" id="matchboxes">
<?php 
$i = 0;
foreach($students as $student) { 
$i ++;
?>
	<div class="studentmatchbox" id="student<?php echo $i; ?>">
		<div class="matchLeftSide">
			<div class="matchProfileImage">
				<img src="<?php echo $student->image_url; ?>" />
			</div>
		</div>
		<div class="matchRightSide">
			<div class="matchName">
				<strong><?php echo $student->first_name . ' ' . $student->last_name . ' ' . $student->skillrating ?></strong>
			</div>
			<?php 
			$handshake = Job::hasHandshake($_GET['jobid'], User::getCurrentUser()->id, $student->id);
			?>
			
			
			<?php $this->widget('bootstrap.widgets.TbButton', array(
		    'label'=>'View Profile',
		    'type'=>'primary',
		    'htmlOptions'=>array(
		        'data-toggle'=>'modal',
		        'data-target'=>'#myModal',
				'style'=>'width: 120px',
				'id' => "profilebutton$i",
		    	'style' => "margin-top: 5px; margin-bottom: 5px;width: 120px;",
		    	),
			)); ?>
			
			<?php $this->widget('bootstrap.widgets.TbButton', array(
		    'label'=>'Virtual Handshake',
		    'type'=>'primary',
			'disabled' => $handshake,
		    'htmlOptions'=>array(
		        'data-toggle'=>'modal',
		        'data-target'=>'#myModal',
				'style'=>'width: 120px',
				'id' => "handshake$i",
		    	'style' => "margin-top: 5px; margin-bottom: 5px;width: 120px;",
		    	),
			)); ?>
			

			<?php $this->widget('bootstrap.widgets.TbButton', array(
		    'label'=>'Send Message',
		    'type'=>'primary',
			
		    'htmlOptions'=>array(

		    	'style' => "margin-top: 5px; margin-bottom: 5px;width: 120px;",
		    	'href' => '/JobFair/index.php/message/send?username=' . $student->username,
		    	'target' => 'new',
		    	),
			)); ?>
			
			
		</div>
		<div id="profile<?php echo $i?>" style="display: none;">
			<div class="matchProfileLeftSide">
				<div class="matchaboutme">
				<?php $basicInfo = $student->basicInfo; if ($basicInfo == null) $basicInfo = new BasicInfo;?>
					<name><?php echo $student->first_name .' '.  $student->last_name; ?></name>
					<aboutme>
						<?php echo $basicInfo->about_me?>
					</aboutme><br>
					<matchlab>EMAIL:</matchlab> <?php echo $student->email; ?><br/>
					<matchlab>LOCATION:</matchlab> <?php echo $basicInfo->city . ', ' . $basicInfo->state; ?><br/>
				</div>
				<div class="matcheducation">
				<div class="titlebox">EDUCATION</div>	
				<div style="clear:both"></div>
				<?php foreach ($student->educations as $education) {?>
					<div>
					<?php echo $education->fKSchool->name; ?> <br />
					<?php echo $education->additional_info; ?><br />
					<lab> Graduation:</lab><?php echo formatDate($education->graduation_date); ?> <br/>
					<lab> Degree:</lab><?php echo $education->degree; ?> <br/>
					<lab> Major:</lab><?php echo $education->major ?> <br/>
					
					</div>
					<div style="clear:both;">
					<hr>
					</div>
					<?php 
					}
					?>
				</div> <!-- END EDUCATION -->
				<div class="matchexperience">
					<div class="titlebox">EXPERIENCE</div>	
					
					<div style="clear:both"></div>
					<?php foreach ($student->experiences as $experience) {?>
						<div>
						<?php echo $experience->job_title; ?><br />
						<?php echo $experience->job_description ?> <br />
						<lab>Start:</lab><?php echo formatDate($experience->startdate); ?><br />
						<lab>End:</lab><?php echo getExperienceEnd($experience->enddate); ?><br />
						<lab>Employer:</lab><?php echo $experience->company_name ?><br />
						</div>
						<div style="clear:both;">
						<hr>
						</div>
						<?php 
						}
					?>
				</div>
			</div>
			<div class="matchProfileRightSide">
				<div id="skills">
						<div class="titlebox">SKILLS</div>
						<ul id="sortable">
						<?php foreach ($student->studentSkillMaps as $skill) {?>
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
		</div>
		<div id="resume<?php echo $i?>" style="display: none;">
			<?php 
			$resume = $student->resume;
			
            
			if (isset($resume)){ 
				$link = 'http://'.Yii::app()->request->getServerName().'/'. $resume->resume;
			?>
				<iframe src="http://docs.google.com/gview?url=<?php echo $link ?>&embedded=true" style="width:718px; height:700px;" frameborder="0"></iframe>
			<?php } else {
				echo 'No Resume Uploaded Yet!';
			}
				
			?>
			
		</div>
	</div>
<?php }?>
</div>
</div>
<div class="matchprofile">
<div id="tabContainer">
    <div class="tabs">
      <ul>
        <li id="tabHeader_1">Profile</li>
        <li id="tabHeader_2">Resume</li>
      </ul>
    </div>
    <div id="tabsContent">
      <div class="tabpage" id="tabpage_1">
      </div>
      <div class="tabpage" id="tabpage_2">
      </div>
    </div>
</div>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/tabs.js"></script>
</div>
<?php } else { ?>

<p class="notice">
There were no matches for your job.  Consider adding skills to your job.
</p>
<?php }?>
<?php 

function formatDate($date) {
	$time = strtotime($date);
	return date("F Y", $time);
}

function getExperienceEnd($date){
	if ($date == 0) {
		return "Present";
	} else {
		return formatDate($date);
	}
}



?>
