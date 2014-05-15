<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>
<?php $user = User::getCurrentUser(); ?>
<br/><br/><br/>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-changePassword-form',
	'enableAjaxValidation'=>false,
)); ?>
	<h1>Welcome <?php echo $user->first_name?> <?php  echo $user->last_name?>!</h1>
	<h2>Your username is: <?php echo $user->username?></h2>
	
	<h4>Please enter a password for this account. </h4>
	
	<div class="row" style="padding-left:30px;">
		<?php echo $form->labelEx($model,'New Password'); ?>
		<?php echo CHtml::passwordField('User[password1]', ''); ?>
	</div>
	
	<div class="row" style="padding-left:30px;">
		<?php echo $form->labelEx($model,'Retype New Password'); ?>
		<?php echo CHtml::passwordField('User[password2]', ''); ?>
	</div>	
	
	<?php if ($error != '') {?>
	<p style="color:red;"> <?php echo $error?></p>
	<?php }?>

	<div class="row buttons" style="padding-left:30px;">
		<?php echo CHtml::submitButton('Submit', array("class"=>"btn btn-primary")); ?>
	</div>
	<br>
	<!-- <p class="note"> <span class="required">*</span>You will be able to sign in with the social network you registered with as well</p> -->

<?php $this->endWidget(); ?>

</div><!-- form -->