<?php

class ReminderCommand extends CConsoleCommand
{
    public function getHelp()
    {
        echo "Sends SMS reminders to users about upcoming interviews ";
    }

    public function run($args)
    {   
    	date_default_timezone_set('America/New_York');
    	$date = date('Y-m-d');
    	$time = date('H:i:s');
    	$futuretime = date('H:i:s', strtotime("+30 min"));
    	$entries = VideoInterview::model()->findAllBySql("SELECT * FROM video_interview WHERE
    	                                                    date=:date AND time BETWEEN CONVERT("  ."'"   .$time.  "'" .  ", TIME) AND
    	                                                    CONVERT("  ."'"  .$futuretime.  "'" .    ", TIME)", array(":date"=>$date));
        echo $date . " " . $time . "\n";
        echo $futuretime . "\n";
    	spl_autoload_unregister(array('YiiBase','autoload'));
    	require('Services/Twilio.php');
    	$sid = "AC1a9ec3e5aaf3135a5e4893c095be8430";
    	$token = "15871d8b55c402145f12c77dd7525644";
    	$client = new Services_Twilio($sid, $token);
    	spl_autoload_register(array('YiiBase','autoload'));
        echo "Timeframe: " . $date . " (" . $time . " - " . $futuretime . ")\nFound " . count($entries) ."interview(s)\n";
		foreach($entries as $avideo)
        {
            $infoEmployer = BasicInfo::model()->find("userid=:userid",array('userid'=>$avideo->FK_employer));
            $userEmployer = User::model()->find("id=:id",array('id'=>$avideo->FK_employer));
            $infoStudent = BasicInfo::model()->find("userid=:userid",array('userid'=>$avideo->FK_student));
            $userStudent = User::model()->find("id=:id",array('id'=>$avideo->FK_student));
            //Send message to employer
            //if($infoEmployer->allowSMS == 1 && $infoEmployer->validated == 1)
            if($infoEmployer->allowSMS == 1 && $infoEmployer->validated == 1 && $userEmployer->activated == 1) #Corrected code
            {
                echo "Sending Employer SMS [" . $infoEmployer->phone . "]\n";
                $msg = "Hello " . $userEmployer->username . " this is friendly reminder from Virtual Job Fair about your scheduled interview with ". $userStudent->username. " today at  " . $avideo->time;
                //echo $avideo->session_key . "\n";
                $client->account->messages->sendMessage("+17868375870", "+1".$infoEmployer->phone  ,$msg );
            }

            //Send message to student
            //if($infoStudent->allowSMS == 1 && $infoStudent->validated == 1)
            if($infoStudent->allowSMS == 1 && $infoStudent->validated == 1 && $userStudent->activated == 1)
            {
                echo "Sending Student SMS [" . $infoStudent->phone . "]\n";
                $msg = "Hello " . $userStudent->username . " this is friendly reminder from Virtual Job Fair about your scheduled interview with ". $userEmployer->username. " today at  " . $avideo->time;
                //echo $avideo->session_key . "\n";
                $client->account->messages->sendMessage("+17868375870", "+1".$infoStudent->phone  ,$msg );
            }
       }
    }

    public function actionSendEmail()
    {
        date_default_timezone_set('America/New_York');
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $futuretime = date('H:i:s', strtotime("+30 min"));
        echo $date . " " . $time . "\n";
        echo $futuretime . "\n";
        return 0;
    }
}




