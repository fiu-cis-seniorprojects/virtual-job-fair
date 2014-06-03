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
        company: '{{company().toUpperCase()}}',
        title: '{{lorem(3, "words").toUpperCase()}}',
        type: function (tags) {
            var jtypes = ['Part Time', 'Full Time', 'Internship', 'Co-op', 'Research'];
            return jtypes[tags.integer(0, jtypes.length - 1)];
        },
        expiration_date: '{{date(new Date(2014, 0, 1), new Date(), "YYYY-MM-ddThh:mm:ss Z")}}',
        contact: '{{email()}}',
        website: 'http://website.com',
        compensation: '{{floating(15, 40, 2, "$0.00")}}',
        description: '{{lorem(5, "paragraphs")}}'
    }
]
    */

    public function actionCareerpath()
    {
        // using test URL retrieve mock json objects
        // here I would request a date range, since this script runs daily as a cron job
        //
        $request = Yii::app()->curl->run('http://www.json-generator.com/j/bVtmwbiRHC?indent=4');

        $job_postings = CJSON::decode($request->getData());

        // keep track of new jobs (used in crontab email message)
        $new_jobs_count = 0;

        // check each object to see if it has been posted already:
        // criteria for duplicate jobs:
        // - same title, description and expiration date
        foreach($job_postings as $job_posting)
        {
            // dissect job posting information
            $jp_company = $job_posting['company'];
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