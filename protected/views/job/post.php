<?php
/* @var $this JobController */
/* @var $model Job */
/* @var $form CActiveForm */
?>
<br/><br/><br/><br/>

<script>
var skillsarray = [];

$(document).ready(function() {
	//array to remove duplicate skills
	var words = [];
	
	var i = 1;
	$("#addskill").click( function(e) {
		var skill = $('#addskillname');
		skill.val($.trim(skill.val()).replace(/\s*[\r\n]+\s*/g, '\n')
                .replace(/(<[^\/][^>]*>)\s*/g, '$1')
                .replace(/\s*(<\/[^>]+>)/g, '$1'));

		if ($.inArray(skill.val(), skillsarray) != -1) {
			alert("Skill is already in list");
			return;
		}
        
		$.get("/JobFair/index.php/profile/getskill?name=" + $('#addskillname').val(), function (data,status) {
			skillsarray.push($('#addskillname').val());
			$("#skills ul").append('<li id="newskill' + i + '"><span class="skilldrag">' + $('#addskillname').val() + 
					"<input type='hidden' name='Skill[]' value='" + data + "' /></span>"  +
					'<a class="deletenewskill" id="newskill' + i + '" value="' + $('#addskillname').val() + '"><img src="/JobFair/images/ico/del.gif"/></a></li>');
			$("#addskillname").val("");
			i++;
			$("#ui-id-1").hide();
		});
	
	});

	$('#addskillname').bind("enterKey",function(e){
		  $("#addskill").click();
	});
	$('#addskillname').keydown(function(e){
		if(e.keyCode == 13)
		{
			e.preventDefault();
		}
	});
	$('#addskillname').keyup(function(e){
		if(e.keyCode == 13)
		{
			e.preventDefault();
			$(this).trigger("enterKey");
		}
	});


$("#Job_description").keyup(function(e){
	//detect words, query them to see if its a skill
	if (e.which == 32){
		var text = $(this).val().replace(/[\r\n]+(?=[^\r\n])/g, " ").split(' ');

		for( var j = 0, len = text.length - 1; j < len; j++ ) {
			var search = text[j].replace(/^,|,$/g,'');
			search = search.replace(/^\.|\.$/g,'');
			//alert(search);
			if ($.inArray(search, words) != -1){
				continue;
			}
			
			words.push(search);
			
			$.get("/JobFair/index.php/job/querySkill?name=" + search, function (data,status) {
				var place = data.split(',');
				var skillname = place[0];
				var skillid = place[1];
				if (data != "No Skill"){
					if ($.inArray(skillname, skillsarray) == -1 ){
						$("#skills ul").append('<li id="newskill' + skillname + '"><span class="skilldrag">' + skillname + 
								"<input type='hidden' name='Skill[]' value='" + skillid + "' /></span>"  +
								'<a class="deletenewskill" id="newskill' + skillname + '" value="' + skillname + '"><img src="/JobFair/images/ico/del.gif"/></a></li>');
						$("#ui-id-1").hide();
						skillsarray.push(skillname);
					}
					
				}
				
			});
		}
	}
	});

$("#Job_description").change(function(e){
	//detect words, query them to see if its a skill
	
	var text = $(this).val().replace(/[\r\n]+(?=[^\r\n])/g, " ").split(' ');
	for( var i = 0, len = text.length; i < len; i++ ) {
		
		var search = text[i].replace(/^,|,$/g,'');
		search = search.replace(/^\.|\.$/g,'');
		
		if ($.inArray(search, words) != -1){
			continue;
		}
		words.push(search);
		
		
		$.get("/JobFair/index.php/job/querySkill?name=" + search, function (data,status) {
			var place = data.split(',');
			var skillname = place[0];
			var skillid = place[1];
			if (data != "No Skill"){
				if ($.inArray(skillname, skillsarray) == -1 ){
					$("#skills ul").append('<li id="newskill' + skillname + '"><span class="skilldrag">' + skillname + 
							"<input type='hidden' name='Skill[]' value='" + skillid + "' /></span>"  +
							'<a class="deletenewskill" id="newskill' + skillname + '" value="' + skillname + '"><img src="/JobFair/images/ico/del.gif"/></a></li>');

					$("#ui-id-1").hide();
					skillsarray.push(skillname);
				}
			}
		});
	}
	
	});
});

$(document).delegate('.deletenewskill','click',function(){
	var delskill = $('#addskillname');
	skillsarray.splice( $.inArray(delskill.val(), skillsarray), 1 );
	$("#" + this.id).remove();
});

</script>
<div id="fullcontent">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'job-post-form',
	'enableAjaxValidation'=>false,
)); ?>
<div id="subcontent">



	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<p style="color:red" id="errors"></p>
<div id="regbox">

		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->dropDownList($model,'type',array ("" => "", 'Part Time'=>'Part Time', 'Full Time'=>'Full Time', 'Internship'=>'Internship', 'Co-op'=>'Co-op', 'Research'=>'Research')); ?>


		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array( 'style'=>'width: 200px')); ?>


		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description', array('rows'=> 10, 'cols'=>75)); ?>


		<?php echo $form->labelEx($model,'compensation'); ?>
		<?php echo $form->textField($model,'compensation',array( 'style'=>'width: 200px')); ?>




		<?php echo $form->labelEx($model,'deadline'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name' => 'Job[deadline]',
				'options' => array(
					'showAnim' => 'fold',	
					'dateFormat'=> 'yy-mm-dd',
				), 
				'htmlOptions' => array(
					'style' => 'height:20px; width: 200px'
				)
				));?>
		<?php echo $form->error($model,'deadline'); ?>
		<br>
        <!--<?php echo "<input type='checkbox' id='template' name='template' value='template'> Is template?</br>" ?>-->
        <?php //echo $form->checkBox($model, 'template') ?>
<?php echo CHtml::submitButton('Submit', array("class"=>"btn btn-primary")); ?>
	</div>
</div><!-- form -->
<div id="rightside" style="float:right!important">
<div id="skills" style="margin-top:30px;">
<div class="titlebox">SKILLS</div>
<br>
<ul id="sortable">
	
	<script>
		$(document).ready(function() {
			$(function() {
				$("#sortable").sortable();
				$("#sortable").disableSelection();
			});
		});
	</script>
	
	</ul>
	<br/>
	<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
    'name'=>'addskillname',
	'id'=>'addskillname',
	'source'=>Skillset::getNames(),
    'htmlOptions'=>array(),)); ?>
    <br>

	
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		    'label'=>'Add Skill',
		    'type'=>'primary',
		    'htmlOptions'=>array(
		        'data-toggle'=>'modal',
		        'data-target'=>'#myModal',
				'style'=>'width: 120px',
				'id' => "addskill",
		    	'style' => "margin-top: 5px; margin-bottom: 5px;width: 120px;",
		    	),
			)); ?>
	
</div>
</div>
</div>
<script>
	
$.MyNamespace={
		submit : "true"
};
$(document).ready(function() {
    $("#job-post-form").submit(function(e) {
        form = e;
        $.ajaxSetup({async:false});
        
        var response = $.post("/JobFair/index.php/Job/verifyJobPost", $("#job-post-form").serialize());

        response.done(function(data) {
        	if (data != ""){
        		$("html, body").animate({ scrollTop: 0 }, "fast");
        		$("#errors").html(data);
        		$.MyNamespace.submit = 'false';
        	} else {
        		$.MyNamespace.submit = 'true';
        	}
        });
		if ($.MyNamespace.submit == 'false'){
			e.preventDefault();
		}
    });
    return;
});
</script>
<?php $this->endWidget(); ?>

