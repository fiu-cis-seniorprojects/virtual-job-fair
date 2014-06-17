<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>
<?php $user = User::getCurrentUser(); ?>
<br/><br/><br/><br/>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-changePassword-form',
	'enableAjaxValidation'=>false,
)); ?>
	
	<?php if ($error != '') {?>
	<p style="color:red;"> <?php echo $error?></p>
	<?php }?>
    <div class="row">
        <?php echo $form->labelEx($model,'E-mail of your other account'); ?>
        <?php echo CHtml::passwordField('User[password1]', ''); ?>
    </div>
    <div class="row">
		<?php echo $form->labelEx($user,'Password of your other account'); ?>
		<?php echo CHtml::passwordField('User[password]', ''); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit', array("class"=>"btn btn-primary")); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->