<h2><?php echo ($model->isNewRecord ? 'Create' : 'Edit');?> API Key</h2>


<script>
    function random_str(char_len)
    {
        var alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        var text = "";
        for( var i=0; i < char_len; i++ )
            text += alphabet.charAt(Math.floor(Math.random() * alphabet.length));

        $('#apikeyText').val(text);
    }

    $( document ).ready(function() {
        random_str(45);
    });

</script>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'user-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('class'=>'well'),
)); ?>

<fieldset>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldRow($model, 'description', array('style' => 'width:50%;','maxlength'=>45, 'class'=>'span3')); ?>

    <div>
    <?php echo $form->textFieldRow($model, 'valid_key', array('style' => 'width:50%;', 'maxlength'=>45, 'class'=>'span3', 'id'=>'apikeyText')); ?>

    <?php $this->widget('bootstrap.widgets.TbButton',
        array(  'label'=>'Generate',

            'type' => 'inverse',
            'htmlOptions' => array(
                'style' => 'vertical-align:top',
                'onclick' => 'js:random_str(45);'
            ),
        )
    );
    ?>
    </div>

</fieldset>




<div class="form-actions">
<?php $this->widget('bootstrap.widgets.TbButton', array('type' => 'primary', 'buttonType'=>'submit', 'label'=>'Save')); ?>


<?php $this->widget('bootstrap.widgets.TbButton',
    array(  'label'=>'Cancel',
        'htmlOptions' => array(
            'onclick' => 'js:document.location.href="'.Yii::app()->createAbsoluteUrl("ApiAuth/index").'"'
        ),
    )
);
?>
</div>


<?php $this->endWidget(); ?>
