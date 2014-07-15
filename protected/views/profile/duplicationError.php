<?php
/* @var $this ProfileController */
/* @var $model model */
/* @var $form LinkTooForm */
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
<h2>Linking Error:</h2>
<h3>This account is already link to another account</h3>

<?php $this->endWidget(); ?>