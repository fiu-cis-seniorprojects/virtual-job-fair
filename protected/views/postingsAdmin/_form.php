<?php
/* @var $this PostingsAdminController */
/* @var $model Job */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'job-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->textField($model,'type',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'FK_poster'); ?>
		<?php echo $form->textField($model,'FK_poster'); ?>
		<?php echo $form->error($model,'FK_poster'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'post_date'); ?>
		<?php echo $form->textField($model,'post_date'); ?>
		<?php echo $form->error($model,'post_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deadline'); ?>
		<?php echo $form->textField($model,'deadline'); ?>
		<?php echo $form->error($model,'deadline'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'compensation'); ?>
		<?php echo $form->textField($model,'compensation',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'compensation'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'other_requirements'); ?>
		<?php echo $form->textArea($model,'other_requirements',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'other_requirements'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email_notification'); ?>
		<?php echo $form->textField($model,'email_notification'); ?>
		<?php echo $form->error($model,'email_notification'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'active'); ?>
		<?php echo $form->textField($model,'active'); ?>
		<?php echo $form->error($model,'active'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'matches_found'); ?>
		<?php echo $form->textField($model,'matches_found'); ?>
		<?php echo $form->error($model,'matches_found'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'posting_url'); ?>
		<?php echo $form->textArea($model,'posting_url',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'posting_url'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'comp_name'); ?>
		<?php echo $form->textField($model,'comp_name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'comp_name'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->