<?php
/* @var $this SMSController */
/* @var $model SMS */

$this->breadcrumbs=array(
	'Sms'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SMS', 'url'=>array('index')),
	array('label'=>'Create SMS', 'url'=>array('create')),
	array('label'=>'View SMS', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage SMS', 'url'=>array('admin')),
);
?>

<h1>Update SMS <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>