<?php
/* @var $this ProfileController */

/* @var $this UserController */
/* @var $user User */
/* @var $form CActiveForm */



$this->breadcrumbs=array(
	'Profile'=>array('/profile'),
	'View',
);

if (!isset($resume)){
	$resume = new Resume;
}

if (!isset($videoresume)){
	$videoresume = new VideoResume;
}

if (!isset($user->basicInfo)){
	$user->basicInfo = new BasicInfo;
}
?>



<div class="form">
<script src="/JobFair/themes/bootstrap/js/jquery.bpopup-0.8.0.min.js"></script>

<script type="text/javascript">
//for the video tutorials
$(function() {
$('div.one')
.css("cursor","pointer")
.click(function(){
$(this).siblings('.child-'+this.id).toggle('slow');
});
$('div[class^=child-]').hide();
});

</script>

<script type="text/javascript">
    function Checkfiles()
    {
        var fup = document.getElementById('Resume_resume');
        var fileName = fup.value;
        var ext = fileName.substring(fileName.lastIndexOf('.') + 1);

    if(ext =="pdf" || ext=="PDF")
    {
        return true;
    }
    else
    {
        alert("Upload PDF files only");
        return false;
    }
    }
</script>

<script>
$(document).ready(function() {
	var i = 1;
	$("#saveSkills").hide();
	$("#edit").click(function(e) { 
		if($("#BasicInfo_about_me").is(":disabled") && $("#User_email").is(":disabled")
		&& $("#BasicInfo_phone").is(":disabled")&& $("#BasicInfo_city").is(":disabled")
		&& $("#BasicInfo_state").is(":disabled")) {  
			$("#BasicInfo_about_me").attr("disabled", false);
			$("#User_email").attr("disabled", false);
			$("#BasicInfo_phone").attr("disabled", false); 
			$("#BasicInfo_city").attr("disabled", false); 
			$("#BasicInfo_state").attr("disabled", false); 
			$("#edit").attr("name", "yt0");
			$("#edit img").attr("src", "/JobFair/images/ico/done.gif");
			$("#edit").attr("onclick", "$(this).closest('form').submit(); return false;");
		} else {
			$(this).closest('form').submit()			
		}
	}); 

	$("#sortable").sortable({
		change: function() { 
			$("#saveSkills").show();
		}
	});

	

	$("#saveSkills").click(function(e) {
		$(this).closest('form').submit();
	});
	
	$("#editEducation").click(function(e) { 
		
		if ($('#Education_name').val() == ""){
			alert("School Name Cannot be blank");
			return;
		}
			$(this).closest('form').submit();		
	
	});

	$("#editExperience").click(function(e) { 
		$(this).closest('form').submit();	
	});

	$("#addskill").click( function(e) {
		if ($('#addskillname').val() == ""){
			alert("Skill was left empty");
			return;
		}
		$.get("/JobFair/index.php/profile/getskill?name=" + $('#addskillname').val(), function (data,status) {
			
			$("#skills ul").append('<li id="newskill' + i + '"><span class="skilldrag">' + $('#addskillname').val() + 
					"<input type='hidden' name='Skill[]' value='" + data + "' /></span>"  +
					'<a class="deletenewskill" id="newskill' + i + '"><img src="/JobFair/images/ico/del.gif"/></a></li>');
			$("#addskillname").val("");
			i++;
			$("#ui-id-1").hide();
		});
		$("#saveSkills").show();
	
	});
	$(document).delegate('.deleteskill','click',function(){
		$("#" + this.id).remove();
		$("#saveSkills").show();
	});

	
	$('#addskillname').bind("enterKey",function(e){
		  $("#addskill").click();
	});
	$('#addskillname').keydown(function(e){
		if(e.keyCode == 13)
		{
			e.preventDefault();
		}
	});
	$('#addskillname').keyup(function(e){
		if(e.keyCode == 13)
		{
			e.preventDefault();
			$(this).trigger("enterKey");
		}
	});
	
});

$(document).delegate('.deletenewskill','click',function(){
	$("#" + this.id).remove();
	
});

