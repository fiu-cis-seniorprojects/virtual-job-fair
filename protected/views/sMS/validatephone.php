<?php
/* @var $this SMSController */
/* @var $id SMS */

$this->breadcrumbs=array(
		'Sms'=>array('index')
		
);


$form=$this->beginWidget('CActiveForm', array(
		'id'=>'activation_code',
		'enableAjaxValidation'=>false,
));



$info->smsCode = ""; // clear value from text box
?>



<h1> You need to validate your phone number</h1>

<div class="alertx"><div id="errors"></div></div>

<?php echo $form->labelEx($info,'Enter Activation Code'); ?>
<?php echo $form->textField($info,'smsCode'); ?>

<?php echo CHtml::submitButton('Submit', array("class"=>"btn btn-primary")); ?>

<?php $this->endWidget(); ?>

 <?php 
	$this->widget('bootstrap.widgets.TbButton', array(
    'label'=>'Send/resend code',
    'type'=>'success', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
    'size'=>'null', // null, 'large', 'small' or 'mini'
	 'htmlOptions'=>array('id' => 'resend'),
)); ?>






<script> 

$(function () {
	$.ajaxSetup({async:false});
	$("#activation_code").submit(function(event){
		$.post( "<?php echo $this->createUrl('SMS/validation');?>",$("#activation_code").serialize())
		.done(function(data){
				if(data == ""){
					
					alert("Code succesfully entered");
				}
				else{
					
					
					$("#errors").html(data);
	        		$(".alertx").attr("class","alert alert-danger");
	        		event.preventDefault();
					}
			});

	  
	});
});


//request a code
$(function(){
	$("#resend").click( function() { 
		
			
			  $.post("<?php echo $this->createUrl('SMS/Sendcode');?>", 
					    function (data) {
							  if(data == ""){
								  alert("A new activation code was sent to your phone, please enter it on the given field.");
							  }

							  else{
								  $("#errors").html(data);
							  }
						  });

			
			
		
	});
});





</script>



