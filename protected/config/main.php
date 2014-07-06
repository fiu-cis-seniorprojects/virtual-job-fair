<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Virtual Job Fair',
	'theme'=>'bootstrap',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
        'ext.YiiMailer.YiiMailer',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'gii',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		'mailbox'=>array(
				'userClass'=>'User',
				'userIdColumn'=>'id',
				'usernameColumn'=>'username',
				),		
		/*'message' => array(
					'userModel' => 'User',
					'getNameMethod' => 'getFullName',
					'getSuggestMethod' => 'getSuggest',
					'viewPath' => '/message/fancy',
					'receiverRelation' => array(
						CActiveRecord::BELONGS_TO,
						'User',						
						'on' => 'User.id = receiver_id'),
					'senderRelation' => array(
								CActiveRecord::BELONGS_TO,
								'User',								
								'on' => 'User.id = sender_id'),
				),	*/			
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(

                // REST API PATTERNS
                array('API/post', 'pattern'=>'api/postings', 'verb'=>'POST'),
                array('API/update', 'pattern'=>'api/postings/<modelid:\d+>', 'verb'=>'PUT'),
                //

                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',

			),
		),
		'email'=>array(
				'class'=>'application.extensions.email.Email',
				'delivery'=>'php', //Will use the php mailing function.
		),
		
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			//'connectionString' => 'mysql:host=jobfairdb.db.9862366.hostedresource.com;dbname=jobfairdb',
			'connectionString' => 'mysql:host=localhost;dbname=jobfairdb',
			'emulatePrepare' => true,
			'username' => 'vjfuser',
			//'username' => 'jobfairdb',
			'password' => 's3n10rpr0j3ct',
			//'password' => 'E!qazxsw2',
			'charset' => 'utf8',
		),

        'jobmatch'=>array(
            'class'=>'JobMatch'
        ),

		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),		
		),
		'bootstrap'=>array(
				'class'=>'bootstrap.components.Bootstrap'
		),
		'multicomplete'=>array(
				'class'=>'multicomplete.MultiComplete.php'),

        'curl' => array(
            'class' => 'application.extensions.curl.Curl',
            'options'=>array(
                'setOptions'=>array(
                    CURLOPT_HEADER => false,
                ),
            ),
        ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);
