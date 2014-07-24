<h2>Manage Users</h2>


<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed well',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'template'=>"{summary}{items}\n{pager}",
    'summaryText'=>"Displaying {start} - {end} of {count}",
    'columns'=>array(

        array(
            'name' => 'registration_date',
            'header' => 'Registered',
            'value' => 'date("m/d/Y",strtotime($data->registration_date))',
            'filter' => false
        ),

        array (
            'name' => 'username',
            'type' => 'raw',
            'value' => 'CHtml::encode($data->username)',
            'filter' => CHtml::textField('User[username]', '', array('placeholder'=>'Search for Username', 'maxlength'=>'45', 'style' => 'width: 90%' )),
        ),

        array (
            'name' => 'email',
            'type' => 'raw',
            'value' => 'CHtml::encode($data->email)',
            'filter' => CHtml::textField('User[email]', '', array('placeholder'=>'Search for Email', 'maxlength'=>'45', 'style' => 'width: 90%' )),
        ),

        array (
            'name' => 'first_name',
            'type' => 'raw',
            'value' => 'CHtml::encode($data->first_name)',
            'filter' => CHtml::textField('User[first_name]', '', array('placeholder'=>'Search for First Name', 'maxlength'=>'45', 'style' => 'width: 90%' )),
        ),

        array (
            'name' => 'last_name',
            'type' => 'raw',
            'value' => 'CHtml::encode($data->last_name)',
            'filter' => CHtml::textField('User[last_name]', '', array('placeholder'=>'Search for Last Name', 'maxlength'=>'45', 'style' => 'width: 90%' )),
        ),

        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}',
            'htmlOptions'=>array('style'=>'width: 50px'),
        ),
    ),
)); ?>
