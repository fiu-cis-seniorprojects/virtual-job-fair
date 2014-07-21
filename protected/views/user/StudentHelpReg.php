<?php
/* @var $this UserController */
/* @var $email */
/* @var $form CActiveForm  */
?>
<br><br>
<br><br>
<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'link-to',
    'enableClientValidation' => true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'validateOnChange'=>false
    ),
    'htmlOptions'=>array('class'=>'well'),


));
?>
<h2>You already have an account with that Email</h2><br>
<h4>Choose what will you like to do next:</h4>
<h5>Login with your account:</h5>
<?php $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType'=>'link',
    'type'=>'primary',
    'label'=>'Go to login',
    'url'=>'..'
)); ?><br><br>


<h5>Register using a different email:</h5>
<?php $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType'=>'link',
    'type'=>'primary',
    'label'=>'Register',
    'url'=>'StudentRegister'
)); ?><br><br>

<h5>I don't remember my password:</h5>
<?php $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType'=>'link',
    'type'=>'primary',
    'label'=>'Get my password',
    'url'=>'../site/forgotPassword'

)); ?>
<?php $this->endWidget(); ?>