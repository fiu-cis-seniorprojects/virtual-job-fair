<h2>Manage Job Postings</h2>


<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed well',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'template'=>"{summary}{items}\n{pager}",
    'summaryText'=>"Displaying {start} - {end} of {count}",
    'columns'=>array(

        array(
            'name' => 'post_date',
            'value' => 'date("m/d/Y",strtotime($data->post_date))',
            'filter' => false
        ),

        array(
            'name' => 'deadline',
            'value' => 'date("m/d/Y",strtotime($data->deadline))',
            'filter' => false
        ),

        array (
            'name' => 'title',
            'type' => 'raw',
            'value' => 'CHtml::encode($data->title)',
            'filter' => CHtml::textField('Job[title]', '', array('placeholder'=>'Search for Job Title', 'maxlength'=>'45', 'style' => 'width: 90%' )),
        ),

        array (
            'name' => 'type',
            'type' => 'raw',
            'value' => 'CHtml::encode($data->type)',
            'filter' => CHtml::textField('Job[type]', '', array('placeholder'=>'Search for Job Type', 'maxlength'=>'45', 'style' => 'width: 90%' )),
        ),

        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{delete}',
            'deleteConfirmation' => 'Deleting a job will also delete ALL the information related to it (ex: applicants, etc). Proceed?',
            'htmlOptions'=>array('style'=>'width: 50px'),
        ),
    ),
)); ?>
