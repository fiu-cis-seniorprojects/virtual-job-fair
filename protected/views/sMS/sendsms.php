<?php
/* @var $this SMSController */
/* @var $id SMS */

$this->breadcrumbs=array(
	'Sms'=>array('index'),
	
);


?>
<br><br>
<div class="alertx"><div id="errors"></div></div>

<div id="wrapper" style="overflow: hidden">
<div id="regbox" style="float:left" >



<?php 


 $form=$this->beginWidget('CActiveForm', array(
		'id'=>'send-SMS-form',
		'action'=> $this->createUrl('SMS/Sendsms'),
		'enableAjaxValidation'=>false,
)); 

?>

<p> To:</p>

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




<?php echo $form->labelEx($SMS,'Message'); ?>
<?php echo $form->textArea($SMS,'Message', array('cols' => 70, 'rows'=>10)); ?>
<br>
		


<?php echo CHtml::submitButton('Send', array("class"=>"btn btn-primary")); ?>
<br><br>
<?php $this->endWidget(); ?>

<div id="pref" style="float:right; padding:15px; border: solid 1px rgb(131, 184, 201);border-radius: 5px;" >
<?php $this->widget('bootstrap.widgets.TbLabel', array(
    'type'=>'info', // 'success', 'warning', 'important', 'info' or 'inverse'
    'label'=>'SMS preferences',
	'htmlOptions'=> array("style"=> 'padding:7px;font-size:1.3em'),
)); ?>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'radioform',
    'type'=>'vertical',
	'action'=> $this->createUrl('SMS/ChangeSMSpref'),    //change default action
	
)); ?>


    <?php echo $form->radioButtonListRow($info, 'allowSMS', array(
        'I would prefer not to receive SMS',
        'I would like to receive SMS ',
    )); ?>
 
    <div style="padding-bottom:0px"> 
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit','icon'=>'ok' ,'type'=>'primary', 'label'=>'Save'));?>
    </div>
 
<?php $this->endWidget(); ?>

</div>






<div class="countdown"></div>
</div>

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
					
	        		$("#errors").html(data);
	        		$(".alertx").attr("class","alert alert-danger");
	        		
					}
			});

	  if(valid == false){event.preventDefault(); }
	});
});

</script>




<script> 

function countdown(){
	if($("#SMS_Message").val().length > 160)
	{
		 var new_text = $("#SMS_Message").val().substr(0, 160);
		 $("#SMS_Message").val(new_text);
		 
	}
	var charsleft = 160 - $("#SMS_Message").val().length;
	$(".countdown").text(charsleft + ' characters remaining.');

	 if(charsleft == 0){
	    	$(".countdown").css("color","red");
	    }

	 else $(".countdown").css("color","black");
}

$(function () {
    countdown();
    $("#SMS_Message").change(countdown);
    $("#SMS_Message").keyup(countdown);

   
});

	
</script>



</div>