function uploadpic(){
	document.getElementById("User_image_url").click();
	document.getElementById("User_image_url").onchange = function() {
	    document.getElementById("user-uploadPicture-form").submit();
	}
}

function endWith (str, suffix){
	return str.indexOf(suffix, str.length - suffix.length) !== -1;
}

function uploadresume(){

		document.getElementById("Resume_resume").click();
		document.getElementById("Resume_resume").onchange = function() {
			if (endWith(document.getElementById("Resume_resume").value, 'pdf')){
				document.getElementById("user-uploadResume-form").submit();
			} else {
				alert('Document must be in PDF format');
			}
	    	
		}

}

function uploadvideo(){

	document.getElementById("VideoResume_videoresume").click();
	document.getElementById("VideoResume_videoresume").onchange = function() {
		if (endWith(document.getElementById("VideoResume_videoresume").value, 'MOV') 
				|| endWith(document.getElementById("VideoResume_videoresume").value, 'MP4')
				|| endWith(document.getElementById("VideoResume_videoresume").value, 'mp4')
				|| endWith(document.getElementById("VideoResume_videoresume").value, 'mov')){
			document.getElementById("user-uploadVideo-form").submit();
		} else {
			alert('Document must be in mov or mp4 format');
		}
		
    	
	}

}
</script>




<div id="fullcontent">


<div id="basicInfo">

	
	<?php $form = $this->beginWidget('CActiveForm', array(
   'id'=>'user-uploadPicture-form', 'action'=> '/JobFair/index.php/Profile/uploadImage',
   'enableAjaxValidation'=>false,
   'htmlOptions' => array('enctype' => 'multipart/form-data',),
)); ?>

<div style=clear:both></div>

<div  id="profileImage">
<div id="upload">
<img style="width:200px; 
	height:215px;" src="<?php echo $user->image_url ?>" />
	 </div>
	<a id="uploadlink" href="#" onclick="uploadpic()"><img style="margin-top: 5px;" src='/JobFair/images/ico/add.gif' />Upload Image</a>
	<?php echo CHtml::activeFileField($user, 'image_url', array('style'=>'display: none;')); ?>  

</div>

<?php $this->endWidget(); ?>

<?php $form = $this->beginWidget('CActiveForm', array(
   'id'=>'user-EditStudent-form', 'action'=> '/JobFair/index.php/Profile/EditBasicInfo',
   'enableAjaxValidation'=>false,
   'htmlOptions' => array('enctype' => 'multipart/form-data',),
)); ?>
	
	<div id="insidebasicinfo" >
	
	<name style="float:left; width:auto"><?php echo $user->first_name ." ". $user->last_name?></name>
	

	<aboutme>
		<?php echo $form->textArea($user->basicInfo,'about_me',array('rows'=>3, 'cols'=>75, 'border'=>0, 'class'=>'ta','disabled'=>'true')); ?>
	</aboutme><br>
	
	<lab>EMAIL:</lab> <?php echo $form->textField($user,'email', array('class'=>'tb5','disabled'=>'true')); ?>
	<lab>PHONE:</lab> <?php echo $form->textField($user->basicInfo,'phone', array('class'=>'tb5','disabled'=>'true')); ?>
	<lab>LOCATION:</lab> <?php echo $form->textField($user->basicInfo,'city', array('class'=>'tb5','disabled'=>'true')); ?><br>
	<lab>STATE:</lab> <?php echo $form->textField($user->basicInfo,'state', array('class'=>'tb5','disabled'=>'true')); ?>
	</div>
	<div style="clear:both"></div>

	<p><a style="float: left; margin-left:230px; width:80px" href="#" id="edit" class="editbox"><img src='/JobFair/images/ico/editButton.gif'/> Edit Info.</a></p>
	
</div> <!--  END BASIC INFO -->

	

	
	<div style="clear:both"></div>
		<?php $this->endWidget(); ?>
	<hr>


<div id="leftside">


<div style="clear:both"></div>

