<?php

class JobMatchCommand extends CConsoleCommand {
    public function getHelp()
    {
        $str = "";
        $str .= "JobMatching Command Help:\n";
        $str .= "\t-i\tTime Interval in days. Used in parallel with cronjob schedule. Accepted values [1, 7, 30] days.\n";
        $str .= "\t-e\tRegistered user email address. Must be used with -u switch.\n";
        $str .= "\t-u\tRegistered user username. Must be used with -e switch.\n";
        $str .= "\t-m \tSend employers job matching results email.\n";
        $str .= "\t-h --help\tThis output.\n";
        $str .= "Ex.: ./yiic jobmatch -i 1\tSends users job match notifications every day if configured in user profile.\n";
        echo $str;
        exit;
    }

    public function buildTable($type, $ar, $interval)
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
            $table .= CHtml::openTag('tr', array('bgcolor'=>$color, 'align'=>'center'));
            $table .= CHtml::tag('td', array(), 'Job Title');
            $table .= CHtml::tag('td', array(), 'Posted');
            $table .= CHtml::tag('td', array(), 'Source');
            $table .= CHtml::tag('td', array(), 'Job Details');
            $table .= CHtml::tag('td', array(), 'Posted Recently');
            $table .= CHtml::closeTag('tr');
            #let's intercalate array elements
            $cb = (isset($ar['careerbuilder'])) ? array_slice($ar['careerbuilder'], 1) : null;
            $indeed = (isset($ar['indeed'])) ? $ar['indeed']['results']['result'] : null;
            $cp = (isset($ar['careerpath'])) ? $ar['careerpath'] : null;
            $cb_count = $indeed_count = $cp_count = 0;
            $cmp_time_cb = $cmp_time_indeed = $cmp_time_cp = "";
            $cmp_time = strtotime('- ' . strval($interval) . ' day');
            if($cb != null and count($cb) > 0)
            {
                $cb_count = count($cb);
            }
            if($indeed != null && count($indeed) > 0)
            {
                $indeed_count = count($indeed);
            }
            if($cp != null && count($cp) > 0)
            {
                $cp_count = count($cp);
            }
            $max = 0;
            if($cb_count > $indeed_count)
            {
                $max = $cb_count;
            }
            if($max < $cp_count)
            {
                $max = $cp_count;
            }
            if($max == 0)
            {
                $table .= CHtml::openTag('tr', array('bgcolor'=>$color, 'align'=>'center'));
                $table .= CHtml::tag('td', array('bgcolor'=>$color, 'align'=>'center', 'colspan'=>5), "No Matches Found");
                $table .= CHtml::closeTag('tr');
                $table .= CHtml::closeTag('table');
                return $table;
            }
            for($i = 0; $i < $max; $i++)
            {
                $job_date = null;
                if($i < $cb_count)
                {
                    $newpost = "";
                    $table .= CHtml::openTag('tr', array('bgcolor'=>$color));
                    $job = (array)$cb[$i];
                    $lnk = CHtml::link('here', $job['jobDetailsURL']);
                    $table .= CHtml::tag('td', array(), $job['title']);
                    $table .= CHtml::tag('td', array(), $job['posted']);
                    $table .= CHtml::tag('td', array(), "CarrerBuilder");
                    if(strtotime($job['posted']) >= $cmp_time)
                    {
                        $newpost = "YES";
                    }
                    $table .= CHtml::tag('td', array(), $lnk);
                    $table .= CHtml::tag('td', array(), $newpost);
                    $color = ($flag%2 == 0) ? "#FFFFFF" : "#D1E5F6";
                    $flag += 1;
                    $table .= CHtml::closeTag('tr');
                }
                if($i < $indeed_count)
                {
                    $newpost = "";
                    if($indeed_count == 1)
                    {
                        $table .= CHtml::openTag('tr', array('bgcolor'=>$color));
                        $lnk = CHtml::link('here', $indeed['url']);
                        $table .= CHtml::tag('td', array(), $indeed['jobtitle']);
                        $table .= CHtml::tag('td', array(), $indeed['date']);
                        $table .= CHtml::tag('td', array(), "Indeed");
                        $job_date = strtotime($indeed['date']);
                    }
                    else
                    {
                        $job = $indeed[$i];
                        $table .= CHtml::openTag('tr', array('bgcolor'=>$color));
                        $lnk = CHtml::link('here', $job['url']);
                        $table .= CHtml::tag('td', array(), $job['jobtitle']);
                        $table .= CHtml::tag('td', array(), $job['date']);
                        $table .= CHtml::tag('td', array(), "Indeed");
                        $job_date = strtotime($job['date']);
                    }
                    if($job_date >= $cmp_time)
                    {
                        $newpost = "YES";
                    }
                    $table .= CHtml::tag('td', array(), $lnk);
                    $table .= CHtml::tag('td', array(), $newpost);
                    $color = ($flag%2 == 0) ? "#FFFFFF" : "#D1E5F6";
                    $flag += 1;
                    $table .= CHtml::closeTag('tr');
                }
                if($i < $cp_count)
                {
                    $newpost = "";
                    $job = $cp[$i];
                    $table .= CHtml::openTag('tr', array('bgcolor'=>$color));
                    $lnk = CHtml::link('here', Yii::app()->request->hostInfo . "/JobFair/index.php/job/view/jobid/".$job["id"]);
                    $table .= CHtml::tag('td', array(), $job['title']);
                    $table .= CHtml::tag('td', array(), $job['post_date']);
                    $job_date = strtotime($job['post_date']);
                    $table .= CHtml::tag('td', array(), "CarrerPath");
                    if($job_date >= $cmp_time)
                    {
                        $newpost = "YES";
                    }
                    $table .= CHtml::tag('td', array(), $lnk);
                    $table .= CHtml::tag('td', array(), $newpost);
                    $color = ($flag%2 == 0) ? "#FFFFFF" : "#D1E5F6";
                    $flag += 1;
                    $table .= CHtml::closeTag('tr');
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
        $send_empl = false;
        $switches = Array(
            "-u",
            "-e",
            "-i",
            "-h",
            "-m",
            "--help"
        );

        if(count($args) > 0)    #TODO: This is not the best way to parse arguments. Migrate implementation to "$this->resolveRequest" or updagrade CConsoleCommand for better getopt support.
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
                        case "-m":
                            $send_empl = true;
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
        else
        {
            $this->getHelp();
        }
        $now = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $pasttime = $date . " " . date('H:i:s', strtotime("-30 min"));
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
                            $message .= $this->buildTable('student', $results, $interval);
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
            #Add fecthing for user not active or validated
            $students = User::model()->findAll("FK_usertype = 1 AND job_notification = 1 AND looking_for_job = 1 AND activated = 1 AND disable != 1");
            echo "\n::::::::::::::::::::\n[*] Matching jobs for students.\n";
            foreach($students as $st)
            {
                $message = "";
                $results = Array();
                $saved_queries = SavedQuery::model()->findAll("FK_userid=:id AND active = 1", array(':id'=>$st->id));
                if(count($saved_queries) > 0 && ($interval == intval($st->job_int_date)) && $interval > 0)
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
                        $message .= $this->buildTable('student_custom', $results, $interval);
                        $message .= "<br/>";
                    }
                    echo "[*] Sending custom search job results email to: $st->email\n";
                    User::sendEmail($st->email, "Virtual Job Fair | Job Matches", "Your Job Matches", $message);

                }
                else
                {
                    $results = Yii::app()->jobmatch->getStudentMatchJobs($st->id, $jobs);
                    if(count($results) > 0)
                    {
                        $message .= "The following jobs matched with your skills:<br/>";
                        $message .= $this->buildTable('student', $results, $interval);
                        echo "[*] Sending skill matches results email to: $st->email\n";
                        User::sendEmail($st->email, "Virtual Job Fair | Job Matches", "Your Job Matches", $message);
                    }
                }

            }
            if($send_empl)
            {
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
                    $table = $this->buildTable('', $results, $interval);
                    $message .= $table;
                    echo $this->replaceTags($message);
                    echo "[*] Sending email to $job_poster_email\n";
                    User::sendEmail($job_poster_email, "Virtual Job Fair | Job Matches", "Job Matches for $job->title", $message);
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