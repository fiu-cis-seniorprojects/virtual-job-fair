<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>
<br/><br/>
<h2>Linking Error:</h2>
<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'user-StudentRegister-form',
        'enableAjaxValidation'=>false,
    )); ?>

    <?php if ($error != '') {?>

        <p style="color:red;"> <?php echo $error?></p>
    <?php }?>

    <?php if (isset($_GET['error'])){
        $error = $_GET['error'];	?>
        <p style="color:red;"> <?php echo $error?></p><?php
    }?>

    </div><!-- form -->
