<br>

<script>
function filterUsers(){
    var q = document.getElementById("filter").value;
    
    var tds = document.getElementsByTagName("td");
    for (var i=0; i<tds.length; i++) {
        tds[i].parentNode.style.display = "";
    }

    for (var i=0; i<tds.length; i++) {
        if (tds[i].className == "info"){
           if (tds[i].innerHTML.toLowerCase().indexOf(q.toLowerCase()) < 0){
            tds[i].parentNode.style.display = "none";
           }
        }                    
    }

}
</script>

<div id="hardcorecontent">
<?php if ($results != null && sizeof($results) > 0) {?>
<br><br>
<h2>Search Results </h2>
 
<table class="jobtable">
<thead>
<tr><th> Username </th> <th> Name </th> <th> University </th> <th> Skills </th></tr>
</thead>
<tbody id="fbody">
<?php foreach ($results as $js){  if ($js != null){?>
<tr>
	<td class="info"><a href="/JobFair/index.php/profile/student/user/<?php echo $js->username;?>"><?php echo $js->username;?></a></td>
	<td><?php echo $js->first_name . " " . $js->last_name;?></td>


	<td><?php 
	$educ = Education::model()->findByAttributes(array('FK_user_id'=>$js->id));
	$school = "";
	if($educ)
	{
		$school = School::model()->findByAttributes(array('id'=>$educ->FK_school_id))->name;
	}
	echo $school;
	?></td>	
       
        <td class="info1">
	<?php $temp = StudentSkillMap::model()->findAllByAttributes(array('userid'=>$js->id));
	 foreach ($temp as $one){
		echo Skillset::model()->findByAttributes(array('id'=>$one->skillid))->name." - ";
	}?></td>
</tr>
<?php } } ?>
</tbody>
</table>


<?php } else {?>
<br><br>
<h2>No Results</h2>
<?php }?>
</div>