<?php
/* @var $this HomeController */

$this->breadcrumbs=array(
	'Home'=>array('/home'),
	'Home',
);
?>


<script type="text/javascript">
$(function() {
	var x;
		setInterval(function() {
				if(x == 0) {
					$('.blinking').removeAttr('style');
					x = 1;
				} else  {
					if(x = 1) {
						$('.blinking').css('color', 'red');
						x = 0;
					}
				}
		}, 500);
});
</script>

<div id="fullcontent" style="width:1200px;">

<div id="leftside3" style="border-right: 1px solid rgb(228, 228, 228);min-height: 380px;">


<h1><?php echo 'Welcome to Virtual Job Fair' ?></h1> <br>
<h3 style="margin: -10px 0 0 10px"><i><?php echo  $user->first_name ." ". $user->last_name  ?></i></h3>
<div style="clear:both"></div>
<hr>

		<div class="hometitle" style="width:740px; ">JOB POSTS | <a style="color:white; font-weight:bold" href="http://<?php echo Yii::app()->request->getServerName(); ?>/JobFair/index.php/job/post">Post New Job</a>
        </div>
		<div style="clear:both"></div>
		<div id="list4">
		

		
		<ul id="jobs">
		
			<?php foreach ($user->jobs as $job) {?>
				
							<?php  $countapp = 0;
							$apps= $job->applications;
							//print "<pre>"; print_r($apps);print "</pre>";return;
							if($apps != null){
								foreach ($apps as $app) {
									$auser= User::model()->findByAttributes(array('id' => $app->userid));
									$countapp++;
								}
							}
							?>
				
				  <li class="eachjob">
				    <span class="menuBox"><a href="#" class="ajob" ><?php echo $job->title?> <span style="float:right; font-size: 10pt;
margin-right: 10px;
color: rgb(129, 126, 126);">Applied: <?php echo $countapp?> candidates</span></a></span>
				    <div class="jobinfo" id="jobinfo">
				    			<?php $form = $this->beginWidget('CActiveForm', array(
						  'id'=>'job-post-form', 'action'=> '/JobFair/index.php/Job/EditJobPost',
						   'enableAjaxValidation'=>false,
						   'htmlOptions' => array('enctype' => 'multipart/form-data',),
						)); ?>
	
				    	<p >Applicants: <br>	
							<?php
							$apps= $job->applications;
							//print "<pre>"; print_r($apps);print "</pre>";return;
							if($apps != null){
							?><div style="height: 80px;
							padding: 6px;
							font-size: 10pt;
							overflow-y: auto;
							display: block !important;
							background-color: rgb(245, 245, 245);"><?php 
							foreach ($apps as $app) {
							$auser= User::model()->findByAttributes(array('id' => $app->userid));
							?>
							<a class="applicants" href="/JobFair/index.php/profile/student/user/<?php echo $auser->username;?>" target="_blank" > <img src='/JobFair/images/imgs/user-default.png' height="20" width="20"/> <?php echo $auser->first_name . " " . $auser->last_name?></a>
							
							<?php 
							}
							?></div>
							<?php }
							?>
							
						<div style="clear:both"></div>
						<hr>
					<div style="display:block!important; float:left">
					 <?php echo $form->textField($job,'id',array('style'=>'display: none;')); ?><br>	
					<lab2>TITLE:</lab2> <?php echo $form->textField($job,'title'); ?><br>	
					<lab2>DEADLINE:</lab2> <?php echo $form->textField($job,'deadline'); ?><br>	
					<lab2>TYPE:</lab2> <?php echo $form->textField($job,'type'); ?><br>
					<lab2>COMPENSATION:</lab2> <?php echo $form->textField($job,'compensation'); ?><br>
					</div>
					<div style="display:block!important; float:left; margin: 15px;">
					<lab2>DESCRIPTION:</lab2> <br><?php echo $form->textArea($job,'description',array('rows'=>6, 'cols'=>40, 'style'=>'margin: 10px;')); ?><br>
					<div style="clear:both;display:block!important "></div>
					<p style="margin-left:-330px;">SKILLS:<?php foreach ($job->jobSkillMaps as $jobskill) {?>
							<?php echo $jobskill->skill->name; ?>
						<?php } ?>
					</p>
					</div>
					<br>
				<div style="clear:both;display:block!important "></div>
				<div style="display:block!important; ">
				<?php echo CHtml::submitButton('Save', array("class"=>"btn btn-primary", "style"=>"float:left")); ?>			
					<?php $this->endWidget(); ?>
				<form target="blank" style="float:left; margin:0 5px 0 5px"action="/JobFair/index.php/job/view/jobid/<?php echo $job->id?>" >
				    <input class="btn btn-primary" type="submit"  value="Preview">
				</form>
				<form  target="blank" style="float:left;" action="/JobFair/index.php/job/studentmatch/jobid/<?php echo $job->id?>" >
				    <input class="btn btn-primary" type="submit" value="Matches">
				</form>
				<form style="float:left;margin:0 5px 0 5px" action="/JobFair/index.php/Job/Close/jobid/<?php echo $job->id?>" >			
				<?php if (($user != null) && ($job->FK_poster == $user->id)) {?>
					<?php if ($job->active) {?>
						  <input class="btn btn-primary" type="submit"  value="Close">
					<?php } else {?>
						<p>This posting is closed</p>
					<?php } ?>
				
				<?php }?>
				</form>
								
				
				</div > 
				<br>
				    </div >
				  </li>

			<?php }?>
			</ul>
			
			
