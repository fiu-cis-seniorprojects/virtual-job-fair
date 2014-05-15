<?php
/* @var $this SMSController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Sms',
);

$this->menu=array(
	array('label'=>'Create SMS', 'url'=>array('create')),
	array('label'=>'Manage SMS', 'url'=>array('admin')),
);
?>

<h1>Sms</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
