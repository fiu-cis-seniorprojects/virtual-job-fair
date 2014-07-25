<?php

class APIController extends Controller
{

    public function filters()
    {
        return array();
    }

    protected function authenticate()
    {
        // check if an api key has been specified
        if (!isset($_SERVER['HTTP_API_KEY']))
        {
            $this->_sendResponse(500, 'Error: Parameter <b>API key</b> is missing');
            Yii::app()->end();
        }

        // grab api key in headers
        $api_key = $_SERVER['HTTP_API_KEY'];

        // verify api key against database
        $key_exists = ApiAuth::model()->find('valid_key=:api_key', array(':api_key' => $api_key));
        if (count($key_exists) <= 0)
        {
            // key does not exist
            $this->_sendResponse(401, 'Invalid API Key!');
            Yii::app()->end();
        }
    }

    public function actionPost()
    {
        // check if api is enabled
        $api_status = ApiStatus::getFirst();
        if (!$api_status->isApiOn())
        {
            $this->_sendResponse(200,'API access has been disabled. Contact VJF administrator.');
        }

        // perform routine auth
        $this->authenticate();

        // api key is valid, now parse the json object
        $request_obj = Yii::app()->request->getRawBody();
        $job_posting = CJSON::decode($request_obj);
        if (!isset($job_posting) || (is_null($job_posting)))
        {
            $this->_sendResponse(500, 'Empty job posting body.');
        }

        // dissect scis job posting information
        $jp_id = $job_posting['URL']; //$job_posting['ID'];
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
        //$jp_posting_format = $job_posting['Format']; dont care about this, ask joshua

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
            $hasher = new PasswordHash(8, false);
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
//        $dup_entries = Job::model()->find(  "FK_poster=:poster AND ".
//                                            "title=:title AND ".
//                                            "deadline=:deadline AND ".
//                                            "post_date=:post_date",
//                                            array(  ':poster' => $current_user->id,
//                                                    ':title' => $jp_position,
//                                                    ':deadline' => date('Y-m-d H:i:s', strtotime($jp_expireTime)),
//                                                    ':post_date' => $jp_postedTime));

        $dup_entries = Job::model()->find(  "posting_url=:job_url", array(':job_url' => $jp_id));

        // duplicate entry, ignore
        if (count($dup_entries) > 0)
        {
            $new_job_posting = $dup_entries;
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

            // send response and stop application
            $this->_sendResponse(400, 'Job entry has been updated in the database.');
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

        // all went good
        $this->_sendResponse(200);
    }

    public function actionUpdate()
    {
        echo "updated!";
    }

    public function actionDelete()
    {
        echo "deleted!";
    }

    public function actionList()
    {
        // validate API key
        if (isset($_GET['key']))
        {
            $key = $_GET['key'];
        }
        else
        {
            $this->_sendResponse(500, 'Error: Parameter <b>key</b> is missing');
            Yii::app()->end();
        }

        // this should be done against DB
        if ($key !== $this->API_KEY)
        {
            $this->_sendResponse(401, sprintf('Invalid API Key specified: <b>%s</b>', $key));
            Yii::app()->end();
        }

        // check if we have a range parameter
        if (!isset($_GET['range']))
        {
            $this->_sendResponse(500, 'Error: Parameter <b>range</b> is missing');
            Yii::app()->end();
        }

        //  grab range and convert to date
        $day_range = $_GET['range'];

        $start_date = new DateTime('now');
        $date_interval = new DateInterval('P'.$day_range.'D');
        $start_date->sub($date_interval);

        $end_date = new DateTime('now');

        // retrieve postings from DB that fall within specified date range
        $postings = Job::model()->find('post_date >= :startdate AND post_date <= :enddate AND active=1',
                                        array('startdate' => $start_date->format('Y-m-d H:i:s'),
                                                'enddate' => $end_date->format('Y-m-d H:i:s')));

        // check if we got results
        if (empty($postings))
        {
            // no results
            $this->_sendResponse(200, sprintf('No active job postings found for the last <b>%s</b> day(s)', $day_range));
        }
        else
        {
            // got results, JSON encode and send to client
            $this->_sendResponse(200, CJSON::encode($postings));
        }
    }

    // http://www.gen-x-design.com/archives/create-a-rest-api-with-php.
    private function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
    {
        // set the status
        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        header($status_header);
        // and the content type
        header('Content-type: ' . $content_type);

        // pages with body are easy
        if($body != '')
        {
            // send the body
            echo $body;
        }
        // we need to create the body if none is passed
        else
        {
            // create some body messages
            $message = '';

            // this is purely optional, but makes the pages a little nicer to read
            // for your users.  Since you won't likely send a lot of different status codes,
            // this also shouldn't be too ponderous to maintain
            switch($status)
            {
                case 401:
                    $message = 'You must be authorized to view this page.';
                    break;
                case 404:
                    $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
            }

            // servers don't always have a signature turned on
            // (this is an apache directive "ServerSignature On")
            $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

            // this should be templated in a real-world solution
            $body = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
</head>
<body>
    <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
    <p>' . $message . '</p>
    <hr />
    <address>' . $signature . '</address>
</body>
</html>';

            echo $body;
        }
        Yii::app()->end();
    }

    private function _getStatusCodeMessage($status)
    {
        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        $codes = Array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }
}

/*
*
{
"ID":"http:\/\/cis.fiu.edu\/careerpath\/posting.php?id=205",
"PostedTime":"2014-03-10 13:33:42",
"ExpireTime":"07\/01\/2014",
"Company":"Fortytwo Sports",
"Position":"Lead Developer",
"URL":"http:\/\/www.fort42wo.com",
"PostingType":"Job",
"Background":"\u003Cp\u003EFortytwo Sports is a startup company that offers an online social networking service. Visit our website \u003Ca href=\"http:\/\/www.fort42wo.com\"\u003Ewww.fort42wo.com\u003C\/a\u003E for more information about the Lead Developer position and to play a quick brain teaser!\u003C\/p\u003E\r\n\r\n\u003Cp\u003E \u003C\/p\u003E\r\n",
"Description":"\u003Cp\u003EFortytwo Sports is looking for an exceptional lead developer who will drive the overall developmental process for new products. Our team will strive to use innovative technologies that change how millions of users connect, explore, and interact with information and one another. As the Lead Developer, you will be responsible for implementing front-end and back-end technologies for building a web\/mobile application. You will work with a small team and can switch projects as our fast-paced business grows and evolves. The ideal candidate will be a self-motivated, out-of-the-box thinker, with a ‘can-do, will do’ attitude with excellent communication skills and an ability to quickly ramp-up skills in new technologies. \u003C\/p\u003E\r\n\r\n\u003Cp\u003EAs a key member of a small and versatile team, you will design, test, deploy and maintain software solutions. Our ambitions reach far beyond a small startup company. You have the opportunity to become a principal member in a company looking to accomplish extraordinary measures.\u003C\/p\u003E\r\n",
"Duties":"\u003Cp\u003E• Lead the developmental process for building a web\/mobile application. \u003C\/p\u003E\r\n\r\n\u003Cp\u003E• Develop aesthetically pleasing and responsive front-end interfaces. \u003C\/p\u003E\r\n\r\n\u003Cp\u003E• Develop an optimized back-end codebase. \u003C\/p\u003E\r\n\r\n\u003Cp\u003E• Design and improve an ever-expanding database. \u003C\/p\u003E\r\n\r\n\u003Cp\u003E• Assist in building a developer team by recruiting talent.\u003C\/p\u003E\r\n",
"Qualifications":"\u003Cp\u003ECandidate should have at least 80% of the preferred qualifications listed below:\u003C\/p\u003E\r\n\r\n\u003Cul\u003E\r\n\t\u003Cli\u003EPursuing or accomplished a BS in Computer Science or related field. \u003C\/li\u003E\r\n\t\u003Cli\u003EFluent in front-end technologies such as HTML, CSS, and Javascript (w\/jQuery) with an interest in user interface design. \u003C\/li\u003E\r\n\t\u003Cli\u003EKnowledgeable in back-end\/server technologies such as C\/C++, Java and\/or Apache\/Apache Tomcat. \u003C\/li\u003E\r\n\t\u003Cli\u003EBasic knowledge in PostgreSQL, GIT, and Agile is a plus. \u003C\/li\u003E\r\n\t\u003Cli\u003EStrong written and oral communication skills. \u003C\/li\u003E\r\n\u003C\/ul\u003E\r\n",
"Email":"jobs@fort42wo.com",
"PostedBy":"Roberto Guzman, From: Fortytwo Sports (Start Up)",
"Format":"2"
}

*/