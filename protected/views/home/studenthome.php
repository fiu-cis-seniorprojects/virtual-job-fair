<?php
/* @var $this HomeController */

/* Student Dashboard */

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

<?php 
$jobs = Job::getMatchingJobs();
if ($jobs == null) $jobs = array();
?>
<div id="yourmatch" >
<div class="titlebox">MATCHING JOBS</div>	
<br/><br/>
	<?php foreach($jobs as $job) {?>
	<a class="mostwantedskills" href="/JobFair/index.php/job/view/jobid/<?php echo $job->id  ?>"><?php echo $job->title; ?></a>
	<br/>
	<?php }?>
</div>	
<div id="mostwanted">
<div class="titlebox">MOST WANTED SKILLS</div><br><br>
<ul>
<?php foreach ($mostwanted as $mmm){?>
	<li class="mostwantedskills"><?php echo $mmm->name;
	$thecount = 0;
	
	/*$criteria1= new CDbCriteria();
	$criteria1=array(
			'select'=>'count(*) as count',
			'condition'=>" skillid=".$mmm->id."",
	);*/

	$sk = JobSkillMap::model()->findAllByAttributes(array('skillid'=>$mmm->id));
    foreach($sk as $sk2)
    {
        $thejob = Job::model()->findByPk($sk2->jobid);


        if($thejob->active)
        {
            $thecount++;
        }
    }
        ?>
        <a class="mostwantedtext" href="/JobFair/index.php/home/Search2/?key=<?php echo $mmm->name  ?>"><?php  echo " - [ " . $thecount . " ] Jobs";?></a>
    </li>
<?php }?></ul>

	<br>
</div>	



</div>
<div id="notificationside">

<div id="notificationemployer">
<div class="hometitle"><?php echo date_default_timezone_set("America/New_York"); date("D M d, Y "); ?> | News and Updates</div>
<br><br>


<!--
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script> 
-->
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
//Matching Candidates
$(function() {
$('div.four')
.css("cursor","pointer")
.click(function(){
$(this).siblings('.child-'+this.id).toggle();
});
$('div4[class^=child-]').hide();
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
	//print "<pre>"; print_r($key->notification_id);print "</pre>";return;
}
//print "<pre>"; print_r($n->keyid);print "</pre>";return;

?>

<div style="background-color:#dff0d8;border-radius: 5px;padding:20px;">
<p style="color:#468847"><a ><?php echo $n->message; ?></a></p>
<br>
<form style="float:right; margin:5px 5px 5px 5px; "action="/JobFair/index.php/Home/AcceptNotificationSchedualInterview" >
<input type="hidden" name="id" value="<?php echo $n->id?>">
<input class="btn btn-primary" type="submit"  value="Accept" style="margin-top: -15px;
float: left;
margin-left: -265px;">
</form>


<form style="float:right; margin:5px 5px 5px 5px; "action="/JobFair/index.php/Home/deleteNotification" >
<input type="hidden" name="id" value="<?php echo $n->id?>">
<input class="btn btn-primary" type="submit"  value="X" style="margin-top: -5px;
width: 20px;
height: 18px;
padding: 0px;
float: right;
margin-right: -34px;">
</form>





</div>

<br>
<?php $count++;  }


