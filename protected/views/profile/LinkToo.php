<?php
/* @var $this ProfileController */
/* @var first_name  */
/* @var last_name  */
/* @var $email  */
/* @var $picture  */
/* @var $mesg   */
/* @var $phone   */
/* @var $city   */
/* @var $state   */
/* @var $about_me   */
?>
    <script type="text/javascript">
        $(document).ready(function()
        {
            $('#saveQmodal').modal('show');

        });


        //data = $data;
        function formSend(form, data, hasError)
        {

            var data=$("#link_accounts").serialize();

            $.ajax({
                type: 'POST',
                url: '<?php echo Yii::app()->createAbsoluteUrl("Profile/UserChoice"); ?>',
                data:data,
                success:function(data){
                    window.location.href = <?php echo '"'.  $this->createAbsoluteUrl('Profile/LinkNotification/mesg/' . $mesg) . '"'; ?>;
                },
                error: function(data) { // if error occured
                    alert("Error occured.please try again");
                    alert(data);
                },

                dataType:'html'
            });

            return false;
        }

    </script>

<!--Modal-->
<div class="modal hide fade" id="saveQmodal" tabindex="-1">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Merge conflict(s) found</h4>
        </div>
        <div class="modal-body">
            Choose the one you want to keep: <br>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'link_accounts',
    'enableClientValidation' => true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'validateOnChange'=>false,
        'afterValidate'=>'js:formSend'
    ),
    'htmlOptions'=>array('class'=>'well'),


));
?>

<?php //echo $form->hiddenField( $model, 'next',array('value'=>$email)); ?>

<?php
    //get current user information
    $user = User::getCurrentUser();
    $basic_info = BasicInfo::model()->findByAttributes(array('userid'=>$user->id));
    $nothing = false;

    //choosing a picture
    if(($picture!= null)&&($user->image_url != null)&&($picture != $user->image_url)){
        $nothing = true;
        echo 'Choose your profile picture';
        $image =CHtml::image($picture,'', array('width'=>100, 'height'=>60));
        $user_image =CHtml::image($user->image_url,'', array('width'=>100, 'height'=>60));
        $model->profilePic = $user->image_url;
        echo $form->radioButtonList($model, 'profilePic',array( $user->image_url=> $user_image, $picture=>$image), array('checked'=>1));
        echo '<br/>';
    }
    if($user->image_url == null){
        $nothing = true;
        echo $form->hiddenField( $model, 'profilePicVar',array('value'=>$first_name));
    }
    //choosing a name
    if(($first_name!= null)&&($user->first_name != null)&&($first_name != $user->first_name)){
        $nothing = true;
        echo 'Choose your first name:';
        $model->firstname = $user->first_name;
        echo $form->radioButtonList($model, 'firstname', array($user->first_name=>$user->first_name, $first_name=>$first_name));
        echo '<br/>';
    }
    if($user->first_name == null){
        $nothing = true;
        echo $form->hiddenField( $model, 'firstnamevar',array('value'=>$first_name));
    }
    //choosing a last name
    if(($last_name!= null)&&($user->last_name != null)&&($last_name != $user->last_name)){
        $nothing = true;
        echo 'Choose your last name:';
        $model->lastname = $user->last_name;
        echo $form->radioButtonList($model, 'lastname', array($user->last_name=>$user->last_name,$last_name=>$last_name));
        echo '<br/>';
    }
    if($user->last_name == null){
        $nothing = true;
        echo $form->hiddenField( $model, 'lastnamevar',array('value'=>$last_name));
    }
    //choosing an e-mail
    if(($email != null)&&($user->email != null)&&($email != $user->email)){
        $nothing = true;
        echo 'Choose the Email you want to keep:';
        $model->email = $user->email;
        echo $form->radioButtonList($model, 'email', array($user->email=>$user->email,$email=>$email));
        echo '<br/>';
    }
    if($user->email == null){
        $nothing = true;
        echo $form->hiddenField( $model, 'emailvar',array('value'=>$email));
    }

    //choosing a phone
    if(($basic_info != null)&&($phone != null)&&($basic_info->phone != null)&&($phone != $basic_info->phone)){
        $nothing = true;
        echo 'Choose the phone number you want to keep:';
        $model->phone = $basic_info->phone;
        echo $form->radioButtonList($model, 'phone', array($basic_info->phone=>$basic_info->phone,$phone=>$phone));
        echo '<br/>';
    }
    if(($basic_info != null)&&($basic_info->phone == null)){
        $nothing = true;
        echo $form->hiddenField( $model, 'phonevar',array('value'=>$phone));
    }

    //choosing a city
    if(($basic_info != null)&&($city != null)&&($basic_info->city != null)&&($city != $basic_info->city)){
        $nothing = true;
        echo 'Choose your city:';
        $model->city = $basic_info->city;
        echo $form->radioButtonList($model, 'city', array($basic_info->city=>$basic_info->city,$city=>$city));
        echo '<br/>';
    }
    if(($basic_info != null)&&($basic_info->city == null)){
        $nothing = true;
        echo $form->hiddenField( $model, 'cityvar',array('value'=>$city));
    }

    //choosing a state
    if(($basic_info != null)&&($state != null)&&($basic_info->state != null)&&($state != $basic_info->state)){
        $nothing = true;
        echo 'Choose your state:';
        $model->state = $basic_info->state;
        echo $form->radioButtonList($model, 'state', array($basic_info->state=>$basic_info->state,$state=>$state));
        echo '<br/>';
    }
    if(($basic_info != null)&& ($basic_info->state == null)){
        $nothing = true;
        echo $form->hiddenField( $model, 'statevar',array('value'=>$state));
    }

    //choosing a about_me
    if(($basic_info != null)&&($about_me != null)&&($basic_info->about_me != null)&&($about_me != $basic_info->about_me)){
        $nothing = true;
        echo 'Say something about you:';
        $model->about_me = $basic_info->about_me;
        echo $form->radioButtonList($model, 'about_me', array($basic_info->about_me=>$basic_info->about_me,$about_me=>$about_me));
        echo '<br/>';
    }
    if(($basic_info != null)&& ($basic_info->about_me == null)){
        $nothing = true;
        echo $form->hiddenField( $model, 'about_me_var',array('value'=>$about_me));
    }
    elseif($nothing == false)
    {
        $this->redirect("/JobFair/index.php/profile/view");
    }



    //messages to show completion
    echo $form->checkBoxListRow($model, 'toPost',array() , array('type'=>"hidden"));
    echo $form->hiddenField( $model, 'toPost',array('value'=>$mesg));
?>


</div>
<div class="modal-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', array('type'=>'primary',
        'size' => 'normal', 'buttonType'=>'submit', 'label'=>'Fix Conflicts')); ?>

    <?php $this->endWidget(); ?>
</div>
</div><!-- /.modal-content -->
</div><!-- /.modal -->