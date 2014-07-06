<br>
<br>
<br>
<!--NOT NEEDED -->
<div id="hardcorecontent">

<?php if ($results != null & sizeof($results) > 0) {?>
<h2>Search Results</h2>
<table class="jobtable">
<thead>
<th>Description</th> <th>Company</th> <th>Type</th> <th>Posted</th> <th>Deadline</th> <th>Compensation</th> <th>Skills</th>
</thead>
<?php foreach ($results as $js){ if ($js != null){?>
<tr>
	<td><a href="/JobFair/index.php/job/view/jobid/<?php echo $js->id;?>"><?php echo $js->title;?></a></td>
	<td><a href="/JobFair/index.php/profile/employer/user/<?php echo User::model()->findByAttributes(array('id'=>$js->FK_poster))->username;?>"><?php echo CompanyInfo::model()->findByAttributes(array('FK_userid'=>$js->FK_poster))->name;?></a></td>
	<td><?php echo $js->type;?></td>
	<td><?php echo Yii::app()->dateFormatter->format('MM/dd/yyyy', $js->post_date);?></td>
	<td><?php echo Yii::app()->dateFormatter->format('MM/dd/yyyy', $js->deadline);?></td>
	<td><?php echo $js->compensation;?></td>
	<td><?php $temp = JobSkillMap::model()->findAllByAttributes(array('jobid'=>$js->id));?>
	<?php foreach ($temp as $one){
		echo Skillset::model()->findByAttributes(array('id'=>$one->skillid))->name.' ';
	}?></td>
</tr>
<?php } } ?>
</table>
<?php } else {?>
<h3>No Results</h3>
<?php }?>

</div>