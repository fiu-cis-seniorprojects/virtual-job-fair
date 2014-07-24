<h2>Manage Skills</h2>


<div class="btn-toolbar well">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>'New Skill',
        'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        //'size' => 'large',

        'htmlOptions' => array(
            'onclick' => 'js:document.location.href="'.Yii::app()->createAbsoluteUrl("Skillset/create").'"',

        ),
    )); ?>

    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>'Merge Skills',
        'type'=>'warning', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        //'size' => 'large',

        'htmlOptions' => array(
            'onclick' => 'js:document.location.href="'.Yii::app()->createAbsoluteUrl("Skillset/consolidate").'"',

        ),
    )); ?>
</div>


<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed well',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'template'=>"{summary}{items}\n{pager}",
    'summaryText'=>"Displaying {start} - {end} of {count}",
    'columns'=>array(
        array(  'name'=>'name',
                'header'=>'Name',
                'filter' => CHtml::textField('Skillset[name]', '', array('placeholder'=>'Search for skill', 'maxlength'=>'45', 'style' => 'width: 100%' )),),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}',
            'htmlOptions'=>array('style'=>'width: 50px'),
        ),
    ),
)); ?>