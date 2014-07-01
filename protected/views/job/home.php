<br/><br/><br/><br/>
<?php
$pages = 0;
$size = 0;
$loValue = false;
settype($size, "integer");
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
    if(isset($result))
    {
        $jobcount += count($result);
    }
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
    }

    $(document).on('click', '#radioOption', create);
    $clicked = 0;

    function create(e)
    {
        if($clicked < 1){
            var input = $('<input />',{
                id: "location",
                type:"text",
                name:"city",
                value: "Miami, Florida"
            });

            $(this).after(input);
            $clicked++;
        }

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

<?php

// get types from database
$unique_types = Job::model()->findAllBySql('SELECT DISTINCT `type` FROM `job`');
$job_types = array('' => 'Any');
if (isset($unique_types) && !is_null($unique_types))
{
    foreach ($unique_types as $cur_type)
    {
        $cur_type_str = $cur_type['type'];
        $job_types[$cur_type_str] = $cur_type_str;
    }
}

echo CHtml::dropDownList("type",'', $job_types);
?>
<!--<select name="type" id="type">-->
<!--	<option value="">Any</option>-->
<!--	<option value="Internship">Internship</option>-->
<!--	<option value="Full Time">Full Time</option>-->
<!--	<option value="Part Time">Part Time</option>-->
<!--    <option value="Co-op">Co-op</option>-->
<!--    <option value="Research">Research</option>-->
<!--</select>-->
<!-- title field -->
<strong>Position:</strong>
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
<!-- outside resources radio button -->
<div class="radio">
    <label>
        Include jobs from outside sources <input type="radio" name="radioOption" id="radioOption" value="true">
    </label>
</div>
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
                Jobs Matching your Search
                <table class="jobtable">
                    <thead>
                    <th>Position</th> <th>Company</th> <th>Type</th> <th>Posted</th> <th>Deadline</th> <th>Compensation</th> <th>Skills</th>
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
                <h3>No Job Matching your Search</h3>
            <?php }  ?>
        </div>
    <?php }
  else if (isset($flag) && $flag == 0) {?>
<!-- ******* Job Postings from Job Page using only Career Path *******  -->
  <div class="pages">
    <?php if ($jobs != null & sizeof($jobs) > 0) {?>
    Job Postings Page:
    <?php for ($i = 0; $i < $pages; $i ++) {?>
    <a class="pageclick"<?php if ($i == 0) echo "id='firstpage'"; ?>> <?php echo $i + 1;?></a>
    <?php }?>
    </div>
    <table class="jobtable"">
    <head><th>Position</th> <th>Company</th> <th>Job Type</th>  <th>Post Date</th> <th>Expiration Date</th>  <th>Compensation</th> <th>Skills</th></head>
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
        <h3>No Job Matching your Search</h3>
    <?php }?>

    </div>
<?php }
else if (isset($flag) && $flag == 2) { ?>
    <!-- ******* Job Postings from Job Page using external sources & Career Path *******  -->
    <div class="pages">
    <table class="jobtable">
        Job Postings Page:
        <?php for ($i = 0; $i < $pages; $i ++) {?>
            <a class="pageclick"<?php if ($i == 0) echo "id='firstpage'"; ?>> <?php echo $i + 1;?></a>
        <?php }?>
    <head><th>Position</th> <th>Company</th> <th>Job Type</th>  <th>Post Date</th> <th>Expiration Date</th>  <th>Compensation</th> <th>Skills</th></head>
    <?php  if($jobs != null & sizeof($jobs) > 0) { ?>
    </div>
    <?php foreach ($jobs as $job) {?>
        <tr>
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
<?php } ?>
    <?php if ($result != null & sizeof($result) > 0) { $size = $result['end'];  ?>
        <tr>
            <?php  for($i=0;$i<$size-1;$i++){
                $results=$result['results'];
                ?>
                <td><a href=<?php echo $results['result'][$i]['url']; ?>>
                   <?php if($results['result'][$i]['jobtitle'] != null) {echo $results['result'][$i]['jobtitle'];}
                            else {echo "N/A";}?></a></td>
                <td><?php if($results['result'][$i]['company'] != null) { echo $results['result'][$i]['company'];}
                            else {echo "N/A";}?></a></td>
                <td>Not provided</td>
                <td><?php if($results['result'][$i]['date'] != null) {echo $results['result'][$i]['date'];}
                            else {echo "N/A";} ?></td>
                <td>Not provided</td>
                <td>Not provided</td>
                <td><?php if($results['result'][$i]['snippet'] != null) {echo $results['result'][$i]['snippet'];}
                            else {echo "N/A";} ?></td>
                </tr>
            <?php } ?>
    </table> <?php }
    else {?>
        <h3>No Jobs Matching your Search</h3>
    <?php }?>

    </div>

<?php } ?>