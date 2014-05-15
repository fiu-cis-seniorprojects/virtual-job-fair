	<script>
	$(document).ready(function() {
		
		   $('.navbar-fixed-top').hide();
		   $('body').css('background', 'none');
		   $('#content').css('margin-left','-30px');
		   $('#fullcontent').css('width','800');
		   $('#subcontent').css('margin-left','-10px');
		   $('#tabContainer').css('width','770px');
		   $('#tabContainer').css('margin-left','-60px');
		   $('#tabContainer').css('background-color','transparent');
		   $('#skills').css('margin-top','180px');
		   $('#tabContent').css('padding','0px');
		   $('#leftside').css('width','50px');
	
		});
	
	</script>
	
	
	
<?php
/* @var $this ProfileController */

if(!(Yii::app()->user->isGuest)){

	$basicInfo = $user->basicInfo;
	$companyInfo = $user->companyInfo;
	
	if ($basicInfo == null){
		$basicInfo = new BasicInfo;
	}
	
	function formatDate($date) {
		$time = strtotime($date);
		return date("F Y", $time);
	}
	?>

	<div class="matchprofile">
<div id="tabContainer">
    <div class="tabs">
      <ul>
        <li id="tabHeader_1">Profile</li>
      </ul>
    </div>
    <div id="tabsContent">
      <div class="tabpage" id="tabpage_1">
      
      
	<div id="subcontent">

		<div id="basicInfo" style="margin-top:30px; width:400px!important; min-height:150px">
				<?php $form = $this->beginWidget('CActiveForm', array(
   'id'=>'user-EditStudent-form', 'action'=> '/JobFair/index.php/Profile/EditBasicInfo',
   'enableAjaxValidation'=>false,
   'htmlOptions' => array('enctype' => 'multipart/form-data',),
)); ?>
			<div style=clear:both></div>
			<name><?php echo $user->first_name ." ". $user->last_name?></name><br>
			<aboutme>
				<?php echo $form->textArea($user->basicInfo,'about_me', array('style'=>'width: 700px; height: 120px!important;','rows'=>3, 'cols'=>75, 'border'=>0, 'class'=>'ta','disabled'=>'true')); ?>
			</aboutme><br>
			<lab>EMAIL:</lab> <?php echo $user->email; ?><br/>
			<lab>PHONE:</lab> <?php echo $basicInfo->phone; ?><br/>
			<lab>LOCATION:</lab> <?php echo $basicInfo->city . ', ' . $basicInfo->state; ?><br/>
					<?php $this->endWidget(); ?>
		</div> <!-- END BASIC INFO -->

	<div id="education">
			<div class="titlebox">COMPANY INFO</div>	
			<div style=clear:both></div>
			
			<name><?php echo $user->companyInfo->name?></name><br>
		
			<aboutme>
				<?php echo $user->companyInfo->description; ?>
			</aboutme><br>
			
			<lab>WEBSITE:</lab> <?php echo $user->companyInfo->website; ?><br>
			<lab>LOCATION:</lab> <?php echo $user->companyInfo->city; ?><br>
			<lab>STATE:</lab> <?php echo $user->companyInfo->state; ?>
	</div> <!-- END EDUCATION --> <!-- END EDUCATION -->
		
		<div id="experience">
			<div class="titlebox">COMPANY JOB</div>		
			<div style=clear:both></div>
			<br>
			<br>
			<?php foreach ($user->jobs as $job) {?>
				<jobtitle>
				<a href="/JobFair/index.php/job/view/jobid/<?php echo $job->id ?>" target="_blank"><?php echo $job->title?></a>
				</jobtitle>
				<div style="clear:both;"></div>
				<hr>
			<?php }?>
	
		</div><!-- END EXPERIENCE -->


	</div> <!--  END CONTENT -->
	
	
	<div id="leftside">


	</div>
	
	
      
      </div>

    </div>
</div>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/tabs.js"></script>
</div>

	




	
	
	
				
<?php 
}
else{
?> <script>alert("You do not have access to view this")
		window.location = "http://www.google.com/"
		</script><div style="margin: 100px;"> <?php 

echo "You do not have access to view this"; ?> </div>
<?php 
 }
?>	

