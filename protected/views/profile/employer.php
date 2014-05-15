<?php
/* @var $this ProfileController */

$this->breadcrumbs=array(
	'Profile'=>array('/profile'),
	'View',
);

$basicInfo = $user->basicInfo;

$companyInfo = $user->companyInfo;

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
		<div style="clear:both"></div>
</div> <!--  END BASIC INFO -->
	
	<div style="clear:both"></div>
	<hr>


<div id="leftside">

<div id="subcontent2" >
<div style=clear:both></div>		
	<div class="companyinfo">
	<div class="titlebox">COMPANY INFO</div>
	<div style=clear:both></div>
	<name><?php echo $user->companyInfo->name?></name><br>

	<aboutme>
		<?php echo $user->companyInfo->description; ?>
	</aboutme><br>
	
	<lab>WEBSITE:</lab> <?php echo $user->companyInfo->website; ?><br>
	<lab>LOCATION:</lab> <?php echo $user->companyInfo->city; ?><br>
	<lab>STATE:</lab> <?php echo $user->companyInfo->state; ?>

	</div> <!--  END COMPANY INFO -->

	
	<div class="companyinfo" style="float:right!important;">
	<div class="titlebox">COMPANY JOBS</div>
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
	
	</div>

</div>


</div><!--  END FULL CONTENT -->






