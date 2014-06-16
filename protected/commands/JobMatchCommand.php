<?php
/**
 * Created by PhpStorm.
 * User: terremark
 * Date: 6/16/14
 * Time: 11:57 AM
 */

class JobMatchCommand extends CConsoleCommand {
    public function getHelp()
    {

    }

    public function run($args)
    {
        date_default_timezone_set('America/New_York');
        $now = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $pasttime = $date . " " . date('H:i:s', strtotime("-30 min"));
//        echo $now . "\n";
//        echo $date . " " . $pasttime . "\n";
        $matchnotification = MatchNotification::model()->findBySql("SELECT * FROM match_notification ORDER BY date_modified DESC limit 1");
        $active_jobs = Job::model()->find("active=:active", array(':active'=>1));
        $notfication_status = intval($matchnotification['status']);
        if($notfication_status)
        {
            echo "[*] Job Matching Notification is ON\n";
            Job::model()->findAll("post_date > '$pasttime' AND active = 1");
            return 0;
        }
        else
        {
            echo "[*] Job Matching Notification is OFF\n";
        }
    }

    public function actionSendEmail($to, $subject, $desc, $message)
    {

    }
} 