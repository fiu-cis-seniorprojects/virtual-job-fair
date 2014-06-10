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
        $key = $_GET['key'];

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
        $postings = Job::model()->find('postdate >= :startdate AND postdate <= :enddate AND active=1',
                                        array('startdate' => $start_date,
                                                'enddate' => $end_date));

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