<div id="menutools">
<div class="titlebox">DOCUMENTS</div><br><br>
<p><a href="#" id="editResume" class="editbox"><img src='/JobFair/images/ico/add.gif' onclick="uploadresume()"/></a></p>

	
<?php

	$form = $this->beginWidget('CActiveForm', array(
	   'id'=>'user-uploadResume-form', 'action'=> '/JobFair/index.php/Profile/uploadResume',
	   'enableAjaxValidation'=>false,
	   'htmlOptions' => array('enctype' => 'multipart/form-data', 'onchange' => 'return Checkfiles();',),
	));

	echo CHtml::activeFileField($resume, 'resume', array('style'=>'display:none;'));
	if (isset($resume->resume)){
		echo CHtml::link(CHtml::encode('Resume'), $resume->resume, array('target'=>'_blank', 'style' =>'float:left'));
	} else {
		echo 'Upload a resume! PDF format only';
	}
	
	$this->endWidget();

?> 

<br><hr>

<p><a href="#" id="editVideo" class="editbox"><img src='/JobFair/images/ico/add.gif' onclick="uploadvideo()"/></a></p>
	
<?php

	$form = $this->beginWidget('CActiveForm', array(
	   'id'=>'user-uploadVideo-form', 'action'=> '/JobFair/index.php/Profile/uploadVideo',
	   'enableAjaxValidation'=>false,
	   'htmlOptions' => array('enctype' => 'multipart/form-data',),
	));

echo CHtml::activeFileField($videoresume, 'videoresume', array('style'=>'display:none;'));
if (isset($videoresume->video_path)){
	echo CHtml::link(CHtml::encode('VideoResume'), $videoresume->video_path, array('target'=>'_blank', 'style' =>'float:left'));
} else {
	echo 'Upload a video resume! MP4 or MOV format';
}

$this->endWidget();

?> 
</div>


<div id="menutools">
<div id="studentlinks">
<!--Author Manuel
making the links dynamic so if the base Url changed the program won not be affected
-->
<?php
    $image =CHtml::image(Yii::app()->baseUrl. '/images/ico/linkedinlogo.png');
    echo CHtml::link($image, array('user/auth1'));
?><br>
<a LinkedIn Connect  </a>

<hr/>
<a href="/JobFair/index.php/user/ChangePassword">Change Password</a>
</div>

</div>



</div> <!--  END LEFT SIDE -->



<div id="subcontent">

	
<div style=clear:both></div>

<?php $form = $this->beginWidget('CActiveForm', array(
   'id'=>'user-EditStudent-form', 'action'=> '/JobFair/index.php/Profile/addEducation',
   'enableAjaxValidation'=>false,
   'htmlOptions' => array('enctype' => 'multipart/form-data',),
)); ?>

<div id="education">
	<div class="titlebox">EDUCATION</div>	
	
	<div style=clear:both></div>
	<?php foreach ($user->educations as $education) {?>
	<?php
	//$list = CHtml::listData($records, 'id', 'name');
   // echo CHtml::dropDownList('names', null, $list, array('empty' => '(Select a name)'));
	?>
	
	<div>
	<?php echo $form->textField($education->fKSchool,'name',  array('class'=>'schoolName','disabled'=>'true')); ?>
	<del><a href="/JobFair/index.php/Profile/deleteEducation?id=<?php echo $education->id?>"><img src='/JobFair/images/ico/del.gif'/></a></del>
	<aboutme>
		<?php echo $form->textArea($education,'additional_info',array('rows'=>3, 'cols'=>75, 'border'=>0, 'class'=>'ta','disabled'=>'true')); ?>
	</aboutme><br>
	
	<lab> Graduation:</lab><p style="margin-left: 88px;
font-size: 14px;"><?php echo formatDate($education->graduation_date);?> </p>
	<lab> Degree:</lab><?php echo $form->textField($education,'degree', array('class'=>'school','disabled'=>'true')); ?>
	<lab> Major:</lab><?php echo $form->textField($education,'major', array('class'=>'school','disabled'=>'true')); ?>
	
	</div>
	<div style="clear:both;">
	<hr>
	</div>
	
		
	<?php }
	$education = new Education;
	
	?>
	

	
	<div class="one" id="div1">
