<h1><?php echo ($model->isNewRecord ? 'New' : 'Update');?> Skill</h1>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'skillset-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('class'=>'well'),
)); ?>

<fieldset>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldRow($model, 'name', array('maxlength'=>45, 'class'=>'span3')); ?>

</fieldset>

<?php $this->widget('bootstrap.widgets.TbButton',
    array(  'label'=>'Cancel',
        'htmlOptions' => array(
            'onclick' => 'js:document.location.href="'.Yii::app()->createAbsoluteUrl("Skillset/index").'"'
        ),
    )
);
?>

<?php $this->widget('bootstrap.widgets.TbButton', array('type' => 'primary', 'buttonType'=>'submit', 'label'=>$model->isNewRecord ? 'Create' : 'Save')); ?>

<?php $this->endWidget(); ?>
