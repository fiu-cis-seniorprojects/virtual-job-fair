<?php
/* @var $this ProfileController */

?>
<br/><br/>
<h2>You already have an account with that Email:</h2><br>
<h3>Do you want to link both accounts?</h3><br>
<div class="form">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'button',
        'type'=>'primary',
        'label'=>'Link',
    )); ?><br><br>
</div><!-- form -->