<?php
require ('PasswordHash.php');

class ApiConfigController extends Controller
{

    public function actionHome()
    {
        if (!User::isCurrentUserAdmin())
            Yii::app()->end();

        $api_status = ApiStatus::getFirst();
        $model = new ApiConfigForm();

        if(isset($_POST['ApiConfigForm']))
        {
            $model->attributes = $_POST['ApiConfigForm'];
            if(Yii::app()->getRequest()->getIsAjaxRequest()) {
                echo CActiveForm::validate(array($model));
                Yii::app()->end();
            }
        }
        $this->render('home', array('model' => $model, 'api_status' => $api_status));
    }

    public function actionImportJobs()
    {
        if (!User::isCurrentUserAdmin())
            Yii::app()->end();

        if(isset($_POST['ApiConfigForm']))
        {
            $model = new ApiConfigForm();
            $model->attributes = $_POST['ApiConfigForm'];

            // API request to CareerPath
            $this->careerPathSync($model->dateFrom, $model->dateTo, $model->allowExpired);
        }
        Yii::app()->end();
    }

    public function actionToggleAPIStatus()
    {
        if (!User::isCurrentUserAdmin())
            Yii::app()->end();

        $bit = intval($_GET['value']);
        $bit = ($bit == 0) ? 1 : 0;
        $stat = new ApiStatus();
        $stat->status = $bit;
        $stat->date_modified = date('Y-m-d H:i:s');
        $stat->save();
        $data = array('status'=>$stat->status);
        echo CJSON::encode($data);
    }

    public function actionCheckAPIStatus()
    {
        if (!User::isCurrentUserAdmin())
            Yii::app()->end();

        $status = ApiStatus::getFirst();
        $data = array('status'=>$status['status']);
        echo CJSON::encode($data);
    }

    public function actionIndex()
    {

        $this->actionHome();
    }

    // synchronize with the careerpath API
    protected function careerPathSync($startDate, $endDate, $allowExpired)
    {
        // using test URL retrieve mock json objects
        // here I would request a date range, since this script runs daily as a cron job
        //
        $request = Yii::app()->curl->run('http://cis.fiu.edu/datarepo/seniorproject/?key=736ab950bdca80a4bae6bf567070028b&pstart=' . $startDate . '&pend=' . $endDate . '&debug=false');
        $job_postings = CJSON::decode($request->getData());

        $hasher = new PasswordHash(8, false);
        // keep track of new jobs
        $new_jobs_count = 0;

        // check each object to see if it has been posted already:
        // criteria for duplicate jobs:
        // - same title, description and expiration date
        foreach ($job_postings as $job_posting)
        {
            // dissect scis job posting information
            $jp_postedTime = $job_posting['PostedTime'];
            $jp_expireTime = $job_posting['ExpireTime'];
            $jp_company = $job_posting['Company'];
            $jp_position = $job_posting['Position'];
            $jp_company_url = $job_posting['URL'];
            $jp_company_background = $job_posting['Background'];
            $jp_description = $job_posting['Description'];
            $jp_duties = $job_posting['Duties'];
            $jp_qualifications = $job_posting['Qualifications'];
            $jp_company_email = $job_posting['Email'];
            $jp_posted_by = $job_posting['PostedBy'];
            $jp_id = $hasher->HashPassword($jp_postedTime . $jp_expireTime . $jp_position . $jp_posted_by);//$job_posting['ID'];
            //$jp_posting_format = $job_posting['Format']; dont care about this, ask joshua

            // check expiration
            $expire_time = strtotime($jp_expireTime);
            $today_time = strtotime('Today');
            if (!$allowExpired && ($expire_time < $today_time))
            {
                // do not post expired jobs
                continue;
            }

            // attempt to find user in database (by email) that corresponds to the job posting
            $user_found = User::model()->find('email=:jp_company_email', array(':jp_company_email' => $jp_company_email));

            // if  user not found in database, create a new 'dummy' user for this posting
            if (count($user_found) <= 0)
            {
                // user info (exclude first name and last name)
                $new_user = new User();
                $new_user->email = $jp_company_email;
                $new_user->activated = 1; // activate their account, and force them to retreive password (if they ever want to login)
                // generate username from email
                $user_name = str_replace(array('@', '.'), '_', $jp_company_email);
                $new_user->username = $user_name;
                $new_user->FK_usertype = 2; // employer type
                $new_user->registration_date = new CDbExpression('NOW()');
                $new_user->image_url = '/JobFair/images/profileimages/user-default.png';

                // hash the password before storing it into the database
                $new_user->password = $hasher->HashPassword($new_user->password);

                // add user to db
                $new_user->save(false);

                // user company info
                $cmpny_info = new CompanyInfo();
                $cmpny_info->name = $jp_company;
                $cmpny_info->website = $jp_company_url;
                $cmpny_info->description = $jp_company_background;
                $cmpny_info->FK_userid = $new_user->id;
                // add company info to db
                $cmpny_info->save(false);

                // user basic info
                $basic_info = new BasicInfo();
                $basic_info->about_me = $jp_posted_by; // ask professor about this mapping
                $basic_info->userid = $new_user->id;
                $basic_info->hide_phone = 1;
                $basic_info->allowSMS = 0;
                $basic_info->validated = 1;
                // add basic info to db
                $basic_info->save(false);
            }

            // we have a user, post under his/her account
            $current_user = (isset($new_user) ? $new_user : $user_found);

            // check for duplicate postings
            $dup_entries = Job::model()->find(  "FK_poster=:poster AND ".
                "title=:title AND ".
                "deadline=:deadline AND ".
                "post_date=:post_date",
                array(  ':poster' => $current_user->id,
                    ':title' => $jp_position,
                    ':deadline' => date('Y-m-d H:i:s', strtotime($jp_expireTime)),
                    ':post_date' => $jp_postedTime));

            // duplicate entry, ignore
            if (count($dup_entries) > 0)
            {
                continue;
            }

            // no duplicates, add posting
            $new_job_posting = new Job();
            $new_job_posting->FK_poster = $current_user->id; // need an account
            $new_job_posting->post_date = $jp_postedTime;
            $new_job_posting->title = $jp_position;
            $new_job_posting->deadline = date('Y-m-d H:i:s', strtotime($jp_expireTime));
            $new_job_posting->description = $jp_description . $jp_duties . $jp_qualifications;
            $new_job_posting->type = 'CIS'; // know it was posted using this api
            $new_job_posting->compensation = ""; // not available from CIS
            $new_job_posting->posting_url = $jp_id;
            $new_job_posting->comp_name = $jp_company;

            // post the job to db
            $new_job_posting->save(false);
            $new_jobs_count++;

            // skill match descripnt against database
            $decoded_desc = utf8_decode($new_job_posting->description);
            $decoded_desc = str_replace(array('/', ',', '.'), ' ', $decoded_desc);
            $description_words = explode(' ', $decoded_desc); // split into words
            $skill_order = 0;
            foreach ($description_words as $word)
            {
                // check database to see if current word is a skill
                $skill = Skillset::model()->find("name=:name", array(":name"=>$word));
                if ($skill)
                {
                    // its a skill, map it to this posting on database
                    $skill_map = new JobSkillMap();
                    $skill_map->jobid = $new_job_posting->id;
                    $skill_map->skillid = $skill->id;
                    $skill_map->ordering = $skill_order;
                    $skill_order++;
                    $skill_map->save(false);
                }
            }

        }
        // all went good
        if ($new_jobs_count > 0)
            echo 'Success: ' . $new_jobs_count . ' new job(s) have been synchronized';
        else
            echo 'No new jobs found!';
    }
}