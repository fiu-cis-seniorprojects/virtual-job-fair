<script language="JavaScript">
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
        var action = 'togglematchnotifications';
        if(check)
        {
            action = 'checknotificationstate';
        }
        $.get("/JobFair/index.php/message/"+action, {"value": val}, function(data){
        data = JSON.parse(data);
        if(data["status"] == '0')
            {
                $("#myonoffswitch").prop('checked', false);
            }
            else
            {
                $("#myonoffswitch").prop('checked', true);
            }
            $("#user_lastmodified").html(data["username"]);
            $("#user_lastmodifieddate").html(data["last_modified"]);
            $("#myonoffswitch").val(data["status"]);
        });
        setTimeout("toggleNotifications(1)", 30000);
    }
</script>
<br/>

<h2>Notification Settings</h2><br/>

<div class="well">
    <?php
    if(isset($matchnotification))
    {
        $value = intval($matchnotification['status']);
        $checked = "";
        if($value == 1)
        {
            $checked = "checked";
        }
    }
    ?>
    <div style="overflow: hidden;">
        <div style="float: left;">Job Match Notifications Status:</div>
        <div style="margin-left: 200px;" class="onoffswitch">
            <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" value='<?php echo $value; ?>' id="myonoffswitch" <?php echo $checked; ?> onclick="toggleNotifications()">
            <label class="onoffswitch-label" for="myonoffswitch">
                <span class="onoffswitch-inner"></span>
                <span class="onoffswitch-switch"></span>
            </label>
        </div>
    </div>
    Last Modified by: <span id="user_lastmodified"><?php if(isset($matchnotification)){ echo $matchnotification['username']; } ?></span><br/>
    Last Modified Date: <span id="user_lastmodifieddate"><?php if(isset($matchnotification)){ echo $matchnotification['date_modified']; } ?></span><br/>
    <br/>
</div>


