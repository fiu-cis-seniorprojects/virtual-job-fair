<?php

class ImportJobsCommand extends CConsoleCommand
{
    public function getHelp()
    {
        $description =  "\nDESCRIPTION\n---\n".
                        "This command will retrieve the latest job postings from the specified source ".
                        "website and update the Virtual Job Fair job postings database.\n\n";

        return parent::getHelp() . $description;
    }



    public function actionIndeed()
    {
        echo "Not implemented.\n";
    }

    /* JSON GENERATOR TEMPLATE:

[
    '{{repeat(5, 1)}}',
    {
      ID: 'http://www.postingurl.com',
      PostedTime: '{{date(new Date(2014, 0, 1), new Date(), "YYYY-MM-dd hh:mm:ss Z")}}',
      ExpireTime: '{{date(new Date(2014, 0, 1), new Date(), "MM/dd/YYYY")}}',
      Company: '{{company().toUpperCase()}}',
      Position: '{{lorem(3, "words").toUpperCase()}}',
      URL: 'http://www.companyurl.com',
      PostingType: "Job",
      Background: '{{lorem(5, "paragraphs")}}',
      Description: '{{lorem(3, "paragraphs")}}',
      Duties: '{{lorem(3, "paragraphs")}}',
      Qualifications: '{{lorem(3, "paragraphs")}}',
      Email: '{{email()}}',
      PostedBy: '{{lorem(3, "paragraphs")}}',
      Format: '2'
    }
]
    */

    public function actionCareerpath()
    {
        // using test URL retrieve mock json objects
        // here I would request a date range, since this script runs daily as a cron job
        //
        $request = Yii::app()->curl->run('http://www.json-generator.com/api/json/get/cmztbPLWCq?indent=2');

        $job_postings = CJSON::decode($request->getData());

        // keep track of new jobs
        $new_jobs_count = 0;

        // check each object to see if it has been posted already:
        // criteria for duplicate jobs:
        // - same title, description and expiration date
        foreach($job_postings as $job_posting)
        {
            // dissect job posting information
            $jp_id = $job_posting['ID'];
            $jp_postedTime = $job_posting['PostedTime'];
            $jp_expireTime = $job_posting['ExpireTime'];
            $jp_company = $job_posting['Company'];
            $jp_position = $job_posting['Position'];
            $jp_company_url = $job_posting['URL'];
            $jp_company_background = $job_posting['Background'];
            $jp_company_description = $job_posting['Description'];
            $jp_duties = $job_posting['Duties'];
            $jp_qualifications = $job_posting['Qualifications'];
            $jp_company_email = $job_posting['Email'];
            $jp_posted_by = $job_posting['PostedBy'];
            $jp_posting_format = $job_posting['Format'];


            $jp_type = $job_posting['PostingType'];
            $jp_website = $job_posting['website'];
            $jp_description = $job_posting['description'];
            $jp_title = $job_posting['title'];
            $jp_expdate = strtotime($job_posting['expiration_date']);
            $jp_compensation = $job_posting['compensation'];
            $jp_contact = $job_posting['contact'];
            $jp_jobtype = $job_posting['type'];

            // attempt to find duplicate in database:
            // since we are using a single user to post for SCIS CareerPath
            // then we can easily search through the job postings by this user
            $posting_user = User::model()->find("username=:username", array(':username' => 'fiuscis'));
            $dup_entries = Job::model()->find(  "FK_poster=:poster AND ".
                                                "title=:title AND ".
                                                "deadline=:deadline AND ".
                                                "description=:description",
                                                array(  ':poster' => $posting_user->id,
                                                        ':title' => $jp_title,
                                                        ':deadline' => date('Y-m-d H:i:s', $jp_expdate),
                                                        ':description' => $jp_description));


            if (count($dup_entries) > 0)
            {
                // duplicate found, skip it
                continue;
            }

            // no duplicates found, add job posting to database!
            $new_job_posting = new Job();
            $new_job_posting->FK_poster = $posting_user->id;
            $new_job_posting->post_date = date('m/d/Y');
            $new_job_posting->title = $jp_title;
            $new_job_posting->deadline = date('Y-m-d H:i:s', $jp_expdate);
            $new_job_posting->description = $jp_description;
            $new_job_posting->type = $jp_jobtype;
            $new_job_posting->compensation = $jp_compensation;
            $new_job_posting->save(false);
            $new_jobs_count++;
        }

        // crontab email message
        if ($new_jobs_count > 0)
            echo date('m/d/Y H:i:s'). ' -> '. $new_jobs_count . ' new job(s) have been imported from FIU SCIS CareerPath';
    }

}


