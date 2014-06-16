<?php

class APIController extends Controller
{
    protected $API_KEY = 'A123456K';

    public function filters()
    {
        return array();
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
 * [
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
]
 */