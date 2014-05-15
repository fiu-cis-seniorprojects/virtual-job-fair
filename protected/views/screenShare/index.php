<style>
body
{
background-image: -webkit-linear-gradient(bottom left, #FFFFFF 0%, #00A3EF 100%);
color:gray;
}
</style>

<?php
/* @var $this ScreenShareController */
/* @var $dataProvider CActiveDataProvider */

 
  Yii::app()->bootstrap->registerAllCss(); 
 $this->beginWidget('bootstrap.widgets.TbHeroUnit', array(
    'heading'=>'No active Screen',
)); 
 ?>
    <h3>Please try again when a screen has been shared.</h3>
     
    
    <?php $this->endWidget(); ?>