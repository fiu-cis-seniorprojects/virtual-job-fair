<?php
/* @var $this UserCrudController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'FK_usertype'); ?>
		<?php echo $form->textField($model,'FK_usertype'); ?>
		<?php echo $form->error($model,'FK_usertype'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'registration_date'); ?>
		<?php echo $form->textField($model,'registration_date'); ?>
		<?php echo $form->error($model,'registration_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'activation_string'); ?>
		<?php echo $form->textField($model,'activation_string',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'activation_string'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'activated'); ?>
		<?php echo $form->textField($model,'activated'); ?>
		<?php echo $form->error($model,'activated'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'image_url'); ?>
		<?php echo $form->textField($model,'image_url',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'image_url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'first_name'); ?>
		<?php echo $form->textField($model,'first_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'first_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'last_name'); ?>
		<?php echo $form->textField($model,'last_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'last_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'disable'); ?>
		<?php echo $form->textField($model,'disable'); ?>
		<?php echo $form->error($model,'disable'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'has_viewed_profile'); ?>
		<?php echo $form->textField($model,'has_viewed_profile'); ?>
		<?php echo $form->error($model,'has_viewed_profile'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'linkedinid'); ?>
		<?php echo $form->textField($model,'linkedinid',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'linkedinid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'googleid'); ?>
		<?php echo $form->textField($model,'googleid',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'googleid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fiucsid'); ?>
		<?php echo $form->textField($model,'fiucsid',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'fiucsid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hide_email'); ?>
		<?php echo $form->textField($model,'hide_email'); ?>
		<?php echo $form->error($model,'hide_email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'job_notification'); ?>
		<?php echo $form->textField($model,'job_notification'); ?>
		<?php echo $form->error($model,'job_notification'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fiu_account_id'); ?>
		<?php echo $form->textField($model,'fiu_account_id',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'fiu_account_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'looking_for_job'); ?>
		<?php echo $form->textField($model,'looking_for_job'); ?>
		<?php echo $form->error($model,'looking_for_job'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->