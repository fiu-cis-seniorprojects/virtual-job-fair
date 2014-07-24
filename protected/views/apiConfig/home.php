<!--API BULK IMPORT FORM-->
<h2><?php echo 'Import Jobs from CareerPath'; ?></h2>
<br>


<script type="text/javascript">
    function formSend(form, data, hasError)
    {
        $('#buttonImportJobs').attr('disabled','disabled');
        var data=$("#careerpath-bulkimport-form").serialize();
        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("ApiConfig/importJobs"); ?>',
            data:data,
            success:function(data){
                alert(data);
                $('#buttonImportJobs').removeAttr('disabled');
            },
            error: function(data) { // if error occured
                alert("Error occured.please try again");
                alert(data);
                $('#buttonImportJobs').removeAttr('disabled');
            },

            dataType:'html'
        });

        return false;
    }

    function toggleNotifications(check)
    {
        var val = $('#myonoffswitch').val();
        if(val == '1')
        {
            $('#myonoffswitch').val('0');
        }
        else
        {
            $('#myonoffswitch').val('1');
        }
        var action = 'toggleapistatus';
        if(check)
        {
            action = 'checkapistatus';
        }
        $.get("/JobFair/index.php/ApiConfig/"+action, {"value": val}, function(data){
            data = JSON.parse(data);
            if(data["status"] == '0')
            {
                $("#myonoffswitch").prop('checked', false);
            }
            else
            {
                $("#myonoffswitch").prop('checked', true);
            }
            $("#myonoffswitch").val(data["status"]);
        });
        setTimeout("toggleNotifications(1)", 15000);
    }
</script>

<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'careerpath-bulkimport-form',
    'enableClientValidation' => true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'validateOnChange'=>false,
        'afterValidate'=>'js:formSend'
    ),
    'htmlOptions'=>array('class'=>'well'),


    ));
?>

<?php echo $form->errorSummary($model); ?>

<?php
$checked = '';
$value = 0;
if (isset($api_status) && !is_null($api_status))
{
    if ($api_status['status'] == 1)
    {
        $value = 1;
        $checked = 'checked';
    }
}
?>
<div style="overflow: hidden;">
    <div style="float: left;">CareerPath API Status:</div>
    <div style="margin-left: 150px;" class="onoffswitch">
        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" value='<?php echo $value; ?>' id="myonoffswitch" <?php echo $checked; ?> onclick="toggleNotifications()">
        <label class="onoffswitch-label" for="myonoffswitch">
            <span class="onoffswitch-inner"></span>
            <span class="onoffswitch-switch"></span>
        </label>
    </div>
</div>
<br>

<?php echo $form->labelEx($model,'dateFrom'); ?>
<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name' => 'ApiConfigForm[dateFrom]',
    'value' => date('Y-m-d', strtotime('last month')),
    'options' => array(
        'showAnim' => 'fold',
        'dateFormat'=> 'yy-mm-dd',
    ),
    'htmlOptions' => array(
        'style' => 'height:20px; width: 200px'
    )
));?>

<?php echo $form->labelEx($model,'dateTo'); ?>
<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name' => 'ApiConfigForm[dateTo]',
    'value' => date('Y-m-d'),
    'options' => array(
        'showAnim' => 'fold',
        'dateFormat'=> 'yy-mm-dd',
    ),
    'htmlOptions' => array(
        'style' => 'height:20px; width: 200px'
    )
));?>

<?php echo $form->checkboxRow($model, 'allowExpired'); ?>

<div class="form-actions">

<?php $this->widget('bootstrap.widgets.TbButton', array('id' => 'btnBulkImport',
                                                        'type'=>'primary',
                                                        'size' => 'normal',
                                                        'buttonType'=>'submit',
                                                        'label'=>'Import Jobs',
                                                        'htmlOptions'=>array('id'=>'buttonImportJobs'),));
?>
</div>

<?php $this->endWidget(); ?>

