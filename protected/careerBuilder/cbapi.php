<?php
/**
 * Created by PhpStorm.
 * User: analhernandez
 */

/**
 * Class CBAPI
 * @package careerBuilder
 * source: https://github.com/cbdr/CareerBuilder-API-PHP-Library
 * CareerBuilder API Information
 * DevKey: "WDHP5VP681NXND0YFBCW"
 * email: virtualjobfairfiu@gmail.com
 * password: Cis49112014
 * http://developer.careerbuilder.com/
 */


namespace careerBuilder;

/************************************************************
 * CLASS:         CBAPI
 * DESCRIPTION:   Master class responsible for making all API calls to CareerBuilder.com this is the
 *                primary interface for performing CareerBuilder related activities.
 *
 * FUNCTIONS:     getJobCount, getJobDetails, getJobResults, getRecommendationsForJob, etc.
 *
 */
class CBAPI {
    private static $APIKey = "WDHP5VP681NXND0YFBCW";  /* your private CareerBuilder API Developer Key */
    public static $perPage = 25; /* the number of job results to pull back for a results search */
    public static $siteID = ''; /* optional string that can be used to track engagement */


    public function __construct(){
        $this->publisher = $this->$APIKey;
    }

    /*
     *  FUNCTION:     getJobCount
     *  DESCRIPTION:  Function that runs job search and returns the number of jobs found that match
     *  INPUT:        (keywords:string) string for the keywords of the search
     *  INPUT:        (location:string) string for the location of the search
     *  RETURNS:      an integer of the number of jobs found
     *  EXAMPLE CODE:
     *  			require_once('../classes/cb.php'); // load library
     *				$num_jobs = CBAPI::getJobCount("sales","atlanta"); // make the function call
     *
     */
    public static function getJobCount($keywords, $location) {
        $siteIDValue = CBAPI::$siteID;
        $location = urlencode($location);
        $keywords = urlencode($keywords);
        $key = CBAPI::$APIKey;
        $url = "http://api.careerbuilder.com/V1/jobsearch?DeveloperKey=$key&ExcludeNational=True&Keywords=$keywords&siteid=$siteIDValue&Location=$location&PerPage=1";
        try {
            $xml = simplexml_load_file($url);
        }catch(Exception $e){
            print_r($e);
        }

        $count = $xml->TotalCount;
        return $count;
    }


    /*
     *  FUNCTION:     getJobCountSinceDate
     *  DESCRIPTION:  Function to get the number of jobs since a date
     *  INPUT:        (keywords:string) string for the keywords of the search
     *  INPUT:        (location:string) string for the location of the search
     *  INPUT:        (daysBackToLook:number) number of days back to look, values from 1-30
     *  INPUT:        (country:string:optional) country code in which to run the search
     *  RETURNS:      an integer of the number of jobs found
     *  EXAMPLE CODE:
     *  			require_once('../classes/cb.php'); // load library
     *				$num_jobs = CBAPI::getJobCountSinceDate("sales","atlanta", 7); // make the function call
     *
     */
    public static function getJobCountSinceDate($keywords, $location, $daysBackToLook, $country = null) {
        $siteIDValue = CBAPI::$siteID;
        $location = urlencode($location);
        $keywords = urlencode($keywords);
        $key = CBAPI::$APIKey;
        $url = "http://api.careerbuilder.com/V1/jobsearch?DeveloperKey=$key&ExcludeNational=True&Keywords=$keywords&PostedWithin=$daysBackToLook&siteid=$siteIDValue&Location=$location";
        if($country != "" && $country != null) {
            $url = $url . "&CountryCode=$country";
        }

        try {
            $xml = simplexml_load_file($url);
        }catch(Exception $e){
            print_r($e);
        }

        $count = $xml->TotalCount;
        return $count;
    }