<script>
$(document).ready(function(){
	//auto generate ids for all li
	$('#jobs li.eachjob').each(function(i,el){
	    el.id = i+1;
	});
	//auto generate ids for all a class .ajob
	$('.ajob').each(function(i,el){
	    el.id = i+1;
	});
	//auto generate ids for all div class .jobinfo	
	$('.jobinfo').each(function(i,el){
	    el.id = i+1;
	});	
	//Show Hide a div based on its ID and the ID of a you click
	  $('#jobs li.eachjob a').click(function(){
	    $('#jobs li.eachjob').children('div#'+this.id).toggle(); 
	  });
	});

	$('#jobs li.eachjob #jobinfo').hide();
</script>
			
			
			
			
				</div><!-- END COMPANY JOBS -->
		</div>





<div id="notificationside">

<div id="notificationemployer">
<div class="hometitle">NEWS AND UPDATES | <?php echo date_default_timezone_set('America/New_York'); date("D M d, Y "); ?> </div>
<br><br>




<script type="text/javascript">
//Video Interviews
$(function() {
$('div.one')
.css("cursor","pointer")
.click(function(){
$(this).siblings('.child-'+this.id).toggle();
});
$('div[class^=child-]').hide();
});
//Applicants
$(function() {
$('div.two')
.css("cursor","pointer")
.click(function(){
$(this).siblings('.child-'+this.id).toggle();
});
$('div2[class^=child-]').hide();
});
//Messages
$(function() {
$('div.three')
.css("cursor","pointer")
.click(function(){
$(this).siblings('.child-'+this.id).toggle();
});
$('div3[class^=child-]').hide();
});



</script>


<div class="one" id="div1" style="margin-bottom: 15px;
width: 280px;
cursor: pointer;
margin-left: 5px;
padding: 10px;
box-shadow: 1px 1px 10px 1px; border-radius:0 5px 5px 0;height: 28px;">
<a class="iconnotification"><img src='/JobFair/images/ico/videointer.png'/></a>
<span class="notificationtitle" >Video Interviews </span> <span class="notificationtitle" style="float: right!important"><?php if ($countvideo != 0){ echo $countvideo;} ?></span>
</div>
<div class="child-div1" >
<span class="subnotification">
<?php $count=0;?>
<?php foreach ($notification as $n) { ?>

<?php 
if ($n->been_read == 0 & $n->importancy == 4){


$id= 0;
if($n->keyid!=null){

	$id= $n->keyid;

	

}
 

?>
		
<div style="background-color:#dff0d8;border-radius: 5px;padding:10px;">
<p style="color:#468847"><a href=<?php echo $this->createUrl('VideoInterview/startInterview',array('view'=> $n->link,'notificationRead'=> $n->id,'usertype'=>$user->FK_usertype,'session'=> $id,'me'=>$user->username));         ?>><?php echo $n->message; ?></a>
<form style="float:right; margin:5px 5px 5px 5px; "action="/JobFair/index.php/Home/deleteNotification" >
<input type="hidden" name="id" value="<?php echo $n->id?>">
<input class="btn btn-primary" type="submit"  value="X" style="margin-top: -15px;
width: 20px;
height: 18px;
padding: 0px;
float: right;
margin-right: -15px;;">
</form>
</p>
</div>
<br>
<?php $count++;  }


elseif ($n->been_read != 0 & $n->importancy == 4){

$id= 0;

if($n->keyid!=null){

	$id= $n->keyid;

	

}
//print "<pre>"; print_r($n->keyid);print "</pre>";return;


?>
<div style="background-color:rgb(240, 241, 241);border-radius:5px;padding:10px;">
<p style="color:#468847"><a href=<?php echo $this->createUrl('VideoInterview/startInterview',array('view'=> $n->link,'notificationRead'=> $n->id,'usertype'=>$user->FK_usertype,'session'=> $id,'me'=>$user->username));         ?>><?php echo $n->message; ?></a>
<form style="float:right; margin:5px 5px 5px 5px; "action="/JobFair/index.php/Home/deleteNotification" >
<input type="hidden" name="id" value="<?php echo $n->id?>">
<input class="btn btn-primary" type="submit"  value="X" style="margin-top: -15px; width: 20px; height: 18px; padding: 0px; float: right; margin-right: -15px;">
</form></p>
</div>
<br>
<?php $count++;}

}?>
</span>
</div>

