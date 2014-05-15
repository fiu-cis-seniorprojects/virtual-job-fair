<br/><br/><br/><br/>
<?php 
if (!isset($_GET['companyname'])) {
	$_GET['companyname'] = '';
}
$jobcount = count($jobs);
$rpp = 10; //results per page
$pages = round($jobcount / $rpp);
if ($pages == 0) $pages = 1;
?>
<script>
$(document).ready(function() {

	$('#type').val(getURLParameter("type").replace(/[+]/g, " "));
	
	$('.pageclick').click(function(e) {
		//alert('.page' + $(this).text().replace(" ", ""));
		
		$('tr').hide();
		$('#headerrow').show();
		$('.currentpageclick').attr('class', 'pageclick');
		$('.page' + $(this).text().replace(" ", "")).show();
		$(this).attr('class', 'currentpageclick');
	});
	$('#firstpage').attr('class', 'currentpageclick');
});

function getURLParameter(name) {
    return decodeURI(
        (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
    );
}
</script>

<div id="searchforjobs2" style="float:left;">
<div class="titlebox">Search</div>
<br/><br/>
<form method="GET">
<div>
<strong>Job Type:</strong>
<?php CHtml::dropDownList("type",'', array(''=>'Any', 'Internship'=>'Internship', 'Full+Time'=>'Full Time', 'Part+Time'=>'Part Time'))?>
<select name="type" id="type">
	<option value="">Any</option>
	<option value="Internship">Internship</option>
	<option value="Full Time">Full Time</option>
	<option value="Part Time">Part Time</option>
</select>
<strong>Company:</strong>
<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
    'name'=>'companyname',
	'id'=>'companyname',
	'source'=>CompanyInfo::getCompanyNames(),
	'value'=> $_GET['companyname'],
    'htmlOptions'=>array('value'=> $_GET['companyname'],),)); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array(
		    'label'=>'Search',
		    'type'=>'primary',
		    'htmlOptions'=>array(
		        'data-toggle'=>'modal',
		        'data-target'=>'#myModal',
				'style'=>'width: 120px;',
				'id' => "searchbutton",
		    	'style' => "margin-top: 5px; margin-bottom: 5px;width: 120px;",
		    	'onclick' =>'$(this).closest("form").submit();',
		    	
		    	),
			)); ?>
</div>

</form>
</div>
<div>


<div class="pages">
Page:
<?php for ($i = 0; $i < $pages; $i ++) {?>
<a class="pageclick"<?php if ($i == 0) echo "id='firstpage'"; ?>> <?php echo $i + 1;?></a>
<?php }?>
</div>
<table class="jobtable" style="margin-bottom:50px!important; float:left">
<tr id="headerrow">
	<th>Company</th>
	<th>Job Title</th>
	<th>Job Type</th>
	<th>Post Date</th>
	<th>Expiration Date</th>
</tr>
<?php $i = $rpp;foreach ($jobs as $job) {?>

<tr <?php echo "class='page" . (int) ($i / $rpp) . "'";?>
<?php
if ($i / $rpp >= 2){
	echo "style='display:none;'";
}
?>
>
<td>
<a href="/JobFair/index.php/profile/employer/user/<?php echo $job->fKPoster['username']; ?>">
<?php echo $job->fKPoster->companyInfo['name'];?></a>
</td>


<td>
<a href="/JobFair/index.php/job/view/jobid/<?php echo $job->id; ?>">
<?php echo $job->title; ?>
</a>
</td>
<td>
<?php echo $job->type; ?>
</td>
<td>
<?php  echo date('F j, Y', strtotime($job->post_date));?>
</td>
<td><?php echo date('F j, Y', strtotime($job->deadline)); ?></td>
</tr>

<?php if ($i % $rpp == 0) {?>

<?php }?>
<?php $i++;}?>
</table>

</div>
