<?php

class JobMatchCommand extends CConsoleCommand {
    public function getHelp()
    {
        $str = "";
        $str .= "JobMatching Command Help:\n";
        $str .= "\t-i\tTime Interval in days. Used in parallel with cronjob schedule. Accepted values [1, 7, 30] days.\n";
        $str .= "\t-e\tRegistered user email address. Must be used with -u switch.\n";
        $str .= "\t-u\tRegistered user username. Must be used with -e switch.\n";
        $str .= "\t-h --help\tThis output.\n";
        $str .= "Ex.: ./yiic jobmatch -i 1\tSends users job match notifications every day if configured in user profile.\n";
        echo $str;
        exit;
    }

    public function buildTable($type, $ar)
    {
        $flag = 0;
        $color = "#D1E5F6";
        $table = CHtml::openTag('table', array());
        if($type == 'student')
        {
            $table .= CHtml::openTag('tr', array('bgcolor'=>$color, 'align'=>'center'));
            $table .= CHtml::tag('td', array(), 'Job Title');
            $table .= CHtml::tag('td', array(), 'Match Rate');
            $table .= CHtml::tag('td', array(), 'Job Details');
            $table .= CHtml::closeTag('tr');
            foreach($ar as $item)
            {
                $color = ($flag%2 == 0) ? "#FFFFFF" : "#D1E5F6";
                $table .= CHtml::openTag('tr', array('bgcolor'=>$color));
                $link = CHtml::link('here', Yii::app()->request->hostInfo . "/JobFair/index.php/job/view/jobid/".$item->id);
                $rating = number_format($item->skillrating, 2, '.', '');
                $job_title = $item->title;
                $table .= CHtml::tag('td', array(), $job_title);
                $table .= CHtml::tag('td', array(), $rating);
                $table .= CHtml::tag('td', array(), $link);
                $table .= CHtml::closeTag('tr');
                $flag += 1;
            }

        }
        elseif($type == 'student_custom')
        {
            foreach($ar as $site=>$varr)
            {
                $table .= CHtml::openTag('tr', array('bgcolor'=>$color, 'align'=>'center'));
                $table .= CHtml::tag('td', array('bgcolor'=>$color, 'align'=>'center', 'colspan'=>3), $site);
                $table .= CHtml::closeTag('tr');
                $table .= CHtml::openTag('tr', array('bgcolor'=>$color, 'align'=>'center'));
                $table .= CHtml::tag('td', array(), 'Job Title');
                $table .= CHtml::tag('td', array(), 'Posted');
                $table .= CHtml::tag('td', array(), 'Job Details');
                $table .= CHtml::closeTag('tr');
                if(count($varr) == 0)
                {
                    continue;
                }
                if($site == 'careerbuilder')
                {
                    if(count($varr) > 0)
                    {
                        for($i = 1; $i < count($varr); $i++)
                        {
                            $table .= CHtml::openTag('tr', array('bgcolor'=>$color));
                            $job = (array)$varr[$i];
                            $lnk = CHtml::link('here', $job['jobDetailsURL']);
                            $table .= CHtml::tag('td', array(), $job['title']);
                            $table .= CHtml::tag('td', array(), $job['posted']);
                            $table .= CHtml::tag('td', array(), $lnk);
                            $color = ($flag%2 == 0) ? "#FFFFFF" : "#D1E5F6";
                            $flag += 1;
                            $table .= CHtml::closeTag('tr');
                        }
                    }
                }
                elseif($site == 'indeed')
                {
                    $jobs_arr = $varr['results']['result'];
                    $jobs_count = intval($varr['totalresults']);
                    if($jobs_count > 0)
                    {
                        if($jobs_count == 1)
                        {
                            $table .= CHtml::openTag('tr', array('bgcolor'=>$color));
                            $lnk = CHtml::link('here', $jobs_arr['url']);
                            $table .= CHtml::tag('td', array(), $jobs_arr['jobtitle']);
                            $table .= CHtml::tag('td', array(), $jobs_arr['date']);
                            $table .= CHtml::tag('td', array(), $lnk);
                            $table .= CHtml::closeTag('tr');
                        }
                        else
                        {
                            foreach($jobs_arr as $job)
                            {
                                $table .= CHtml::openTag('tr', array('bgcolor'=>$color));
                                $lnk = CHtml::link('here', $job['url']);
                                $table .= CHtml::tag('td', array(), $job['jobtitle']);
                                $table .= CHtml::tag('td', array(), $job['date']);
                                $table .= CHtml::tag('td', array(), $lnk);
                                $color = ($flag%2 == 0) ? "#FFFFFF" : "#D1E5F6";
                                $flag += 1;
                                $table .= CHtml::closeTag('tr');
                            }
                        }
                    }
                }
                else
                {
                    if(count($varr) > 0)
                    {
                        foreach($varr as $job)
                        {
                            $table .= CHtml::openTag('tr', array('bgcolor'=>$color));
                            $lnk = CHtml::link('here', Yii::app()->request->hostInfo . "/JobFair/index.php/job/view/jobid/".$job["id"]);
                            $table .= CHtml::tag('td', array(), $job['title']);
                            $table .= CHtml::tag('td', array(), $job['post_date']);
                            $table .= CHtml::tag('td', array(), $lnk);
                            $color = ($flag%2 == 0) ? "#FFFFFF" : "#D1E5F6";
                            $flag += 1;
                            $table .= CHtml::closeTag('tr');
                        }
                    }
                }
            }
        }
        else
        {
            $table = CHtml::openTag('table', array());
            $table .= CHtml::openTag('tr', array('bgcolor'=>$color, 'align'=>'center'));
            $table .= CHtml::tag('td', array(), 'Student Name');
            $table .= CHtml::tag('td', array(), 'Match Rate');
            $table .= CHtml::tag('td', array(), 'Profile');
            $table .= CHtml::closeTag('tr');
            foreach($ar as $student)
            {
                if($student->looking_for_job == 0)
                {
                    continue;
                }
                $color = ($flag%2 == 0) ? "#FFFFFF" : "#D1E5F6";
                $table .= CHtml::openTag('tr', array('bgcolor'=>$color));
                $lnk = CHtml::link('Student Profile', Yii::app()->request->hostInfo . '/JobFair/index.php/profile/student/user/' . $student->username);
                $rating = number_format($student->skillrating, 2, '.', '');
                $st_name = $student->first_name . ' ' . $student->last_name;
                $table .= CHtml::tag('td', array(), $st_name);
                $table .= CHtml::tag('td', array(), $rating);
                $table .= CHtml::tag('td', array(), $lnk);
                $table .= CHtml::closeTag('tr');
                $flag += 1;
            }
            $table .= CHtml::closeTag('table');
        }
        $table .= CHtml::closeTag('table');
        return $table;
    }

