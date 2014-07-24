<script>
    $(document).ready(function()
    {
        $('#buttonStateful').click(function() {
            var btn = $(this);
            btn.button('loading'); // call the loading function
            setTimeout(function() {
                btn.button('reset'); // call the reset function
            }, 3000);
        });

    })
</script>

<h2>Edit User</h2>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'user-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('class'=>'well'),
)); ?>

<fieldset>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldRow($model, 'username', array('maxlength'=>45, 'class'=>'span3')); ?>

    <?php echo $form->textFieldRow($model, 'first_name', array('maxlength'=>45, 'class'=>'span3')); ?>

    <?php echo $form->textFieldRow($model, 'last_name', array('maxlength'=>45, 'class'=>'span3')); ?>

    <?php echo $form->textFieldRow($model, 'email', array('maxlength'=>45, 'class'=>'span3')); ?>


</fieldset>

<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array('type' => 'primary', 'buttonType'=>'submit', 'label'=>'Save')); ?>
    <?php $this->widget('bootstrap.widgets.TbButton',
        array(  'label'=>'Cancel',
            'htmlOptions' => array(
                'onclick' => 'js:document.location.href="'.Yii::app()->createAbsoluteUrl("userCrud/admin").'"'
            ),
        )
    );?>
</div>

<?php $this->endWidget(); ?>
