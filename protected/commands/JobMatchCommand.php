<?php

class JobMatchCommand extends CConsoleCommand {
    public function getHelp()
    {

    }

    public function buildTable($type, $ar)
    {
        $flag = 0;
        $color = "#D1E5F6";
        $table = "";
        if($type == 'student')
        {
            $table = CHtml::openTag('table', array('width'=>'100%'));
            $table .= CHtml::openTag('tr', array('bgcolor'=>$color));
            $table .= CHtml::tag('td', array(), 'Job Title');
            $table .= CHtml::tag('td', array(), 'Match Rate');
            $table .= CHtml::tag('td', array(), 'Job Details');
            $table .= CHtml::closeTag('tr');
            foreach($ar as $item)
            {
                $color = ($flag%2 == 0) ? "#FFFFFF" : "#D1E5F6";
                $table .= CHtml::openTag('tr', array('bgcolor'=>$color));
                $link = CHtml::link('Job Deatils', "http://" . Yii::app()->request->hostInfo . "/JobFair/index.php/job/view/jobid/".$item->id);
                $rating = number_format($item->skillrating, 2, '.', '');
                $job_title = $item->title;
                $table .= CHtml::tag('td', array(), $job_title);
                $table .= CHtml::tag('td', array(), $rating);
                $table .= CHtml::tag('td', array(), $link);
                $table .= CHtml::closeTag('tr');
                $flag += 1;
            }
            $table .= CHtml::closeTag('table');
        }
        else
        {

        }
        return $table;
    }

    public function run($args)
    {
        date_default_timezone_set('America/New_York');
        $new_active_user = false;
        $nau_info = array();
        if(count($args) > 0)
        {
            $new_active_user = true;
            $nau_info['username'] = explode('=', $args[0])[1];
            $nau_info['email'] = explode('=', $args[1])[1];
        }
//        $table = CHtml::openTag('table');
//        $table .= CHtml::openTag('tr', array('bgcolor'=>'red'));
//        $table .= CHtml::tag('td', array(), 'Student Name');
//        $table .= CHtml::tag('td', array(), 'Match Rate');
//        $table .= CHtml::tag('td', array(), 'Profile');
//        $table .= CHtml::closeTag('tr');
//        $table .= CHtml::closeTag('table');
//        echo $table;
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
            //echo "[*] Job Matching Notification is ON\n";
            $jobs = Job::model()->findAll("post_date > '$pasttime' AND active = 1");
            $flag = 0;
            if($new_active_user)
            {
                $student = User::model()->find("username=:username", array(':username'=>$nau_info['username']));
                if($student['username'] != null)
                {
                    $message = "";
                    //$message = "The following jobs matched with your skills:";
                    $results = Yii::app()->jobmatch->getStudentMatchJobs(intval($student['id']), $jobs);
                    if(count($results) > 0)
                    {
                        $message .= $this->buildTable('student', $results);
                        User::sendEmail($student->email, "Virtual Job Fair | Job Matches", "Your Job Matches", $message);
                    }
                    return;
                }
            }
            $students = User::model()->findAll("FK_usertype = 1 AND job_notification = 1 AND looking_for_job = 1");
            echo "\n::::::::::::::::::::\n[*] Matching jobs for students.\n";
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
                    User::sendEmail($st->email, "Virtual Job Fair | Job Matches", "Your Job Matches", $message);
                }
            }

            foreach($jobs as $job)
            {
                $message = "";
                $job_poster_info = User::model()->findByPk($job->FK_poster);
                if(!$job_poster_info->job_notification)
                {
                    echo "[*] Employer $job_poster_info->username has notifications OFF\n";
                    continue;
                }
                $job_poster_email = $job_poster_info->email;
                echo "\n[*] Working on jobid $job->id : $job->title\n";
                $results = Yii::app()->jobmatch->getJobStudentsMatch($job->id);
                if(count($results) == 0)
                {
                    echo "[*] No student matches found\n";
                    continue;
                }
                $message .= "The following students matched this job posting:<br/>";
                $color = "#D1E5F6";
                $table = CHtml::openTag('table', array('width'=>'100%'));
                $table .= CHtml::openTag('tr', array('bgcolor'=>$color));
                $table .= CHtml::tag('td', array(), 'Student Name');
                $table .= CHtml::tag('td', array(), 'Match Rate');
                $table .= CHtml::tag('td', array(), 'Profile');
                $table .= CHtml::closeTag('tr');
                foreach($results as $student)
                {
                    $color = ($flag%2 == 0) ? "#FFFFFF" : "#D1E5F6";
                    $table .= CHtml::openTag('tr', array('bgcolor'=>$color));
                    $lnk = CHtml::link('Student Profile', Yii::app()->request->hostInfo . '/JobFair/index.php/profile/student/user/' . $student->username);
                    $rating = number_format($student->skillrating, 2, '.', '');
                    $st_name = $student->first_name . ' ' . $student->last_name;
                    $table .= CHtml::tag('td', array(), $st_name);
                    $table .= CHtml::tag('td', array(), $rating);
                    $table .= CHtml::tag('td', array(), $lnk);
//                    $message .= "$student->first_name $student->last_name | $rating | $lnk<br/>";
                    $table .= CHtml::closeTag('tr');
                    $flag += 1;
                }
                $table .= CHtml::closeTag('table');
                $message .= $table;
                echo $this->replaceTags($message);
                echo "[*] Sending email to $job_poster_email\n";
                User::sendEmail($job_poster_email, "Virtual Job Fair | Job Matches", "Job Matches for $job->title", $message);
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