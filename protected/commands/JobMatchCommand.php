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
            $jobs = Job::model()->findAll("post_date > '$pasttime' AND active = 1");
            foreach($jobs as $job)
            {
                $message = "";
                $job_poster_id = $job->FK_poster;
                $job_poster_info = User::model()->findByPk($job_poster_id);
//                if(!$job_poster_info->job_notification)
//                {
//                    echo "[*] User $job_poster_info->username has notifications OFF\n";
//                    continue;
//                }
                $job_poster_email = $job_poster_info->email;
                echo "\n[*] Working on jobid $job->id : $job->title\n";
                $results = Yii::app()->jobmatch->getJobStudentsMatch($job->id);
                if(count($results) == 0)
                {
                    echo "[*] No student matches found\n";
                    continue;
                }
                $message .= "The following students matched this job posting:<br/>";
                foreach($results as $student)
                {
                    $message .= "$student->first_name $student->last_name : $student->email<br/>";
                }
                echo $this->replaceTags($message);
                echo "[*] Sending email to $job_poster_email\n";
                //User::sendEmail($job_poster_email, "Virtual Job Fair | Job Matches", "Job Matches for $job->title", $message);
            }
            $students = User::model()->findAll("FK_usertype = 1 AND job_notification = 1");
            echo "\n::::::::::::::::::::\n[*] Matching jobs for students.";
            foreach($students as $st)
            {
                $message = "";
                $results = Yii::app()->jobmatch->getStudentMatchJobs($st->id, $jobs);
                if(count($results) > 0)
                {
                    $message .= "The following jobs matched with your skills:<br/>";
                    foreach($results as $j)
                    {
                        $message .= "$j->title : $j->post_date<br/>";
                    }
                    echo "[*] Sending email to $st->email\n";
                    User::sendEmail($st->email, "Virtual Job Fair | Job Matches", "Job Matches for $job->title", $message);
                }
            }
            return 0;
        }
        else
        {
            echo "[*] Job Matching Notification is OFF\n";
        }
    }

    public function replaceTags($str)
    {
        $tags = array('</p>','<br />','<br/>','<br>','<hr />','<hr>','</h1>','</h2>','</h3>','</h4>','</h5>','</h6>');
        return str_replace($tags, "\n", $str);
    }
} 