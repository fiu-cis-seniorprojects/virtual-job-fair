<?php
/* @var $this ApiConfigController */

$this->breadcrumbs=array(
	'Api Config'=>array('/apiConfig'),
	'Home',
);
?>
<h1><?php echo 'API Configuration'; ?></h1>



<!--API BULK IMPORT FORM-->
<br>
<br>
<h3><?php echo 'CareerPath Import'; ?></h3>
<br>


<script type="text/javascript">
    function formSend(form, data, hasError)
    {
        var data=$("#careerpath-bulkimport-form").serialize();

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
    'id'=>'careerpath-bulkimport-form',
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



<!--API KEY MANAGER-->
<br>
<br>
<h3><?php echo 'Authentication'; ?></h3>
<br>

<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'api-manager-form',
    'enableClientValidation' => false,
    'clientOptions'=>array(
        'validateOnSubmit'=>false,
        'validateOnChange'=>false,
    ),
    'htmlOptions'=>array('class'=>'well'),
));
?>

<?php $this->widget('bootstrap.widgets.TbButton',
    array(  'type'=>'primary',
            'size' => 'large',
            'type' => 'success',
            'label'=>'Manage API Keys',
            'htmlOptions' => array(
                'onclick' => 'js:document.location.href="'.Yii::app()->createAbsoluteUrl("ApiAuth/index").  '"'
            ),
    )); ?>
<?php $this->endWidget(); ?>