    /*
     *  FUNCTION:     getJobResults
     *  DESCRIPTION:  Function to run a job search on careerbuilder and return back an array of the
     *                results that matched the search.
     *  INPUT:        (keywords:string) string for the keywords of the search
     *  INPUT:        (location:string) string for the location of the search
     *  INPUT:        (country:string) country code in which to search
     *  INPUT:        (pagenumber:number) page of results to retrieve, start at page 0
     *  INPUT:        (daysBackToLook:number:optional) values from 1-30, used to add a time constraint
     *                to the search
     *  RETURNS:      an array of job objects that matches the search
     *  EXAMPLE CODE:
     *  			require_once('../classes/cb.php'); // load library
     *				$results = CBAPI::getJobResults("sales","Atlanta", "", 0); // request a job object
     *
     */
    public static function getJobResults($keywords, $location, $country, $pagenumber, $daysBackToLook = null) {
        $siteIDValue = CBAPI::$siteID;
        $location = urlencode($location);
        $keywords = urlencode($keywords);
        $key = CBAPI::$APIKey;
        $perPage = CBAPI::$perPage;
        $url = "http://api.careerbuilder.com/v1/jobsearch?DeveloperKey=$key&ExcludeNational=True&Location=".$location."&siteid=$siteIDValue&Keywords=$keywords&PerPage=$perPage&PageNumber=$pagenumber";
        if($daysBackToLook != null)
        {
            $url .= "&PostedWithin=$daysBackToLook";
        }
        if($country != "") {
            $url = $url . "&CountryCode=$country";
        }
        $xml = simplexml_load_file($url);
        $jobsCollection = Array();
        $currItem = 1;
        $jobCount = $xml->LastItemIndex;
        $jobsCollection[0] = $jobCount;

        foreach($xml->Results->JobSearchResult as $result) {
            $currJob = new Job();
            $currJob->did = (string)$result->DID;
            $currJob->title = (string)$result->JobTitle;
            $currJob->companyName = (string)$result->Company;
            $currJob->city = (string)$result->Location;
            foreach($result->Skills->Skill as $sk)
            {
                $currJob->skills .= ucwords((string)$sk)." ";
            }
            $currJob->pay = (string)$result->Pay;
            $currJob->type = (string)$result->EmploymentType;
            $currJob->posted = (string)$result->PostedDate;
            $currJob->jobDetailsURL = (string)$result->JobDetailsURL;
            $jobsCollection[$currItem] = $currJob;
            $currItem ++;
        }

        return $jobsCollection;
    }

}

/************************************************************/
/************************************************************
 * CLASS:         Job
 * DESCRIPTION:   Class representing a job posting on careerbuilder.com.
 *
 * ATTRIBUTES:    did, title, company, city, state, etc.
 *
 * FUNCTIONS:     getJobCount, getJobDetails, getJobResults, getRecommendationsForJob, etc.
 *
 * SUGGESTED IMPROVMENTS: expand attributes to reflect all job properties, create a constructor
 *                        that will initialize from passed in XML or SimpleXML object
 */
class Job {
    public $did = "";
    public $title = "";
    public $companyName = "";
    public $city = "";
    public $state = "";
    public $skills = "";
    public $description = "";
    public $type = "";          // EmploymentType
    public $posted = "";
    public $relevancy = -1;
    public $jobDetailsURL = ""; // JobDetailsURL
    public $pay = "";           // Pay

    /*
     *  FUNCTION:     getJobTitle
     *  DESCRIPTION:  More advanced way to return the job title
     *  INPUT:        (maxLength:number:optional) the maximum length before trimming the title
     *  RETURNS:      the job title as a string (truncated if specified)
     */
    public function getJobTitle($maxLength = null) {
        if($this->title != null && $maxLength != null && strlen($this->title) > $maxLength) {
            return substr($this->title, 0, $maxLength-3)."...";
        } else {
            return $this->title;
        }
    }

    /*
     *  FUNCTION:     getCompanyName
     *  DESCRIPTION:  More advanced way to return the job company Name
     *  INPUT:        (maxLength:number:optional) the maximum length before trimming the title
     *  RETURNS:      the job title as a string (truncated if specified)
     */
    public function getCompanyName($maxLength = null) {
        if($this->companyName != null && $maxLength != null && strlen($this->companyName) > $maxLength) {
            return substr($this->companyName, 0, $maxLength-3)."...";
        } else {
            return $this->companyName;
        }
    }

    /*
     *  FUNCTION:     getLocation
     *  DESCRIPTION:  More advanced way to return the jobs location
     *  INPUT:        (maxLength:number:optional) the maximum length before trimming the title
     *  RETURNS:      the city/state as one location string (truncated if specified)
     */
    public function getLocation($maxLength = null) {
        $location = "" . $this->city;
        if($this->state != "" && $this->state != null){
            if($location != ""){ $location .= ", "; }
            $location .= $this->state;
        }
        if($location != null && $maxLength != null && strlen($location) > $maxLength) {
            return substr($location, 0, $maxLength-3)."...";
        } else {
            return $location;
        }
    }

    /*
     *  FUNCTION:     getJobTitle
     *  DESCRIPTION:  More advanced way to return the job title
     *  INPUT:        (maxLength:number:optional) the maximum length before trimming the title
     *  RETURNS:      the job title as a string (truncated if specified)
     */
    public function getJobDescription() {
        return $this->description;
    }

}

?>