/*
 [{"ID":"http:\/\/cis.fiu.edu\/careerpath\/posting.php?id=205","PostedTime":"2014-03-10 13:33:42","ExpireTime":"07\/01\/2014","Company":"Fortytwo Sports","Position":"Lead Developer","URL":"http:\/\/www.fort42wo.com","PostingType":"Job","Background":"\u003Cp\u003EFortytwo Sports is a startup company that offers an online social networking service. Visit our website \u003Ca href=\"http:\/\/www.fort42wo.com\"\u003Ewww.fort42wo.com\u003C\/a\u003E for more information about the Lead Developer position and to play a quick brain teaser!\u003C\/p\u003E\r\n\r\n\u003Cp\u003E \u003C\/p\u003E\r\n","Description":"\u003Cp\u003EFortytwo Sports is looking for an exceptional lead developer who will drive the overall developmental process for new products. Our team will strive to use innovative technologies that change how millions of users connect, explore, and interact with information and one another. As the Lead Developer, you will be responsible for implementing front-end and back-end technologies for building a web\/mobile application. You will work with a small team and can switch projects as our fast-paced business grows and evolves. The ideal candidate will be a self-motivated, out-of-the-box thinker, with a ‘can-do, will do’ attitude with excellent communication skills and an ability to quickly ramp-up skills in new technologies. \u003C\/p\u003E\r\n\r\n\u003Cp\u003EAs a key member of a small and versatile team, you will design, test, deploy and maintain software solutions. Our ambitions reach far beyond a small startup company. You have the opportunity to become a principal member in a company looking to accomplish extraordinary measures.\u003C\/p\u003E\r\n","Duties":"\u003Cp\u003E• Lead the developmental process for building a web\/mobile application. \u003C\/p\u003E\r\n\r\n\u003Cp\u003E• Develop aesthetically pleasing and responsive front-end interfaces. \u003C\/p\u003E\r\n\r\n\u003Cp\u003E• Develop an optimized back-end codebase. \u003C\/p\u003E\r\n\r\n\u003Cp\u003E• Design and improve an ever-expanding database. \u003C\/p\u003E\r\n\r\n\u003Cp\u003E• Assist in building a developer team by recruiting talent.\u003C\/p\u003E\r\n","Qualifications":"\u003Cp\u003ECandidate should have at least 80% of the preferred qualifications listed below:\u003C\/p\u003E\r\n\r\n\u003Cul\u003E\r\n\t\u003Cli\u003EPursuing or accomplished a BS in Computer Science or related field. \u003C\/li\u003E\r\n\t\u003Cli\u003EFluent in front-end technologies such as HTML, CSS, and Javascript (w\/jQuery) with an interest in user interface design. \u003C\/li\u003E\r\n\t\u003Cli\u003EKnowledgeable in back-end\/server technologies such as C\/C++, Java and\/or Apache\/Apache Tomcat. \u003C\/li\u003E\r\n\t\u003Cli\u003EBasic knowledge in PostgreSQL, GIT, and Agile is a plus. \u003C\/li\u003E\r\n\t\u003Cli\u003EStrong written and oral communication skills. \u003C\/li\u003E\r\n\u003C\/ul\u003E\r\n","Email":"jobs@fort42wo.com","PostedBy":"Roberto Guzman, From: Fortytwo Sports (Start Up)","Format":"2”}]
 */