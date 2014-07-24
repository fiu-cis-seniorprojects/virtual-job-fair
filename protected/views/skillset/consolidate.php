<h1>Merge Skills</h1>


<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'merge-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('class'=>'well'),
)); ?>
<fieldset>

    <p class="note"><span class="required">Note:</span> Skill A will be replaced by Skill B.</p>

    <?php
    if (isset($error) && $error != '')
    {
        echo "<div class='alert alert-block alert-error'><p>Please fix the following input errors:</p><ul><li>"
            .
            $error
            .
            "</li></ul></div> ";
    }
    ?>

    <?php
        $skills_list = Skillset::model()->getNames();

        echo CHtml::label('Skill A:', 'skill_one');

        $this->widget(  'bootstrap.widgets.TbTypeahead', array(
                'name'=>'skill_one',
                'id'=>'skill_one',
                'value'=>$skill1,
                'options'=>array(
                    'source'=>$skills_list,
                    'items'=>6,
                    'matcher'=>"js:function(item)
                                            {
                                                return ~item.toLowerCase().indexOf(this.query.toLowerCase());
                                            }",
                ),
            )
        );

        echo CHtml::label('Skill B:', 'skill_two');
        $this->widget(  'bootstrap.widgets.TbTypeahead', array(
                        'name'=>'skill_two',
                        'id'=>'skill_two',
                        'value'=>$skill2,
                        'options'=>array(
                            'source'=>$skills_list,
                            'items'=>6,
                            'matcher'=>"js:function(item)
                                        {
                                            return ~item.toLowerCase().indexOf(this.query.toLowerCase());
                                        }",
                            ),
                        )
                    );

//        echo CHtml::textField('skill_two');
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
