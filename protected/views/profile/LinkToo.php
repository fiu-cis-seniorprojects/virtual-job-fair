<?php
/* @var $this ProfileController */

?>
<br/><br/>
<h2>You already have an account with that Email:</h2><br>
<h3>Do you want to link both accounts?</h3><br>
<div class="form">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'button',
        'type'=>'primary',
        'label'=>'Link',
    )); ?><br><br>
</div><!-- form -->


    <script type="text/javascript">
        function formSend(form, data, hasError)
        {
            var data=$("#link_accounts").serialize();

            $.ajax({
                type: 'POST',
                url: '<?php echo Yii::app()->createAbsoluteUrl("ApiConfig/importJobs"); ?>',
                data:data,
                success:function(data){
                    alert(data);
                },
                error: function(data) { // if error occured
                    alert("Error occured.please try again");
                    alert(data);
                },

                dataType:'html'
            });

            return false;
        }
    </script>

<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'link_accounts',
    'enableClientValidation' => true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'validateOnChange'=>false,
        'afterValidate'=>'js:formSend'
    ),
    'htmlOptions'=>array('class'=>'well'),


));
?>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->labelEx($model,'dateFrom'); ?>
<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name' => 'ApiConfigForm[dateFrom]',
    'options' => array(
        'showAnim' => 'fold',
        'dateFormat'=> 'yy-mm-dd',
    ),
    'htmlOptions' => array(
        'style' => 'height:20px; width: 200px'
    )
));?>

<?php echo $form->labelEx($model,'dateTo'); ?>
<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name' => 'ApiConfigForm[dateTo]',
    'options' => array(
        'showAnim' => 'fold',
        'dateFormat'=> 'yy-mm-dd',
    ),
    'htmlOptions' => array(
        'style' => 'height:20px; width: 200px'
    )
));?>

<?php echo $form->checkboxRow($model, 'allowExpired'); ?>

<?php $this->widget('bootstrap.widgets.TbButton', array('type'=>'primary', 'size' => 'normal', 'buttonType'=>'submit', 'label'=>'Synchronize with CareerPath')); ?>

<?php $this->endWidget(); ?>