<p><a href="javascript:void(0);" id="addEducation">+ Add Education</a></p>
</div>
<div class="child-div1">
		
		<lab2>School</lab2>
		<?php 
	$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
	'id'=>'Education_name',
    'name'=>'Education[name]',
    'source'=>$allSchools,
    // additional javascript options for the autocomplete plugin
    'options'=>array(
        'minLength'=>'2',
    ),
    'htmlOptions'=>array(
      'class'=>'school'
   		
    ),
));
	?>
	<lab2> Graduation Date:</lab2><br/>
	<?php //echo $form->textField($education,'graduation_date', array('class'=>'school')); ?>
	   	<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name' => 'Education[graduation_date]',
				'options' => array(
					'showAnim' => 'fold',	
					'dateFormat'=> 'yy-mm-dd',
				), 
				'htmlOptions'=>array(
						'class'=>'school'
				   
				),
				));?>
	
	
	<lab2> Degree Obtained:</lab2><?php echo $form->textField($education,'degree', array('class'=>'school')); ?>
	<lab2> Mayor:</lab2><?php echo $form->textField($education,'major', array('class'=>'school')); ?>
	<lab2> Additional Comments:</lab2><?php echo $form->textField($education,'additional_info', array('class'=>'school')); ?>
			<p><a href="#" id="editEducation" class="editbox"><img src='/JobFair/images/ico/add.gif'/></a></p>
</div>
</div> <!--  END EDUCATION -->
<?php $this->endWidget(); ?>

<div style="clear:both"></div>


<?php $form = $this->beginWidget('CActiveForm', array(
   'id'=>'user-EditStudent-form', 'action'=> '/JobFair/index.php/Profile/addExperience',
   'enableAjaxValidation'=>false,
   'htmlOptions' => array('enctype' => 'multipart/form-data',),
)); ?>

<div id="experience">

<div class="titlebox">EXPERIENCE</div>	
	
	<div style=clear:both></div>
	<?php foreach ($user->experiences as $experience) {?>
	<div>
	

	<?php echo $form->textField($experience,'job_title',  array('class'=>'schoolName','disabled'=>'true')); ?>
	<del><a href="/JobFair/index.php/Profile/deleteExperience?id=<?php echo $experience->id?>"><img src='/JobFair/images/ico/del.gif'/></a></del>
	<?php echo $form->textArea($experience,'job_description', array('class'=>'ta','disabled'=>'true')); ?>
	<lab>Start:</lab><p style="margin-left: 88px;
font-size: 14px;"><?php echo formatDate($experience->startdate); ?></p>
	<?php if ($experience->enddate == '0000-00-00 00:00:00'){ ?>
	<lab>End:</lab><p style="margin-left: 88px;
font-size: 14px;"><?php echo "Present" ?></p>
	<?php } else {?>
	<lab>End:</lab><p style="margin-left: 88px;
font-size: 14px;"><?php echo formatDate($experience->enddate); ?></p>
	<?php }?>
	<lab>Employer:</lab><?php echo $form->textField($experience,'company_name', array('class'=>'school','disabled'=>'true')); ?>
	</div>
	<div style="clear:both;">
	<hr>
	</div>
	
		
	<?php }
	$experience = new Experience;
	?>
	

	
	<div class="one" id="div1">
<p><a href="javascript:void(0);" id="addExperience">+ Add Experience</a></p>
</div>
<div class="child-div1">
		
	<lab2> Position:</lab2><?php echo $form->textField($experience,'job_title', array('class'=>'school')); ?>
	<lab2> Employer:</lab2><?php echo $form->textField($experience,'company_name', array('class'=>'school')); ?>
	<lab2> Job Description:</lab2><?php echo $form->textField($experience,'job_description', array('class'=>'school')); ?>
	
	<lab2> Start Date:</lab2>
	<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name' => 'Experience[startdate]',
				'options' => array(
					'showAnim' => 'fold',	
					'dateFormat'=> 'yy-mm-dd',
				), 
				'htmlOptions' => array(

						'class'=>'school'
				)
				));?>
	<?php echo $form->error($experience,'startdate'); ?>
	

	<lab2> End Date: (Leave blank if present)</lab2> 
	<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name' => 'Experience[enddate]',
				'options' => array(
					'showAnim' => 'fold',	
					'dateFormat'=> 'yy-mm-dd',
				), 
				'htmlOptions' => array(

						'class'=>'school'
				)
				));?>
	<?php echo $form->error($experience,'enddate'); ?>
	
	<lab2> City:</lab2><?php echo $form->textField($experience,'city', array('class'=>'school')); ?>
	<lab2> State:</lab2><?php echo $form->textField($experience,'state', array('class'=>'school')); ?>
		<p><a href="#" id="editExperience" class="editbox"><img src='/JobFair/images/ico/add.gif'/></a></p>
