<h1>Consolidate Skills</h1>


<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'merge-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('class'=>'well'),
)); ?>
<fieldset>
    <?php

        echo CHtml::label('Merge this skill:', 'skill_one');
        echo CHtml::textField('skill_one');

        echo CHtml::label('With this skill:', 'skill_two');
        echo CHtml::textField('skill_two');
    ?>
</fieldset>

<?php $this->widget('bootstrap.widgets.TbButton',
    array(  'label'=>'Apply Changes',
        'type' => 'primary',
        'buttonType'=>'submit',
    )
);
?>

<?php $this->endWidget(); ?>
