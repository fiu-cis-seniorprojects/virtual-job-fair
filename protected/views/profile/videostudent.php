	<script>
	$(document).ready(function() {
		
		   $('.navbar-fixed-top').hide();
		   $('body').css('background', 'none');
		   $('#content').css('margin-left','-30px');
		   $('#fullcontent').css('width','800');
		   $('#resume').css('margin-bottom','20px');
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
	$resume = $user->resume;
	
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
        <li id="tabHeader_2">Resume</li>
      </ul>
    </div>
    <div id="tabsContent">
      <div class="tabpage" id="tabpage_1">
      
      
	<div id="subcontent">
	
		<div id="basicInfo" style="margin-top:30px; width:400px!important; min-height:150px">
			<div style=clear:both></div>
			<name><?php echo $user->first_name ." ". $user->last_name?></name><br>
			<aboutme>
				<?php echo $basicInfo->about_me?>
			</aboutme><br>
			<lab>EMAIL:</lab> <?php echo $user->email; ?><br/>
			<lab>PHONE:</lab> <?php echo $basicInfo->phone; ?><br/>
			<lab>LOCATION:</lab> <?php echo $basicInfo->city . ', ' . $basicInfo->state; ?><br/>
		</div> <!-- END BASIC INFO -->
		
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
		</div> <!-- END EDUCATION --> <!-- END EDUCATION -->
		
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
				<lab>End:</lab><?php echo formatDate($experience->enddate); ?><br />
				<lab>Employer:</lab><?php echo $experience->company_name ?><br />
				</div>
				
				
				<?php 
				}
			?>
	
		</div><!-- END EXPERIENCE -->


	</div> <!--  END CONTENT -->
	
	
	<div id="leftside">


			<div id="skills" style="margin-top:10px">
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
	
	
      
      </div>
      <div class="tabpage" id="tabpage_2">
      
     	
			<?php 
			$link="";
			if($resume != null){
			$link = 'http://'.Yii::app()->request->getServerName().'/' . $resume->resume;
			}
			?>
				<iframe src="http://docs.google.com/gview?url=<?php echo $link ?>&embedded=true" style="width:718px; height:700px;" frameborder="0"></iframe>
		
				
			
			
		
      
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

