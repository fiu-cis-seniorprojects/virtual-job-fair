<h1>Manage Skills</h1>

<div class="btn-toolbar well">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>'Create New Skill',
        'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'size' => 'large',

        'htmlOptions' => array(
            'onclick' => 'js:document.location.href="'.Yii::app()->createAbsoluteUrl("Skillset/create").'"',

        ),
    )); ?>

    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>'Consolidate Skills',
        'type'=>'warning', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'size' => 'large',

        'htmlOptions' => array(
            'onclick' => 'js:document.location.href="'.Yii::app()->createAbsoluteUrl("Skillset/consolidate").'"',

        ),
    )); ?>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'skillset-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'pager' => array('cssFile' => Yii::app()->baseUrl . '/css/gridViewStyle/gridView.css'),
    'cssFile' => Yii::app()->baseUrl . '/css/gridViewStyle/gridView.css',
    //and you can even set your own css class to the grid container
    'htmlOptions' => array('class' => 'grid-view rounded'),
    'columns'=>array(
        array (
            'name' => 'name',
            'type' => 'raw',
            'value' => 'CHtml::encode($data->name)',
            'filter' => CHtml::textField('Skillset[name]', '', array('placeholder'=>'Search for Skill', 'maxlength'=>'45', 'style' => 'width: 100%' )),
        ),

        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}{delete}',
            'deleteConfirmation' => 'Deleting a user will erase ALL of the information associated with the account (including job postings). Proceed?',
            'updateButtonImageUrl' => Yii::app()->baseUrl . '/css/gridViewStyle/images/' . 'gr-update.png',
            'deleteButtonImageUrl' => Yii::app()->baseUrl . '/css/gridViewStyle/images/' . 'gr-delete.png',
        ),
    ),
)); ?>
