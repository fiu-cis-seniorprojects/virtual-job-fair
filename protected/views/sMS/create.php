<?php
/* @var $this SMSController */
/* @var $model SMS */

$this->breadcrumbs=array(
	'Sms'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SMS', 'url'=>array('index')),
	array('label'=>'Manage SMS', 'url'=>array('admin')),
);
?>

<h1>Create SMS</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>