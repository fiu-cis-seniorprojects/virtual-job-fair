<br/><br/><br/><br/>
<?php
$pages = 0;
$size = 0;
$loValue = false;
settype($size, "integer");
if (!isset($_GET['allWords'])) {
	$_GET['allWords'] = '';
}
if (!isset($_GET['phrase'])) {
    $_GET['phrase'] = '';
}
if (!isset($_GET['anyWord'])) {
    $_GET['anyWord'] = '';
}
if (!isset($_GET['minus'])) {
    $_GET['minus'] = '';
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
            $(input).after('<br>');
            $clicked++;
        }

    }

    function myFunction()
    {
        document.getElementById("searchForm").submit();
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

<!-- ********** Advance Search *********** -->
<div id="searchforjobs2" style="float:left;">
<div class="titlebox">Advanced Search  </div>
    <br/><br>
    <form method="GET" id="searchForm" action="/JobFair/index.php/job/home">
      <h4>Find jobs with... </h4>
        <div>
            <strong>these words:</strong>
            <?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
                'name'=>'allWords',
                'id'=>'allWords',
                'value'=> $_GET['allWords'],
                'htmlOptions'=>array('value'=> $_GET['allWords'],'placeholder' => 'put plus sign before word',
                    'style'=>'width: 220px;'),)); ?>
            <br> <strong>this exact word or phrase:</strong><br>
            <?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
                'name'=>'phrase',
                'id'=>'phrase',
                'value'=> $_GET['phrase'],
                'htmlOptions'=>array('value'=> $_GET['phrase'],'placeholder' => 'put word or phrase in quotes',
                    'style'=>'width: 220px;'),)); ?>
            <br> <strong>any of these words:</strong><br>
            <?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
                'name'=>'anyWord',
                'id'=>'anyWord',
                'value'=> $_GET['anyWord'],
                'htmlOptions'=>array('value'=> $_GET['anyWord'],'placeholder' => 'put OR between words',
                    'style'=>'width: 220px;'),)); ?>
            <br> <strong>none of these words:</strong><br>
            <?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
                'name'=>'minus',
                'id'=>'minus',
                'value'=> $_GET['minus'],
                'htmlOptions'=>array('value'=> $_GET['minus'],'placeholder' => 'put minus sign before words',
                    'style'=>'width: 220px;'),)); ?>
            <!-- outside resources radio button -->
            <br> <div class="radio">
             <input type="radio" name="radioOption" id="radioOption" value="true">
                <strong> Include jobs from outside sources </strong>
            </div>
            <!-- search button -->
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>'Search',
                'type'=>'primary',
                'htmlOptions'=>array(
                    'data-toggle'=>'modal',
                    'data-target'=>'#myModal',
                    //'action'=> '/JobFair/index.php/job/home',
                    'id' => "searchbutton",
                    'style' => "margin-top: 5px; margin-bottom: 5px; width: 115px;",
                    'onclick' => "myFunction()",
                ),
            )); ?>
           <!-- reset button -->
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType'=>'reset',
                'label'=>'Reset Fields',
                'htmlOptions'=>array(
                    'data-toggle'=>'modal',
                    'data-target'=>'#myModal',
                    'id' => "searchbutton",
                    'style' => "margin-top: 5px; margin-bottom: 5px;width: 140px;",
                    'onclick' =>'window.location = "/JobFair/index.php/job/home"',
                ),)); ?>
        </div>
    </form>
</div>
<div>

<!-- ********** Search Result from Nav Bar && Advanced Search ************** -->
 <?php if (isset($flag) && $flag == 2) { ?>
    <!-- ******* Job Postings from Job Page using external sources & Career Path *******  -->
    <div class="pages">
    <table class="jobtable">
     <?php if ($jobs == null && $result == "" && $cbresults == ""){?>
        <h3>Sorry, no jobs matched your search. Please try again.</h3>
     <?php } else {?>
        Job Postings Page:
        <?php for ($i = 0; $i < $pages; $i ++) {?>
            <a class="pageclick"<?php if ($i == 0) echo "id='firstpage'"; ?>> <?php echo $i + 1;?></a>
        <?php }?>
    <head><th style="width: 20%">Position</th> <th style="width: 15%">Company</th> <th style="width: 10%">Type</th>  <th>Opening</th> <th style="width: 70px">Deadline</th>  <th>Compensation</th> <th> Skills</th><th>Source</th></head>
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
        <td><?php echo "CareerPath"?></td>
        </tr>
    <?php }  ?>
<?php } ?>
    <?php if ($result != null & sizeof($result) > 0) { $size = $result['end'];  ?>
        <tr>
            <?php  for($i=0;$i<$size-1;$i++){
                $results=$result['results'];
                ?>
                <td><a href=<?php echo $results['result'][$i]['url']; ?> target="_blank">
                   <?php if($results['result'][$i]['jobtitle'] != null) {echo $results['result'][$i]['jobtitle'];}
                            else {echo "N/A";}?></a></td>
                <td><?php if($results['result'][$i]['company'] != null) { echo $results['result'][$i]['company'];}
                            else {echo "N/A";}?></a></td>
                <td>N/A</td>
                <td><?php if($results['result'][$i]['date'] != null) {echo $results['result'][$i]['date'];}
                            else {echo "N/A";} ?></td>
                <td>N/A</td>
                <td>N/A</td>
                <td><?php if($results['result'][$i]['snippet'] != null) {echo $results['result'][$i]['snippet'];}
                            else {echo "N/A";} ?></td>
                <td><?php echo "Indeed"?></td>
            </tr>
            <?php } ?>
     <?php }
    if ($cbresults != null & sizeof($cbresults) > 0) {  $size = $cbresults[0]; ?>
        <tr>
        <?php  for($i=1;$i<$size+1;$i++){
            ?>
            <td><a href=<?php echo $cbresults[$i]->jobDetailsURL; ?> target="_blank">
                    <?php if($cbresults[$i]->title != null) {echo $cbresults[$i]->title;}
                    else {echo "N/A";}?></a></td>
            <td><?php if($cbresults[$i]->companyName != null) { echo $cbresults[$i]->companyName;}
                else {echo "N/A";}?></a></td>
            <td><?php if($cbresults[$i]->type != null) { echo $cbresults[$i]->type;}
                else {echo "N/A";}?></td>
            <td><?php if($cbresults[$i]->posted != null) {echo $cbresults[$i]->posted;}
                else {echo "N/A";} ?></td>
            <td>N/A</td>
            <td><?php if($cbresults[$i]->pay != null) {echo $cbresults[$i]->pay;}
                else {echo "N/A";} ?></td>
            <td><?php if($cbresults[$i]->skills != null) {echo $cbresults[$i]->skills;}
                else {echo "N/A";} ?></td>
            <td><?php echo "CareerBuilder"?></td>
            </tr>
        <?php } ?>
        </table> <?php }
     }?>

    </div>

<?php } ?>