<h2><?php echo ($model->isNewRecord ? 'New' : 'Edit');?> Skill</h2>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'skillset-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('class'=>'well'),
)); ?>

<fieldset>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <label class="required" for="Skillset_name">
        Name
        <span class="required">*</span>
    </label>
    <?php


    $skills_list = Skillset::model()->getNames();
    $skill_name = $model->name;
    $this->widget('bootstrap.widgets.TbTypeahead', array(
        'name'=>'Skillset[name]',
        'id'=>'Skillset_name',
        'value'=>$skill_name,
        'options'=>array(
            'source'=>$skills_list,
            'items'=>6,
            'matcher'=>"js:function(item) {
            return ~item.toLowerCase().indexOf(this.query.toLowerCase());
        }",
        ),
    ));

    ?>


</fieldset>

<div class="form-actions">
<?php $this->widget('bootstrap.widgets.TbButton', array('type' => 'primary', 'buttonType'=>'submit', 'label'=>$model->isNewRecord ? 'Create' : 'Save')); ?>


<?php $this->widget('bootstrap.widgets.TbButton',
    array(  'label'=>'Cancel',
        'htmlOptions' => array(
            'onclick' => 'js:document.location.href="'.Yii::app()->createAbsoluteUrl("Skillset/index").'"'
        ),
    )
);
?>
</div>

<?php $this->endWidget(); ?>
