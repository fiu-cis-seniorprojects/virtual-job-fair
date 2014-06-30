<?php
/* @var $this ApiAuthController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Api Keys',
);

$this->menu=array(
	array('label'=>'Create API Key', 'url'=>array('create')),
	array('label'=>'Manage API Key', 'url'=>array('admin')),
);
?>

<h1>API Key Management</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
