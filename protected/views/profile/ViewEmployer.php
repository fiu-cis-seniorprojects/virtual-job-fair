<?php
/* @var $this ProfileController */

$this->breadcrumbs=array(
	'Profile'=>array('/profile'),
	'View',
);

if (!isset($user->basicInfo)) {
	$user->basicInfo = new BasicInfo;
}
if (!isset($user->companyInfo)) {
	$user->companyInfo = new CompanyInfo;
}
?>


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
<script type="text/javascript" src="http://the-echoplex.net/demos/upload-file/"></script>

<script>

$(document).ready(function() {
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


	
		$("#editcompany").click(function(e) { 
			if($("#CompanyInfo_description").is(":disabled")
			&& $("#CompanyInfo_city").is(":disabled")
			&& $("#CompanyInfo_state").is(":disabled")) {  
				$("#CompanyInfo_description").attr("disabled", false);
				$("#CompanyInfo_city").attr("disabled", false); 
				$("#CompanyInfo_website").attr("disabled", false);
				$("#CompanyInfo_state").attr("disabled", false); 
				$("#editcompany").attr("name", "yt0");
				$("#editcompany img").attr("src", "/JobFair/images/ico/done.gif");
				$("#editcompany").attr("onclick", "$(this).closest('form').submit(); return false;");
			} else {
				$(this).closest('form').submit()			
			}
		}); 


	
	
});

function uploadpic(){
	document.getElementById("User_image_url").click();
	document.getElementById("User_image_url").onchange = function() {
	    document.getElementById("user-uploadPicture-form").submit();
	}
}

function toggleJobMatching(){
    var val = $('#myonoffswitch').val();
    if(val == '1')
    {
        $('#myonoffswitch').val('0');
    }
    else
    {
        $('#myonoffswitch').val('1');
    }
    $.get("/JobFair/index.php/user/toggleEmailMatch", {"value": val}, function(data){
        data = JSON.parse(data);
        if(data["status"] == '0')
        {
            $("#myonoffswitch").prop('checked', false);
        }
        else
        {
            $("#myonoffswitch").prop('checked', true);
        }
        $("#user_lastmodified").html(data["username"]);
        $("#user_lastmodifieddate").html(data["last_modified"]);
        $("#myonoffswitch").val(data["status"]);
    });
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
	<name><?php echo $user->first_name ." ". $user->last_name?></name><br>

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

</div>
<!--  <div style="clear:both"></div>-->





<div id="subcontent2" >

	
<div style=clear:both></div>


<?php $form = $this->beginWidget('CActiveForm', array(
   'id'=>'user-EditStudent-form', 'action'=> '/JobFair/index.php/Profile/editCompanyInfo',
   'enableAjaxValidation'=>false,
   'htmlOptions' => array('enctype' => 'multipart/form-data',),
)); ?>		
	<div class="companyinfo">
    <div class="titlebox">SETTINGS</div><br/><br/>
        <?php
        $checked = '';
        $job_notif = null;
        if(isset($user['job_notification']))
        {
            $job_notif = $user->job_notification;
            if($user->job_notification == '1')
            {
                $checked = 'checked';
            }
        }
        ?>
        <div style="overflow: hidden;">
            <div style="float: left;">Email Job Matches:</div>
            <div style="margin-left: 120px;" class="onoffswitch">
                <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" value='<?php echo $job_notif; ?>' id="myonoffswitch" <?php echo $checked; ?> onclick="toggleJobMatching()">
                <label class="onoffswitch-label" for="myonoffswitch">
                    <span class="onoffswitch-inner"></span>
                    <span class="onoffswitch-switch"></span>
                </label>
            </div>
        </div><hr>
	<div class="titlebox">COMPANY INFO</div>
	<p><a href="#" id="editcompany" class="editbox"><img src='/JobFair/images/ico/editButton.gif'/></a></p>
	<div style=clear:both></div>
	<name><?php echo $user->companyInfo->name?></name><br>

	<aboutme>
		<?php echo $form->textArea($user->companyInfo,'description',array('rows'=>3, 'cols'=>75, 'border'=>0, 'class'=>'ta','disabled'=>'true')); ?>
	</aboutme><br>
	
	<lab>WEBSITE:</lab> <?php echo $form->textField($user->companyInfo,'website', array('class'=>'tb5','disabled'=>'true')); ?>
	<lab>LOCATION:</lab> <?php echo $form->textField($user->companyInfo,'city', array('class'=>'tb5','disabled'=>'true')); ?><br>
	<lab>STATE:</lab> <?php echo $form->textField($user->companyInfo,'state', array('class'=>'tb5','disabled'=>'true')); ?>

	</div> <!--  END COMPANY INFO -->
	
<?php $this->endWidget(); ?>
	
	<div class="companyinfo" style="float:right!important; margin-top:-20px;">
	<div class="titlebox">COMPANY JOBS</div>
	<p><a href="/JobFair/index.php/job/post" id="postJob" class="editbox"><img src='/JobFair/images/ico/add.gif'"/></a></p>
	<br>
	<br>
	<?php foreach ($user->jobs as $job) {?>
		<jobtitle>
		<a href="/JobFair/index.php/job/view/jobid/<?php echo $job->id ?>"><?php echo $job->title?></a>
		</jobtitle>
		<div style="clear:both;"></div>
		<hr>
	<?php }?>
	</div> <!--  END COMPANY JOBS -->
	
	

	
	



<?php 
function formatDate($mysqldate){
	$time = strtotime($mysqldate);
	return date("F Y", $time);
}

?>





</div><!--  END CONTENT -->


</div><!--  END FULL CONTENT -->






