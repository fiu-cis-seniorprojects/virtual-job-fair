<h2>API Authentication</h2>


<div class="btn-toolbar well">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label'=>'New API Key',
        'type'=>'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        //'size' => 'large',

        'htmlOptions' => array(
            'onclick' => 'js:document.location.href="'.Yii::app()->createAbsoluteUrl("ApiAuth/create").'"',

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
        array(  'name'=>'description',
            'header'=>'Description',
            'filter' => CHtml::textField('ApiAuth[description]', '', array('placeholder'=>'Search by description', 'maxlength'=>'45', 'style' => 'width: 100%' )),),
        array(  'name'=>'valid_key',
            'header'=>'Key',
            'filter' => false),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}',
            'htmlOptions'=>array('style'=>'width: 50px'),
        ),
    ),
)); ?>