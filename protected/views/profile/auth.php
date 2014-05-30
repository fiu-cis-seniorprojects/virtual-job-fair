<?php
	if (!isset($_SESSION))
    	session_start();

    //edit by Manuel making the link dynamic, using Yii
    $config['base_url']             =   'http://'.Yii::app()->request->getServerName().'/JobFair/index.php/profile/auth.php';
    $config['callback_url']         =   'http://'.Yii::app()->request->getServerName().'/JobFair/index.php/profile/demo';
    $config['linkedin_access']      =   '2rtmn93gu2m4';
    $config['linkedin_secret']      =   'JV0fYG9ls3rclP8v';

    include_once "linkedin.php";

    # First step is to initialize with your consumer key and secret. We'll use an out-of-band oauth_callback
    $linkedin = new LinkedIn($config['linkedin_access'], $config['linkedin_secret'], $config['callback_url'] );
    //$linkedin->debug = true;

    # Now we retrieve a request token. It will be set as $linkedin->request_token
    $linkedin->getRequestToken();
    $_SESSION['requestToken'] = serialize($linkedin->request_token);
  
    # With a request token in hand, we can generate an authorization URL, which we'll direct the user to
    //echo "Authorization URL: " . $linkedin->generateAuthorizeUrl() . "\n\n";
    header("Location: " . $linkedin->generateAuthorizeUrl());
?>
