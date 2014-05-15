<?php
/* @var $this SMSController */
/* @var $id SMS */

$this->breadcrumbs=array(
	'Sms'=>array('index'),
	$id->id,
);


?>




<div id="errors">
</div>
<?php 


 $form=$this->beginWidget('CActiveForm', array(
		'id'=>'send-SMS-form',
		'enableAjaxValidation'=>false,
)); 

?>


<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
    'name'=>'username',
    'sourceUrl'=>$this->createUrl('SMS/GetAutoComplete'), 
		
    // additional javascript options for the autocomplete plugin
    'options'=>array(
        'minLength'=>'2',
    ),
    'htmlOptions'=>array(
        'style'=>'height:20px;',
    ),
));?>




<?php echo $form->labelEx($SMS,'Message'); ?><p style="color:red" id="charcount"> H</p>
<?php echo $form->textArea($SMS,'Message', array('cols' => 70, 'rows'=>10)); ?>



<?php echo CHtml::submitButton('Submit', array("class"=>"btn btn-primary")); ?>

<script> 
var valid = false;
$(function () {
	$.ajaxSetup({async:false});
	$("#send-SMS-form").submit(function(event){
		$.post( "<?php echo $this->createUrl('SMS/Verify');?>",$("#send-SMS-form").serialize())
		.done(function(data){
				if(data == ""){
					valid = true;
					alert("Message has been sent");
				}
				else{
					valid = false;
					$("html, body").animate({ scrollTop: 0 }, "fast");
	        		$("#errors").html(data);
					}
			});

	  if(valid == false){event.preventDefault(); }
	});
});

</script>


<?php $this->endWidget(); ?>
<span class="countdown"></span>
<script> 

function countdown(){
	if($("#SMS_Message").val().length > 160)
	{
		 var new_text = $("#SMS_Message").val().substr(0, 160);
		 $("#SMS_Message").val(new_text);
		 
	}
	var charsleft = 160 - $("#SMS_Message").val().length;
	$(".countdown").text(charsleft + ' characters remaining.');
	
}

$(function () {
    countdown();
    $("#SMS_Message").change(countdown);
    $("#SMS_Message").keyup(countdown);
});

	
</script>


