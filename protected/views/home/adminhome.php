<br>
<br>
<script>
    function toggleNotifications(check)
    {
        var val = $("#tn_value").val();
        var action = 'togglematchnotifications';
        if(check)
        {
            action = 'checknotificationstate';
        }
        $.get("/JobFair/index.php/message/"+action, {"value": val}, function(data){
            data = JSON.parse(data);
            if(data["status"] == '0')
            {
                $("#toggleNotifications").addClass('btn btn-danger').removeClass('btn btn-success');
            }
            else
            {
                $("#toggleNotifications").addClass('btn btn-success').removeClass('btn btn-danger');
            }
            $("#user_lastmodified").html(data["username"]);
            $("#user_lastmodifieddate").html(data["last_modified"]);
            $("#tn_value").val(data["status"]);
        });
       setTimeout("toggleNotifications(1)", 30000);
    }

</script>
<div id="adminSearchbox"> 
<h1> Search for User or Job</h1>

<form method="POST" action="adminSearch" >
<input type="text" name="keyword">
<input type="submit" class="btn btn-primary">
</form>

<?php 
if ($results != NULL)
{?>

<table id="table-2" class="jobtable">
<thead>
<h2> User Results</h2>
<th>Username</th> <th>Id</th> <th>Email</th> <th>Registration Date</th> <th>Name</th> <th>Action</th>
</thead>
<?php foreach ($results as $js){ if ($js != null){?>
<tr>
	<td><a href="/JobFair/index.php/profile/student/user/<?php echo $js->username;?>"><?php echo $js->username;?></a></td>
	<td><?php echo $js->username;?></td>
	<td><?php echo $js->email;?></td>
	<td><?php echo Yii::app()->dateFormatter->format('MM/dd/yyyy', $js->registration_date);?></td>
	<td><?php echo $js->first_name.' '.$js->last_name;?></td>
	
	<?php if ($js->disable == 0){?>
	<form method="POST" action="disableUser" >
	<input type="hidden"  name="id" value="<?php echo $js->id;?>">
	<td><input type="submit" value="Disable" class="btn btn-primary"> </td></form>
	<?php }else{?>
	<form method="POST" action="enableUser" >
	<input type="hidden"  name="id" value="<?php echo $js->id;?>">
	<td><input type="submit" value="enable" class="btn btn-primary"> </td></form>
	<?php }?>
</tr>
<?php } } ?>
</table>
<?php } ?>




<?php 
if ($results1 != NULL)
{?>

<table id="table-2" class="jobtable">
<thead>
<h2> Jobs Results</h2>
<th>title</th> <th>type</th> <th>Poster</th> <th>Post Date</th> <th>Deadline</th>
</thead>
<?php foreach ($results1 as $js){ if ($js != null){?>
<tr>
	<td><a href="/JobFair/index.php/job/view/jobid/<?php echo $js->id;?>"><?php echo $js->title;?></a></td>
	<td><?php echo $js->type;?></td>
	<td><?php echo $js->fKPoster->username;?></td>
	<td><?php echo Yii::app()->dateFormatter->format('MM/dd/yyyy', $js->post_date);?></td>
	<td><?php echo Yii::app()->dateFormatter->format('MM/dd/yyyy', $js->deadline);?></td>
	
	<form method="POST" action="deleteJob" >
	<input type="hidden"  name="id" value="<?php echo $js->id;?>">
	<td><input type="submit" value="Delete" class="btn btn-primary"> </td></form>
</tr>
<?php } } ?>
</table>
<?php } ?>

</div>

<div id="adminSearchbox"> 
<h1> Skills</h1>
<p>Add a skill to database:</p>
<form method="POST" action="/JobFair/index.php/home/addSkill" >
<input type="text" name="skillname">
<input type="submit" class="btn btn-primary">
</form>

<p>Combine skills: First input is the skill to keep</p>
<form method="POST" action="/JobFair/index.php/home/mergeSkills" >
<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
    'name'=>'skill1',
	'id'=>'addskillname',
	'source'=>Skillset::getNames(),
    'htmlOptions'=>array(),)); ?>
    <?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
    'name'=>'skill2',
	'id'=>'addskillname2',
	'source'=>Skillset::getNames(),
    'htmlOptions'=>array(),)); ?>
<input type="submit" class="btn btn-primary">
</form>

</div>

<div id="adminTools">

    <h1>ToolBox:</h1>
    <?php
        if(isset($matchnotification))
        {
            $class = 'btn btn-success';
            $value = intval($matchnotification['status']);
            if($value == 0)
            {
                $class = 'btn btn-danger';
            }
            echo CHtml::button("Match Notification Status", array(
                'type'=>'submit',
                'id'=>'toggleNotifications',
                'class'=>$class,
                'onclick'=>'toggleNotifications()'
            ));

            echo CHtml::button("", array(
                'type'=>'hidden',
                'id'=>'tn_value',
                'class'=>'btn btn-success',
                'onclick'=>'toggleNotifications()',
                'value'=>$value,
            ));
        }
    ?><br/>Last Modified by: <span id="user_lastmodified"><?php if(isset($matchnotification)){ echo $matchnotification['username']; } ?></span><br/>
    Last Modified Date: <span id="user_lastmodifieddate"><?php if(isset($matchnotification)){ echo $matchnotification['date_modified']; } ?></span><br/>

</div>

<div id="adminNotification">

<h1>Notifications:</h1>

<?php foreach ($notification as $n) { ?>
<p style="color:#468847"><a href="<?php echo $n->link."?notificationRead=".$n->id."&activation=".$n->sender_id; ?>"><?php echo $n->message; ?></a>
<del><a href="/JobFair/index.php/Home/deleteNotification?id=<?php echo $n->id?>"><img src='/JobFair/images/ico/icon_close_small.jpg'/></a></del></p>
<hl>
<?php }?>

</div>

