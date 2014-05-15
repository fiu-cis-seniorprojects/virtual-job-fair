<?php
/* @var $this RegisterController */

$this->breadcrumbs=array(
	'Register',
);
?>
<h1>Virtual Job Fair Registration </h1>

<div id="register">

<div class="reg" style=" margin-right:25px">
<a href="<?php echo Yii::app()->baseUrl ?>/index.php/user/StudentRegister" ><img  class="regimg" src="/JobFair/images/ico/studenticon.png"/></a>
</div>

<div class="reg">
<a href="<?php echo Yii::app()->baseUrl ?>/index.php/user/EmployerRegister" ><img class="regimg" src="/JobFair/images/ico/empicon.png"/></a>
</div>


<div class="reg" style="border-right: solid 1px rgb(192, 192, 192); height:140px; text-align:left; ">
<h2>Students</h2>
<p style=" margin-top:15px;padding:20px">Virtual Job Fair allows you to create a profile and showcase your skills and abilities for free. We also match you with current job opening based on your skills.</p>
</div>
<div class="reg" style="height:140px; text-align:left; padding-left:20px ">
<h2>Employers</h2>
<p style=" margin-top:15px; padding:20px">Virtual Job Fair allows you to make virtul conections with job seeking students. With features such as Live Video Interview, you can interview and get to know the candidates without leaving the office.</p>
</div>



</div>