elseif ($n->been_read != 0 & $n->importancy == 4){
$id= 0;
if($n->keyid!=null){
	$id= $n->keyid;
	//print "<pre>"; print_r($key->notification_id);print "</pre>";return;
}


?>

<div style="background-color:rgb(240, 241, 241);border-radius:5px;padding:10px;">
<p style="color:#468847"><a href= <?php echo $this->createUrl('VideoInterview/startInterview',array('view'=> $n->link,'notificationRead'=> $n->id,'usertype'=>$user->FK_usertype,'session'=> $id,'me'=>$user->username));         ?>><?php echo $n->message; ?></a></p>
<form style="float:right; margin:5px 5px 5px 5px; "action="/JobFair/index.php/Home/deleteNotification" >
<input type="hidden" name="id" value="<?php echo $n->id?>">
<input class="btn btn-primary" type="submit"  value="X" style="margin-top: -15px;
width: 20px;
height: 18px;
padding: 0px;
float: right;
margin-right: -15px;;">
</form>

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
<span class="notificationtitle" >Matching Jobs</span><span class="notificationtitle" style="float: right!important"><?php if ($countmachingjobs != 0){ echo $countmachingjobs;} ?></span>
</div>
<div class="child-div2" >
<span class="subnotification">
<?php $count=0;?>
<?php foreach ($notification as $n) { ?>

<?php 
if ($n->been_read == 0 & $n->importancy == 2){?>
<div style="background-color:#dff0d8;border-radius: 5px;padding:10px;">
<p style="color:#468847"><a href="<?php echo $n->link."?notificationRead=".$n->id; ?>"><?php echo $n->message; ?></a>
<form style="float:right; margin:5px 5px 5px 5px; "action="/JobFair/index.php/Home/deleteNotification" >
<input type="hidden" name="id" value="<?php echo $n->id?>">
<input class="btn btn-primary" type="submit"  value="X" style="margin-top: -15px;
width: 20px;
height: 18px;
padding: 0px;
float: right;
margin-right: -15px;;">
</form></p>
</div>
<br>
<?php $count++;  }


elseif ($n->been_read != 0 & $n->importancy == 2){?>
<div style="background-color:rgb(240, 241, 241);border-radius:5px;padding:10px;">
<p style="color:#468847"><a href="<?php echo  $n->link."?t=1&notificationRead=".$n->id; ?>"><?php echo $n->message; ?></a>
<form style="float:right; margin:5px 5px 5px 5px; "action="/JobFair/index.php/Home/deleteNotification" >
<input type="hidden" name="id" value="<?php echo $n->id?>">
<input class="btn btn-primary" type="submit"  value="X" style="margin-top: -15px;
width: 20px;
height: 18px;
padding: 0px;
float: right;
margin-right: -15px;;">
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
<input class="btn btn-primary" type="submit"  value="X" style="margin-top: -15px;
width: 20px;
height: 18px;
padding: 0px;
float: right;
margin-right: -15px;;">
</form></p>
</div>
<br>

<?php $count++;  }


elseif ($n->been_read != 0 & $n->importancy == 3 & $count < 6){?>

<div style="background-color:rgb(240, 241, 241);border-radius:5px;padding:10px;">
<p style="color:#468847"><a href="<?php echo  $n->link."?t=1&notificationRead=".$n->id; ?>"><?php echo $n->message; ?></a>
<form style="float:right; margin:5px 5px 5px 5px; "action="/JobFair/index.php/Home/deleteNotification" >
<input type="hidden" name="id" value="<?php echo $n->id?>">
<input class="btn btn-primary" type="submit"  value="X" style="margin-top: -15px;
width: 20px;
height: 18px;
padding: 0px;
float: right;
margin-right: -15px;;">
</form></p>
</div>
<br>

<?php $count++;}

}?>
</span>

</div>

<div class="four" id="div4"  style="margin-bottom: 15px;
width: 280px;
cursor: pointer;
margin-left: 5px;
padding: 10px;
box-shadow: 1px 1px 10px 1px; border-radius:0 5px 5px 0;height: 28px;">
<a class="iconnotification"><img src='/JobFair/images/ico/match.png'/></a>
<span class="notificationtitle" >Misc</span><span class="notificationtitle" style="float: right!important"><?php  if ($countmisc != 0){ echo $countmisc;} ?></span>
</div>
<div class="child-div4" >
<span class="subnotification">
<?php $count=0;?>
<?php foreach ($notification as $n) { ?>

<?php 
if ($n->been_read == 0 & $n->importancy == 1 & $count < 10){?>

<div style="background-color:#dff0d8;border-radius: 5px;padding:10px;">
<p style="color:#468847"><a href="<?php echo $n->link."?notificationRead=".$n->id; ?>"><?php echo $n->message; ?></a>
<form style="float:right; margin:5px 5px 5px 5px; "action="/JobFair/index.php/Home/deleteNotification" >
<input type="hidden" name="id" value="<?php echo $n->id?>">
<input class="btn btn-primary" type="submit"  value="X" style="margin-top: -15px;
width: 20px;
height: 18px;
padding: 0px;
float: right;
margin-right: -15px;;">
</form></p>
</div>
<br>
<?php $count++;  }

elseif ($n->been_read != 0 & $n->importancy == 1 & $count < 10){?>

<div style="background-color:rgb(240, 241, 241);border-radius:5px;padding:10px;">
<p style="color:#468847"><a href="<?php echo $n->link."?notificationRead=".$n->id; ?>"><?php echo $n->message; ?></a>
<form style="float:right; margin:5px 5px 5px 5px; "action="/JobFair/index.php/Home/deleteNotification" >
<input type="hidden" name="id" value="<?php echo $n->id?>">
<input class="btn btn-primary" type="submit"  value="X" style="margin-top: -15px;
width: 20px;
height: 18px;
padding: 0px;
float: right;
margin-right: -15px;;">
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
