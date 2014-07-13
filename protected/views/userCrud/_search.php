<?php
/* @var $this UserCrudController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'FK_usertype'); ?>
		<?php echo $form->textField($model,'FK_usertype'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'registration_date'); ?>
		<?php echo $form->textField($model,'registration_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'activation_string'); ?>
		<?php echo $form->textField($model,'activation_string',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'activated'); ?>
		<?php echo $form->textField($model,'activated'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'image_url'); ?>
		<?php echo $form->textField($model,'image_url',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'first_name'); ?>
		<?php echo $form->textField($model,'first_name',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'last_name'); ?>
		<?php echo $form->textField($model,'last_name',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'disable'); ?>
		<?php echo $form->textField($model,'disable'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'has_viewed_profile'); ?>
		<?php echo $form->textField($model,'has_viewed_profile'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'linkedinid'); ?>
		<?php echo $form->textField($model,'linkedinid',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'googleid'); ?>
		<?php echo $form->textField($model,'googleid',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fiucsid'); ?>
		<?php echo $form->textField($model,'fiucsid',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'hide_email'); ?>
		<?php echo $form->textField($model,'hide_email'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'job_notification'); ?>
		<?php echo $form->textField($model,'job_notification'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fiu_account_id'); ?>
		<?php echo $form->textField($model,'fiu_account_id',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'looking_for_job'); ?>
		<?php echo $form->textField($model,'looking_for_job'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->