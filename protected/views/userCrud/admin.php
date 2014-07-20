<h1>Manage Users</h1>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'pager' => array('cssFile' => Yii::app()->baseUrl . '/css/gridViewStyle/gridView.css'),
    'cssFile' => Yii::app()->baseUrl . '/css/gridViewStyle/gridView.css',
    //and you can even set your own css class to the grid container
    'htmlOptions' => array('class' => 'grid-view rounded'),
	'columns'=>array(

        array(
            'name' => 'registration_date',
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
			'class'=>'CButtonColumn',
            'template'=>'{update}{delete}',
            'deleteConfirmation' => 'Deleting a user will erase ALL of the information associated with the account (including job postings). Proceed?',
            'updateButtonImageUrl' => Yii::app()->baseUrl . '/css/gridViewStyle/images/' . 'gr-update.png',
            'deleteButtonImageUrl' => Yii::app()->baseUrl . '/css/gridViewStyle/images/' . 'gr-delete.png',
		),
	),
)); ?>