<div class="two" id="div2"  style="margin-bottom: 15px;
width: 280px;
cursor: pointer;
margin-left: 5px;
padding: 10px;
box-shadow: 1px 1px 10px 1px; border-radius:0 5px 5px 0;height: 28px;">
<a class="iconnotification"><img src='/JobFair/images/ico/applicant.png'/></a>
<span class="notificationtitle" >Applicants </span><span class="notificationtitle" style="float: right!important"><?php if ($countapplicants != 0){ echo $countapplicants;} ?></span>
</div>
<div class="child-div2" >
<span class="subnotification">
<?php $count=0;?>
<?php foreach ($notification as $n) { ?>

<?php 
if ($n->been_read == 0 & $n->importancy == 6){?>
<div style="background-color:#dff0d8;border-radius: 5px;padding:10px;">
<p style="color:#468847"><a href="<?php echo $n->link."?notificationRead=".$n->id; ?>"><?php echo $n->message; ?></a>
<form style="float:right; margin:5px 5px 5px 5px; "action="/JobFair/index.php/Home/deleteNotification" >
<input type="hidden" name="id" value="<?php echo $n->id?>">
<input class="btn btn-primary" type="submit"  value="X" style="margin-top: -15px; width: 20px; height: 18px; padding: 0px; float: right; margin-right: -15px;">
</form></p>
</div>
<br>
<?php $count++;  }


elseif ($n->been_read != 0 & $n->importancy == 6){?>
<div style="background-color:rgb(240, 241, 241);border-radius:5px;padding:10px;">
<p style="color:#468847"><a href="<?php echo  $n->link."?t=1&notificationRead=".$n->id; ?>"><?php echo $n->message; ?></a>
<form style="float:right; margin:5px 5px 5px 5px; "action="/JobFair/index.php/Home/deleteNotification" >
<input type="hidden" name="id" value="<?php echo $n->id?>">
<input class="btn btn-primary" type="submit"  value="X" style="margin-top: -15px; width: 20px; height: 18px; padding: 0px; float: right; margin-right: -15px;">
</form></p>
</div>
<br>




<?php $count++;}

}?>
</span>
</div>

<div class="three" id="div3"  style="margin-bottom: 15px;
width: 280px;
cursor: pointer;
margin-left: 5px;
padding: 10px;
box-shadow: 1px 1px 10px 1px; border-radius:0 5px 5px 0;height: 28px;">
<a class="iconnotification"><img src='/JobFair/images/ico/message.png'/></a>
<span class="notificationtitle" >Messages</span><span class="notificationtitle" style="float: right!important"><?php if ($countmessages != 0){ echo $countmessages;} ?></span>
</div>
<div class="child-div3" >
<span class="subnotification">
<?php $count=0;?>
<?php foreach ($notification as $n) { ?>

<?php 
if ($n->been_read == 0 & $n->importancy == 3 & $count < 6){?>
<div style="background-color:#dff0d8;border-radius: 5px;padding:10px;">
<p style="color:#468847"><a href="<?php echo $n->link."?notificationRead=".$n->id; ?>"><?php echo $n->message; ?></a>
<form style="float:right; margin:5px 5px 5px 5px; "action="/JobFair/index.php/Home/deleteNotification" >
<input type="hidden" name="id" value="<?php echo $n->id?>">
<input class="btn btn-primary" type="submit"  value="X" style="margin-top: -15px; width: 20px; height: 18px; padding: 0px; float: right; margin-right: -15px;">
</form></p>
</div>
<br>

<?php $count++;  }


elseif ($n->been_read != 0 & $n->importancy == 3 & $count < 6){?>

<div style="background-color:rgb(240, 241, 241);border-radius:5px;padding:10px;">
<p style="color:#468847"><a href="<?php echo  $n->link."?t=1&notificationRead=".$n->id; ?>"><?php echo $n->message; ?></a>
<form style="float:right; margin:5px 5px 5px 5px; "action="/JobFair/index.php/Home/deleteNotification" >
<input type="hidden" name="id" value="<?php echo $n->id?>">
<input class="btn btn-primary" type="submit"  value="X" style="margin-top: -15px; width: 20px; height: 18px; padding: 0px; float: right; margin-right: -15px;">
</form></p>
</div>
<br>

<?php $count++;}

}?>
</span>

</div>






</div>

</div>

</div>

