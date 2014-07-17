<br/><br/><br/><br/>

<link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.0/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.0/css/jquery.dataTables_themeroller.css">
<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.1.min.js"></script>
<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.0/jquery.dataTables.min.js"></script>

<?php
$pages = 0;
$size = 0;
$loValue = false;
$query = "";
$i = 0; $j = 0; $k = 0;
$sizeJobs = 0; $sizeIndeed = 0; $sizeCB = 0 ;

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
if (!isset($_GET['tagName'])) {
    $_GET['tagName'] = '';
}
if (!isset($_GET['radioOption'])) {
    $_GET['radioOption'] = '';
}
if (!isset($_GET['city'])) {
    $_GET['city'] = '';
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



    function myFunction()
    {
        document.getElementById("searchForm").action = "/JobFair/index.php/job/home";
        document.getElementById("searchForm").submit();
    }

    function saveQuery()
    {
        var tagNam = prompt("Please enter name, then check profile settings", "Search_1");

        var num = tagNam.length;

        if(tagNam != "" && num < 25)
        {
            document.getElementById("tagName").value = tagNam;
            document.getElementById("searchForm").action = "/JobFair/index.php/job/savequery";
            document.getElementById("searchForm").submit();
        }
        else
        {
            alert("Tag value cannot be empty AND greater than 25 characters long");
        }

    }

    $(document).ready(function() {

        var isChecked = $('#radioOption').is(':checked');
        $("#city").hide();
        $(document).on('click', '#radioOption', create);
        $clicked = 0;


        function create(e)
        {
            var value =  $('#radioOption').is(':checked');;
            if (value == true) {
                $("#city").show();
                isChecked = false;
            } else if (value == false) {
                $("#city").hide();
                isChecked = true;
            }

        }

        var value = $('#radioOption').val();
        if (isChecked == true)
            $("#city").show();


       $('#jobtable').dataTable({
           "sPaginationType" : "full_numbers",
           "bSort":             false,
           "bFilter" :          false
        });

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

<div id="fullcontentjob">

<!-- ********** Advance Search *********** -->
<div id="searchforjobs2" style="float:left;">
<div class="titlebox">Advanced Search  </div>
    <br/><br>
    <form method="GET" id="searchForm" action="">
      <h4>Find jobs with... </h4>
        <div>
            <strong>these words:</strong>
            <?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
                'name'=>'allWords',
                'id'=>'allWords',
                'value'=> $_GET['allWords'],
                'htmlOptions'=>array('value'=> $_GET['allWords'],'placeholder' => 'put plus sign before word',
                    'style'=>'width: 200px;'),)); ?>
            <br> <strong>this exact word or phrase:</strong><br>
            <?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
                'name'=>'phrase',
                'id'=>'phrase',
                'value'=> $_GET['phrase'],
                'htmlOptions'=>array('value'=> $_GET['phrase'],'placeholder' => 'put words or phrase in quotes',
                    'style'=>'width: 200px;'),)); ?>
            <br> <strong>any of these words:</strong><br>
            <?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
                'name'=>'anyWord',
                'id'=>'anyWord',
                'value'=> $_GET['anyWord'],
                'htmlOptions'=>array('value'=> $_GET['anyWord'],'placeholder' => 'put OR between words',
                    'style'=>'width: 200px;'),)); ?>
            <br> <strong>none of these words:</strong><br>
            <?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
                'name'=>'minus',
                'id'=>'minus',
                'value'=> $_GET['minus'],
                'htmlOptions'=>array('value'=> $_GET['minus'],'placeholder' => 'put minus sign before words',
                    'style'=>'width: 200px;'),)); ?>
            <!-- outside resources radio button -->
            <br> <div>
                <input type="checkbox" name="radioOption" id="radioOption" value="true"
                    <?php  if($_GET['radioOption'] == "true"){echo 'checked="checked"';} ?>  >
                <strong> Include jobs from outside sources</strong>
            </div>
            <!-- hidden box, DO NOT MAKE VISIBLE -->
            <?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
                'name'=>'city',
                'id'=>'city',
                'value'=> $_GET['city'],
                'htmlOptions'=>array('value'=> $_GET['city'], 'placeholder' => 'City, State',
                    'style'=>'width: 200px; display: none'),)); ?>

            <!-- hidden box, DO NOT MAKE VISIBLE -->
            <?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
                'name'=>'tagName',
                'id'=>'tagName',
                'value'=> $_GET['tagName'],
                'htmlOptions'=>array('value'=> $_GET['tagName'],
                    'style'=>'width: 200px; display: none'),)); ?>
            <!-- search button -->
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>'Search',
                'type'=>'primary',
                'htmlOptions'=>array(
                    'data-toggle'=>'modal',
                    'data-target'=>'#myModal',
                    'id' => "searchbutton",
                    'style' => "margin-top: 5px; margin-bottom: 5px; width: 115px;",
                    'onclick' => "myFunction()",
                ),
            )); ?>
            <!-- save button -->
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>'Save Query',
                'type'=>'info',
                'htmlOptions'=>array(
                    'data-toggle'=>'modal',
                    'data-target'=>'#myModal',
                    'id' => "savebutton",
                    'style' => "margin-top: 5px; margin-bottom: 5px; width: 115px;",
                    'onclick' => "saveQuery()",
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
</div> <!-- END OF ADVANCED SEARCH -->


<!-- ********** Search Result from Nav Bar && Advanced Search ************** -->
 <div id ="jobcontent">
 <?php if (isset($flag) && $flag == 2) { ?>
    <!-- ******* Job Postings from Job Page using external sources & Career Path *******  -->
    <table class="display" id="jobtable" >
     <?php if ($jobs == null && $result == "" && $cbresults == ""){?>
        <h3>Sorry, no jobs matched your search. Please try again.</h3>
     <?php } else {?>
    <thead><th>Position</th> <th>Company</th> <th>Type</th>
        <th>Opening</th> <th>Deadline</th>  <th>Salary</th>
        <th> Skills</th><th>Source</th>
    </thead>
     <!-- get size of all job results -->
    <?php
         // gets how many job from CareerPath
        if($jobs !=null & sizeof($jobs) > 0)
        {
            $sizeJobs = sizeof($jobs); $i = 0;
        }
         // gets how many job from Indeed
        if($result != null & sizeof($result) > 0)
        {
            $sizeIndeed = $result['end']; $j = 0; $results=$result['results'];
            //print_r($result);die;
        }
         // gets how many job from CareerBuilder
        if($cbresults != null & sizeof($cbresults) > 0)
        {
            $sizeCB = $cbresults[0]; $k = 1;
            if($sizeCB == 1) {$sizeCB =2;}
        }
    ?>
    <tbody>
        <?php // There is only CareerPath jobs
         if($j == $sizeIndeed && $k == $sizeCB && $sizeJobs > 0)
         {
             foreach ($jobs as $job) {?>
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
            <?php }
         }
         else{?>
           <?php  while($j != $sizeIndeed || $k != $sizeCB || $i != $sizeJobs)
           {
               if($i < $sizeJobs) { ?>
                   <tr>
                       <td><a href="/JobFair/index.php/job/view/jobid/<?php echo $jobs[$i]->id;?>"><?php echo $jobs[$i]->title;?></a></td>
                       <td><a href="/JobFair/index.php/profile/employer/user/<?php echo User::model()->findByAttributes(array('id'=>$jobs[$i]->FK_poster))->username;?>"><?php echo CompanyInfo::model()->findByAttributes(array('FK_userid'=>$jobs[$i]->FK_poster))->name;?></a></td>
                       <td><?php echo $jobs[$i]->type;?></td>
                       <td><?php echo Yii::app()->dateFormatter->format('MM/dd/yyyy', $jobs[$i]->post_date);?></td>
                       <td><?php echo Yii::app()->dateFormatter->format('MM/dd/yyyy', $jobs[$i]->deadline);?></td>
                       <td><?php echo $jobs[$i]->compensation;?></td>
                       <td><?php $temp = JobSkillMap::model()->findAllByAttributes(array('jobid'=>$jobs[$i]->id));?>
                           <?php foreach ($temp as $one){
                               echo Skillset::model()->findByAttributes(array('id'=>$one->skillid))->name.' ';
                           }?></td>
                       <td><?php echo "CareerPath"?></td>
                   </tr>
               <?php $i++; }
               if($j < $sizeIndeed && $sizeIndeed > 1){ ?>
                <tr>
                    <td><a href=<?php echo $results['result'][$j]['url']; ?> target="_blank">
                            <?php if($results['result'][$j]['jobtitle'] != null) {echo $results['result'][$j]['jobtitle'];}
                            else {echo "N/A";}?></a></td>
                    <td><?php if($results['result'][$j]['company'] != null) { echo $results['result'][$j]['company'];}
                        else {echo "N/A";}?></a></td>
                    <td>N/A</td>
                    <td><?php if($results['result'][$j]['date'] != null) {echo $results['result'][$j]['date'];}
                        else {echo "N/A";} ?></td>
                    <td>N/A</td>
                    <td>N/A</td>
                    <td><?php if($results['result'][$j]['snippet'] != null) {echo $results['result'][$j]['snippet'];}
                        else {echo "N/A";} ?></td>
                    <td><?php echo "Indeed"?></td>
               </tr>
               <?php $j++; }
               if($sizeIndeed == 1)
               {?>
                   <tr>
                       <td><a href=<?php echo $results['result']['url']; ?> target="_blank">
                               <?php if($results['result']['jobtitle'] != null) {echo $results['result']['jobtitle'];}
                               else {echo "N/A";}?></a></td>
                       <td><?php if($results['result']['company'] != null) { echo $results['result']['company'];}
                           else {echo "N/A";}?></a></td>
                       <td>N/A</td>
                       <td><?php if($results['result']['date'] != null) {echo $results['result']['date'];}
                           else {echo "N/A";} ?></td>
                       <td>N/A</td>
                       <td>N/A</td>
                       <td><?php if($results['result']['snippet'] != null) {echo $results['result']['snippet'];}
                           else {echo "N/A";} ?></td>
                       <td><?php echo "Indeed"?></td>
                   </tr>
              <?php $j++; }
               if($k < $sizeCB && $sizeCB > 0){  ?>
                <tr>
                    <td><a href=<?php echo $cbresults[$k]->jobDetailsURL; ?> target="_blank">
                            <?php if($cbresults[$k]->title != null) {echo $cbresults[$k]->title;}
                            else {echo "N/A";}?></a></td>
                    <td><?php if($cbresults[$k]->companyName != null) { echo $cbresults[$k]->companyName;}
                        else {echo "N/A";}?></a></td>
                    <td><?php if($cbresults[$k]->type != null) { echo $cbresults[$k]->type;}
                        else {echo "N/A";}?></td>
                    <td><?php if($cbresults[$k]->posted != null) {echo $cbresults[$k]->posted;}
                        else {echo "N/A";} ?></td>
                    <td>N/A</td>
                    <td><?php if($cbresults[$k]->pay != null) {echo $cbresults[$k]->pay;}
                        else {echo "N/A";} ?></td>
                    <td><?php if($cbresults[$k]->skills != null) {echo $cbresults[$k]->skills;}
                        else {echo "N/A";} ?></td>
                    <td><?php echo "CareerBuilder"?></td>
                </tr>
                <?php $k++; } ?>
          <?php }
         }

     }?>
    </tbody>
 </table>
<?php } ?>
</div> <!-- END OF JOB RESULT TABLE-->

</div> <!-- END OF FULL CONTENT -->

