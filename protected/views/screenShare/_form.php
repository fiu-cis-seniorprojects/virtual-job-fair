<?php
/* @var $this ScreenShareController */
/* @var $model ScreenShare */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'screen-share-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'FK_employer'); ?>
		<?php echo $form->textField($model,'FK_employer'); ?>
		<?php echo $form->error($model,'FK_employer'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'FK_student'); ?>
		<?php echo $form->textField($model,'FK_student'); ?>
		<?php echo $form->error($model,'FK_student'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time'); ?>
		<?php echo $form->textField($model,'time'); ?>
		<?php echo $form->error($model,'time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'session_key'); ?>
		<?php echo $form->textField($model,'session_key',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'session_key'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'notification_id'); ?>
		<?php echo $form->textField($model,'notification_id',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'notification_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ScreenShareView'); ?>
		<?php echo $form->textField($model,'ScreenShareView',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'ScreenShareView'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->