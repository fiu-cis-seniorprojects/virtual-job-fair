<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Virtual Job Fair',
	'import'=>array(
        'application.models.*',
        'application.components.*',
        'ext.YiiMailer.YiiMailer',
        'ext.curl.Curl',
    ),

	// application components
	'components'=>array(
		/*'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
		// uncomment the following to use a MySQL database

        'jobmatch'=>array(
            'class'=>'JobMatch'
        ),

        'request' => array(
            'hostInfo' => 'http://vjf-dev.cs.fiu.edu',
            'baseUrl' => '/JobFair/',
            'scriptUrl' => 'index.php',
        ),

		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=jobfairdb',
			'emulatePrepare' => true,
			'username' => 'vjfuser',
			'password' => 's3n10rpr0j3ct',
			'charset' => 'utf8',
		),

        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),
            ),
        ),

        'curl' => array(
            'class' => 'application.extensions.curl.Curl',
            'options'=>array(
                'setOptions'=>array(
                    CURLOPT_HEADER => false,
                ),
            ),
        ),
	),
);