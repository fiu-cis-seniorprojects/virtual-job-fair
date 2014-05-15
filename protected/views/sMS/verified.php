
<?php
/* @var $this SMSController */
/* @var $id SMS */

$this->breadcrumbs=array(
	'Sms'=>array('index'),
	
);


?>
<h1>Your phone has been verified </H1>

<?php $this->widget('bootstrap.widgets.TbLabel', array(
    'type'=>'info', // 'success', 'warning', 'important', 'info' or 'inverse'
    'label'=>'SMS preferences',
	'htmlOptions'=> array("style"=> 'padding:7px;font-size:1.3em'),
)); ?>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'radioform',
    'type'=>'vertical',
	'action'=> $this->createUrl('SMS/ChangeSMSpref'),    //change default action
	
)); ?>


    <?php echo $form->radioButtonListRow($info, 'allowSMS', array(
        'I would prefer not to receive SMS',
        'I would like to receive SMS ',
    )); ?>
 
    <div style="padding-top:10px"> 
    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit','icon'=>'ok' ,'type'=>'primary', 'label'=>'Save'));?>
    </div>
 
<?php $this->endWidget(); ?>

