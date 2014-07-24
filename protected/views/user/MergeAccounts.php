<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>
<?php $user = User::getCurrentUser(); ?>
<br/><br/><br/>
<div class="form">
<?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id'=>'link_accounts',
        'enableClientValidation' => true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
            'validateOnChange'=>false,
            //'afterValidate'=>'js:formSend'
        ),
        'htmlOptions'=>array('class'=>'well'),

    ));
?>


   <!-- <h1> <?php echo $user->first_name?> <?php  echo $user->last_name?>!</h1>
    <h2>Your username is: <?php echo $user->username?></h2>-->

    <h4>Please Enter The Information Needed For Merging Accounts: </h4><br>

	<?php if ($error != '') {?>
	<p style="color:red;"> <?php echo $error?></p>
	<?php }?>
    <div class="row" style="padding-left:50px;">
        <?php echo $form->labelEx($model,'Username of your other account'); ?>
        <?php echo $form->textField($model,'email',array( 'style'=>'width: 200px')); ?>
    </div>
    <div class="row" style="padding-left:50px;">
		<?php echo $form->labelEx($user,'Password of your other account'); ?>
		<?php echo $form->passwordField($model, 'password',array( 'style'=>'width: 200px')); ?>
		<?php echo $form->error($model,'username',array( 'style'=>'width: 200px')); ?>
	</div>

	<div class="row buttons" style="padding-left:50px;">
		<?php echo CHtml::submitButton('Merge', array("class"=>"btn btn-primary")); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->