</div>

</div> <!--  END EXPERIENCE -->
<?php $this->endWidget(); ?>


<?php 
function formatDate($mysqldate){
	$time = strtotime($mysqldate);
	return date("F Y", $time);
}

?>





</div> <!--  END COTENT -->

<div id="rightside">

<?php 
$form = $this->beginWidget('CActiveForm', array(
		'id'=>'user-saveSkills-form', 'action'=> '/JobFair/index.php/Profile/saveSkills',
		'enableAjaxValidation'=>false,
		'htmlOptions' => array('enctype' => 'multipart/form-data',),
));

?>
<div id="skills">
<div class="titlebox">SKILLS</div>

	<div style=clear:both></div>
	<?php $i = 0;?>
	<ul id="sortable">
	
	<script>
		$(document).ready(function() {
			$(function() {
				$("#sortable").sortable();
				$("#sortable").disableSelection();
			});
		});
	</script>
	
	<?php foreach ($user->studentSkillMaps as $skill) {?>
		<?php $i ++?>
		
		<li id="skill<?php echo $i?>">


		<span class="skilldrag" id="skill<?php echo $i?>">
			<?php echo $skill->skill->name; ?>
			<input type="hidden" name="Skill[]" value="<?php echo $skill->skill->id;?>" />
		</span>
		<a class="deleteskill" id="skill<?php echo $i; ?>">
		<img src='/JobFair/images/ico/del.gif'/>
		</a>
		<br/>
		
		</li>
		
	<?php } ?>
	
	</ul>
	<?php $this->endWidget(); ?>
	<hr>
	<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
    'name'=>'addskillname',
	'id'=>'addskillname',
	'source'=>Skillset::getNames(),
    'htmlOptions'=>array(),)); ?>

	<?php $this->widget('bootstrap.widgets.TbButton', array(
		    'label'=>'Add Skill',
		    'type'=>'primary',
		    'htmlOptions'=>array(
		        'data-toggle'=>'modal',
		        'data-target'=>'#myModal',
				'style'=>'width: 120px',
				'id' => "addskill",
		    	'onclick' => 'return false;',
		    	'style' => "margin-top: 5px; margin-bottom: 5px;width: 120px;",
		    	),
			)); ?>
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		    'label'=>'Save Skills',
		    'type'=>'primary',
		    'htmlOptions'=>array(
		        'data-toggle'=>'modal',
		        'data-target'=>'#myModal',
				'style'=>'width: 120px',
				'id' => "saveSkills",
		    	'style' => "margin-top: 5px; margin-bottom: 5px;width: 120px;",
		    	),
			)); ?>
   
</div> <!-- End SKILLS -->



</div> <!-- END RIGHT SIDE -->

</div>
</div>

<!-- Check for first time viewing for students, prompt for LinkedIn Connect -->
<?php 

if (User::isCurrentUserStudent() && !$user->has_viewed_profile) {
	$user->has_viewed_profile = 1;
	$user->save(false);
?>
<div id="linkedinbox" style="display:none;">
<a class="bClose">x</a>
<br><br>
<h3 class="mostwantedskills">Consider using LinkedIn to create your profile.</h3>
<div id="studentlinks">
<a href="http://srprog-fall13-01.cs.fiu.edu/JobFair/index.php/profile/auth">
<img src="/JobFair/images/ico/linkedinlogo.png" height="55" width="55">
<br>LinkedIn Connect</a>
</div>
</div>
<script>
(function($) {
	$(function() {
		$('#linkedinbox').bPopup();
		$('#linkedinbox').show();
	});
})(jQuery);
</script>
<?php 
}
?>



