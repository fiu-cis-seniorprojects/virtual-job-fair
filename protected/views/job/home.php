<br/><br/><br/><br/>
<?php
$pages = 1;
if (!isset($_GET['companyname'])) {
	$_GET['companyname'] = '';
}
if (!isset($_GET['jobtitle'])) {
    $_GET['jobtitle'] = '';
}
if (!isset($_GET['skillname'])) {
    $_GET['skillname'] = '';
}

if(isset($job))
{
    $jobcount = count($jobs);
    $pages = round($jobcount / $rpp);
    if ($pages == 0) $pages = 1;
}

$rpp = 10; //results per page
?>
<script>
    function resetField()
    {
        // Reset the page(
        window.location = "/JobFair/index.php/job/home";
        // Reset fields ONLY
        /* $('#type').val('');
         $('#jobtitle').val('');
         $('#companyname').val('');
         $('#skillname').val(''); */

    }

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

<!-- ********** Refine Search *********** -->
<div id="searchforjobs2" style="float:left;">
<div class="titlebox">Refine Search</div>
<br/><br/>
<form method="GET" id="searchForm">
<div>
<!-- job type field -->
<strong>Job Type:</strong>
<?php CHtml::dropDownList("type",'', array(''=>'Any', 'Internship'=>'Internship', 'Full+Time'=>'Full Time', 'Part+Time'=>'Part Time',
                            'Co-op'=>'Co-op', 'Research'=>'Research'))?>
<select name="type" id="type">
	<option value="">Any</option>
	<option value="Internship">Internship</option>
	<option value="Full Time">Full Time</option>
	<option value="Part Time">Part Time</option>
    <option value="Co-op">Co-op</option>
    <option value="Research">Research</option>
</select>
<!-- title field -->
<strong>Title:</strong>
<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
     'name'=>'jobtitle',
     'id'=>'jobtitle',
     'source'=>Job::getJobTitle(),
     'value'=> $_GET['jobtitle'],
     'htmlOptions'=>array('value'=> $_GET['jobtitle'],),)); ?>
<!-- company field -->
<strong>Company:</strong>
<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
    'name'=>'companyname',
	'id'=>'companyname',
	'source'=>CompanyInfo::getCompanyNames(),
	'value'=> $_GET['companyname'],
    'htmlOptions'=>array('value'=> $_GET['companyname'],),)); ?>
<!-- skills field -->
<strong>Skills:</strong>
<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
     'name'=>'skillname',
     'id'=>'skillname',
     'source'=>Job::getJobBySkill(),
     'value'=> $_GET['skillname'],
     'htmlOptions'=>array('value'=> $_GET['skillname'],),)); ?>
<!-- search button -->
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
<!-- reset button -->
<?php $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType'=>'reset', 'label'=>'Reset',
    'htmlOptions'=>array(
        'data-toggle'=>'modal',
        'data-target'=>'#myModal',
        'style'=>'width: 120px;',
        'id' => "searchbutton",
        'style' => "margin-top: 5px; margin-bottom: 5px;width: 120px;",
        'onclick' =>'resetField()',
    ),)); ?>
</div>
</form>
</div>
<div>

<!-- ********** Search Result from Nav Bar ************** -->
    <?php if (isset($flag) && $flag == 1)   { ?>
        <div id="hardcorecontent">

            <?php if ($results != null & sizeof($results) > 0) {?>
                <h2>Jobs Matching your Search</h2>
                <table class="jobtable">
                    <thead>
                    <th>Title</th> <th>Company</th> <th>Type</th> <th>Posted</th> <th>Deadline</th> <th>Compensation</th> <th>Skills</th> <th>Location</th>
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
                            <td> coming soon </td>
                        </tr>
                    <?php } } ?>
                </table>
            <?php } else {?>
                <h3>No Job Matching your Search 2</h3>
            <?php }  ?>
        </div>
    <?php }
    else {?>
<!-- ******* Job Postings from Job Page *******  -->
  <div class="pages">
    <?php if ($jobs != null & sizeof($jobs) > 0) {?>
    Job Postings Page:
    <?php for ($i = 0; $i < $pages; $i ++) {?>
    <a class="pageclick"<?php if ($i == 0) echo "id='firstpage'"; ?>> <?php echo $i + 1;?></a>
    <?php }?>
    </div>
    <table class="jobtable"">
    <head><th>Title</th> <th>Company</th> <th>Job Type</th>  <th>Post Date</th> <th>Expiration Date</th>  <th>Compensation</th> <th>Skills</th></head>
    <tr>
    <?php $i = $rpp;foreach ($jobs as $job) {?>

        <td><a href="/JobFair/index.php/job/view/jobid/<?php echo $job->id;?>"><?php echo $job->title;?></a></td>
        <td><a href="/JobFair/index.php/profile/employer/user/<?php echo User::model()->findByAttributes(array('id'=>$job->FK_poster))->username;?>"><?php echo CompanyInfo::model()->findByAttributes(array('FK_userid'=>$job->FK_poster))->name;?></a></td>
        <td><?php echo $job->type;?></td>
        <td><?php echo Yii::app()->dateFormatter->format('MM/dd/yyyy', $job->post_date);?></td>
        <td><?php echo Yii::app()->dateFormatter->format('MM/dd/yyyy', $job->deadline);?></td>
        <td><?php echo $job->compensation;?></td>
        <td><?php $temp = JobSkillMap::model()->findAllByAttributes(array('jobid'=>$job->id));?>
            <?php foreach ($temp as $one){
                echo Skillset::model()->findByAttributes(array('id'=>$one->skillid))->name.' ';
            }?></td>
    </tr>
        <?php }  ?>
    </table>
    <?php } else {?>
        <h3>No Job Matching your Search 1</h3>
    <?php }?>

    </div>
<?php }?>



