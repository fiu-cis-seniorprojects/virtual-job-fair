<?php
/* @var $this ApiAuthController */
/* @var $model ApiAuth */
/* @var $form CActiveForm */
?>

<script>
    function random_str(char_len)
    {
        var alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        var text = "";
        for( var i=0; i < char_len; i++ )
            text += alphabet.charAt(Math.floor(Math.random() * alphabet.length));

        $('#apikeyText').val(text);
    }
</script>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'api-auth-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'valid_key'); ?>
		<?php echo $form->textField($model,'valid_key',array('id'=>'apikeyText','size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'valid_key'); ?>
	</div>

	<div class="row buttons">

        <?php
            echo CHtml::button('Generate', array('onclick' => 'js:random_str(45);'));
        ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->