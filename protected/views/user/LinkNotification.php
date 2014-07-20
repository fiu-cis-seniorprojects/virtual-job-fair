<?php
/* @var $this UserController */
/* @var $mesg messageLink */
?>

<br><br>
<br><br>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'link-to',
    'enableClientValidation' => false,
    'clientOptions'=>array(
        'validateOnSubmit'=>false,
        'validateOnChange'=>false,
    ),
    'htmlOptions'=>array('class'=>'well'),
));
?>

<br>
<h2>Congratulations!!!</h2>
<?php
echo "Your " .$mesg. " account has been linked to your profile.";
?><br><br>
<?php $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType'=>'link',
    'type'=>'primary',
    'label'=>'View my Profile',
    'url'=>'../../../profile/view'
)); ?><br><br>

<?php $this->endWidget(); ?>