    public function run($args)
    {
        date_default_timezone_set('America/New_York');
        $new_active_user = false;
        $nau_info = array();
        $interval = 0;

        $switches = Array(
            "-u",
            "-e",
            "-i",
            "-h",
            "--help"
        );

        if(count($args) > 0)
        {
            for($j = 0; $j < count($args); $j++)
            {
                if(in_array($args[$j], $args))
                {
                    switch($args[$j])
                    {
                        case "-u":
                            $new_active_user = true;
                            $nau_info['username'] = $args[$j+1];
                            break;
                        case "-e":
                            $new_active_user = true;
                            $nau_info['email'] = $args[$j+1];
                            break;
                        case "-i":
                            $interval = intval($args[$j+1]);
                            if(!in_array($interval, Array(1,7,30)))
                            {
                                echo "[INVALID] Invalid interval value.\n";
                                $this->getHelp();
                            }
                            break;
                        case "-h":
                        case "--help":
                            $this->getHelp();
                    }

                }
            }
        }
        $now = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $pasttime = $date . " " . date('H:i:s', strtotime("-30 min"));
//        echo $now . "\n";
//        echo $date . " " . $pasttime . "\n";
        $matchnotification = MatchNotification::model()->findBySql("SELECT * FROM match_notification ORDER BY date_modified DESC limit 1");
        $notfication_status = intval($matchnotification['status']);
        if($notfication_status)
        {
            if($new_active_user)
            {
                if(isset($nau_info['email']) && $nau_info['email'] != '' && isset($nau_info['username']) && $nau_info['username'] != '')
                {
                    $jobs = Job::model()->findAll("active = 1");
                    $student = User::model()->find("username=:username", array(':username'=>$nau_info['username']));
                    if($student['username'] != null && $student['looking_for_job'] == 1 && $student['job_notification'] == 1)
                    {
                        $message = "";
                        $results = Yii::app()->jobmatch->getStudentMatchJobs(intval($student['id']), $jobs);
                        if(count($results) > 0)
                        {
                            $message .= $this->buildTable('student', $results);
                            User::sendEmail($student->email, "Virtual Job Fair | Job Matches", "Your Job Matches", $message);
                        }
                        return;
                    }
                    return;
                }
                return;
            }
            echo "[*] Job Matching Notification is ON\n";
            $jobs = Job::model()->findAll("post_date > '$pasttime' AND active = 1");
            $students = User::model()->findAll("FK_usertype = 1 AND job_notification = 1 AND looking_for_job = 1");
            echo "\n::::::::::::::::::::\n[*] Matching jobs for students.\n";
            foreach($students as $st)
            {
                $message = "";
                //check user interval to send email notification appropiatedly
                $results = Array();
                if($interval > 0 && $interval == intval($st->job_int_date))
                {
                    $saved_queries = SavedQuery::model()->findAll("FK_userid=:id, active = 1", array(':id'=>$st->id));
                    if(count($saved_queries) > 0)
                    {
                        $word = "query";
                        if(count($saved_queries) > 1)
                        {
                            $word = "queries";
                        }
                        $message .= "Jobs matching your custom $word:<br/>";
                        foreach($saved_queries as $query)
                        {
                            $results = Yii::app()->jobmatch->customJobSearch($query->query, $query->location);
                            $message .= "Matches for query [$query->query]<br/>";
                            $message .= $this->buildTable('student_custom', $results);
                            $message .= "<br/>";
                        }
                        echo "[*] Sending email to $st->email\n";
                        User::sendEmail($st->email, "Virtual Job Fair | Job Matches", "Your Job Matches", $message);
                    }
                    else
                    {
                        $results = Yii::app()->jobmatch->getStudentMatchJobs($st->id, $jobs);
                        if(count($results) > 0)
                        {
                            $message .= "The following jobs matched with your skills:<br/>";
                            $message .= $this->buildTable('student', $results);
                            echo "[*] Sending email to $st->email\n";
                            User::sendEmail($st->email, "Virtual Job Fair | Job Matches", "Your Job Matches", $message);
                        }
                    }
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
                $table = $this->buildTable('', $results);
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