<?php
/* @var $this SkillsetController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Skillsets',
);

$this->menu=array(
	array('label'=>'Create Skillset', 'url'=>array('create')),
	array('label'=>'Manage Skillset', 'url'=>array('admin')),
);
?>

<h1>Skillsets</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
