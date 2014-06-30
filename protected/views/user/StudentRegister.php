<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>
<br/><br/>
<h2>Student Register</h2>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-StudentRegister-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php if ($error != '') {?>
		
	<p style="color:red;"> <?php echo $error?></p>
	<?php }?>
	
	<?php if (isset($_GET['error'])){
		$error = $_GET['error'];	?>
		<p style="color:red;"> <?php echo $error?></p><?php 
	}?>

	
	<?php $model->basicInfo = BasicInfo::model();//needed to store phone number?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<p style="color:red" id="errors"></p>
	

	
	<div id="regbox">
	

		<?php echo $form->labelEx($model,'first_name'); ?>
		<?php echo $form->textField($model,'first_name',array( 'style'=>'width: 200px')); ?>

		<?php echo $form->labelEx($model,'last_name'); ?>
		<?php echo $form->textField($model,'last_name',array( 'style'=>'width: 200px'));?>
	
	
	
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array( 'style'=>'width: 200px')); ?>


		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password', array('maxlength'=>72, 'style'=>'width: 200px')); ?>

	

		<?php echo $form->labelEx($model, 'password_repeat'); ?>
		<?php echo $form->passwordField($model, 'password_repeat', 
				array('maxlength'=>72,  'style'=>'width: 200px')); ?>	
	

	
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array( 'style'=>'width: 200px')); ?>
		
		<?php echo $form->labelEx($model->basicInfo,'phone'); ?>
		<?php echo $form->textField($model->basicInfo,'phone'); ?>
		
		<br/><?php echo $form->labelEx($model->basicInfo,'allowSMS', array('style'=>'display:inline-block;!important')); ?>
		<?php echo $form->checkBox($model->basicInfo,'allowSMS'); ?>
		
			<div>
		<?php echo CHtml::submitButton('Submit', array("class"=>"btn btn-primary")); ?>
	</div>

<?php $this->endWidget(); ?>
	
	</div>
	

	
<p class="note" style="margin-top:248px; margin-left:300px;">Register with:</p>
<div id="regbox" style="margin-left: 40px; width:220px!important">

    <!--Author Manuel
making the links dynamic so if the base Url changed the program won not be affected
-->
    <?php
    $image =CHtml::image(Yii::app()->baseUrl. '/images/imgs/fiu_login.png');
    echo CHtml::link($image, array('profile/fiuAuth'));
    ?><br><br>

    <?php
    $image =CHtml::image(Yii::app()->baseUrl. '/images/imgs/linkedIn_login.png');
    echo CHtml::link($image, array('user/auth1'));
    ?><br><br>

    <?php
    $image =CHtml::image(Yii::app()->baseUrl. '/images/imgs/google_login.png');
    echo CHtml::link($image, array('profile/googleAuth'));
    ?><br><br>

	<div style="clear:both"></div>
	<br>
	


<script>
$.MyNamespace={
		submit : "true"
};
$(document).ready(function() {
    $("#user-StudentRegister-form").submit(function(e) {
        form = e;
        $.ajaxSetup({async:false});
        
        var response = $.post("/JobFair/index.php/User/verifyStudentRegistration", $("#user-StudentRegister-form").serialize());

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


</div><!-- form -->
