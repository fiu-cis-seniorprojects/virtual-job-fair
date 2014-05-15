<?php
/* @var $this ProfileController */

$this->breadcrumbs=array(
	'Profile'=>array('/profile'),
	'Student',
);

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
<div id="fullcontent">
	<div id="leftside">
		<div  id="profileImage">
			<img style="width:200px; height:215px;" src="<?php echo $user->image_url ?>" />
		</div>
		<div style=clear:both>
		</div>

	
	</div><!-- END LEFT SIDE -->
	<div id="content">
		<div id="basicInfo">
			<div class="titlebox">BASIC INFO</div>
			<div style=clear:both></div>
			<name><?php echo $user->first_name ." ". $user->last_name?></name><br>
			<aboutme>
				<?php echo $basicInfo->about_me?>
			</aboutme><br>
			<lab>EMAIL:</lab> <?php echo $user->email; ?><br/>
			<lab>PHONE:</lab> <?php echo $basicInfo->phone; ?><br/>
			<lab>LOCATION:</lab> <?php echo $basicInfo->city . ', ' . $basicInfo->state; ?><br/>
		</div> <!-- END BASIC INFO -->
		
		<div id="companyInfo">
			<div class="titlebox">COMPANY INFO</div>
			<div style=clear:both></div>
			<name><?php echo $companyInfo->name ?></name><br>
		
			<aboutme>
				<?php echo $companyInfo->description;  ?>
			</aboutme><br>
			
			<lab>WEBSITE:</lab> <?php echo $companyInfo->website; ?> <br/>
			<lab>LOCATION:</lab> <?php echo $companyInfo->city . ', ' . $companyInfo->state; ?><br />
		
		</div> <!-- END COMPANY INFO -->
		<div id="basicInfo">
		<div class="titlebox">COMPANY JOBS</div>
			<?php foreach ($user->jobs as $job) {?>
				<jobtitle>
				<a href="/JobFair/index.php/job/view/jobid/<?php echo $job->id ?>"><?php echo $job->title?></a>
				</jobtitle>
			<?php }?>
		</div><!-- END COMPANY JOBS -->
		


	</div> <!--  END CONTENT -->
</div><!-- END FULL CONTENT -->
			


			