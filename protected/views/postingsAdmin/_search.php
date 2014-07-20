<?php
/* @var $this PostingsAdminController */
/* @var $model Job */
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
		<?php echo $form->label($model,'type'); ?>
		<?php echo $form->textField($model,'type',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'FK_poster'); ?>
		<?php echo $form->textField($model,'FK_poster'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'post_date'); ?>
		<?php echo $form->textField($model,'post_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'deadline'); ?>
		<?php echo $form->textField($model,'deadline'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'compensation'); ?>
		<?php echo $form->textField($model,'compensation',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'other_requirements'); ?>
		<?php echo $form->textArea($model,'other_requirements',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email_notification'); ?>
		<?php echo $form->textField($model,'email_notification'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'active'); ?>
		<?php echo $form->textField($model,'active'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'matches_found'); ?>
		<?php echo $form->textField($model,'matches_found'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'posting_url'); ?>
		<?php echo $form->textArea($model,'posting_url',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'comp_name'); ?>
		<?php echo $form->textField($model,'comp_name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->