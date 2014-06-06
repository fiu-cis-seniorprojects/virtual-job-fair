<?php
?>
<div id="content">

<div id="skills">
<?php foreach ($user->notifications as $n) {?>
<h5><?php echo $n->message;?></h5>
<?php }?>

<h5><?php echo $user->notifications[1]->message?></h5>
</div>


<div id="basicInfo">
<form>
First name: <input type="text" name="firstname"><br>
Last name: <input type="text" name="lastname">
<?php echo date('m/d/Y')?>
</form>

<h1>Profile for <?php echo $mas->message ?></h1>
<?php echo $usermodel->notifications->message?>

<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
</div>
</div>	
		
</div>
</div>
<?php


//$user = new User();
//$User->Email= 'tomer@hotmail.com';�
//$User->username= 'tomer';
//$User->email= 'tomer@hotmail.com';
//$User->save();

?>

