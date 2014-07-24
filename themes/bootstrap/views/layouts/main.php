<?php /* @var $this Controller */ ?>
<?php date_default_timezone_set('America/New_York'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/jquery.jgrowl.css" />
    <link href="http://vjs.zencdn.net/c/video-js.css" rel="stylesheet">
	<script src="http://vjs.zencdn.net/c/video.js"></script>
    
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<?php Yii::app()->bootstrap->register();  ?>

    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
            width: 260px;
        }
    </style>
	
	<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.jgrowl.js"></script>
	<link rel="shortcut icon" href="/JobFair/images/ico/icon.ico">
</head>

<body>

<?php
if (!isset($_GET['keyword'])) {
    $_GET['keyword'] = '';
}

	if (User::isStudent(Yii::app()->user->name))
		$profile = '/profile/view';
	else 
		$profile = '/profile/viewEmployer';
	?>
	
	<?php /*
	if (!User::isStudent(Yii::app()->user->name) & !Yii::app()->user->isGuest)
		$home = '/home/employerhome';
	else if (User::isStudent(Yii::app()->user->name))
		$home = '/home/studenthome';
	else 
		$home = '/site/index';
	
	*/?>
	<?php
	$search = "";
	if (User::isCurrentUserAdmin(Yii::app()->user->name)) {
		$home = '/home/adminhome';
	} else if (User::isCurrentUserEmployer(Yii::app()->user->name)) {
		$home = '/home/employerhome';
		$search = '<form class="navbar-search pull-left" method="post" action="/JobFair/index.php/home/employersearch" >'
					. $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
		    		'name'=>'skillkeyword',
					'source'=>Skillset::getNames(),
		    		'htmlOptions'=>array('class'=>'search-query span2','placeholder'=>'Search Students by Skill'
		    	),
			), true
		).
		    		'<button type="submit" style="background-color:transparent ; border:0"  >
		<img src="/JobFair/images/ico/Search-icon.png"  height="25" width="25" style="margin:1px 0 0 5px"></button>
		</form>';
	} else if (User::isCurrentUserStudent(Yii::app()->user->name)){
		$home = '/home/studenthome';
		$search = '<form class="navbar-search pull-left" method="get" action="/JobFair/index.php/job/search">'
                    . $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
                    'name'=>'keyword',
                    'id'=>'keyword',
                    'value'=> $_GET['keyword'],
                    'htmlOptions'=>array('class'=>'search-query span2',
                        'style'=>'width: 250px',
                        'placeholder'=>'Search by Position, Skills, Company, Type'
                    ),
                ), true
            ).
            '<button type="submit" style="background-color:transparent ; border:0" >
		<img src="/JobFair/images/ico/Search-icon.png"  height="25" width="25" style="margin:1px 0 0 5px"></button>
		</form>';
	} else {
		$home = '/site/index';
	}

	?>

<?php $this->widget('bootstrap.widgets.TbNavbar',array(
    //'type'=>'inverse', // null or 'inverse'
    'htmlOptions' => array('role' => 'navigation'),
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(
                array('label'=>'Home', 'url'=>array($home),'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'Jobs', 'url'=>array("/job/home"), 'visible'=>User::isCUrrentUserStudent()),
    			array('label'=>'Message', 'url'=>array('/message'), 'visible'=>!Yii::app()->user->isGuest ),
                //array('label'=>'Post Job', 'url'=>array('/job/post'), 'visible'=>User::isCurrentUserEmployer()),
                array('label'=>'Post Job', 'url'=>"#", 'visible'=>User::isCurrentUserEmployer()),
                array('label'=>'SMS', 'url'=>array('/SMS/Sendsms'), 'visible'=>!Yii::app()->user->isGuest & !User::isCurrentUserAdmin(Yii::app()->user->name)),
            ),
        ),$search
,

   array(
            'class'=>'bootstrap.widgets.TbMenu',
            'htmlOptions'=>array('class'=>'pull-left'),
            'items'=>   array('-',
                        array('label'=>'('.Yii::app()->user->name.')', 'url'=>'#', 'items'=>array(
                        array('label'=>'My Profile', 'url'=>array($profile), 'visible'=>!Yii::app()->user->isGuest & !User::isCurrentUserAdmin(Yii::app()->user->name)),
                        array('label'=>'Merge Accounts','visible'=>(User::isCurrentUserStudent(Yii::app()->user->name)), 'url'=>'/JobFair/index.php/user/MergeAccounts'),
                        array('label'=>'Change Password','visible'=>!Yii::app()->user->isGuest, 'url'=>'/JobFair/index.php/user/ChangePassword'),
                        '----',
                        array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
                        array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                        array('label'=>'Register', 'url'=>array('/user/register'), 'visible'=>Yii::app()->user->isGuest),
                )),
            ),
        ),
    ),
)); ?>

<div class="container-fluid" id="page">

<?php
    if (User::isCurrentUserAdmin(Yii::app()->user->name))
    {
        echo "<div class=\"row-fluid\"><div class=\"span3\">";
        echo "<div class=\"well sidebar-nav affix\">";
        $actionid = $this->getUniqueId() . '/' . $this->getAction()->getId();
        $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'list',
            'items' => array(
                array('label' => 'ADMINISTRATION'),
//                array('label' => 'Home', 'icon' => 'home', 'url' => $this->createUrl('/'), 'active' => in_array($actionid, array('home/adminhome', 'site/error'))),
                array('label' => 'Users', 'icon' => 'user', 'url' => $this->createUrl('UserCrud/admin'), 'active' => in_array($actionid, array('userCrud/admin', 'userCrud/update', 'userCrud/index'))),
                array('label' => 'Skills', 'icon' => 'pencil', 'url' => $this->createUrl('Skillset/admin'), 'active' => in_array($actionid, array('skillset/admin', 'skillset/consolidate', 'skillset/create', 'skillset/update', 'skillset/index'))),
                array('label' => 'Postings', 'icon' => 'list', 'url' => $this->createUrl('PostingsAdmin/admin'), 'active' => in_array($actionid, array('postingsAdmin/admin', 'postingsAdmin/index'))),
                array('label' => 'CAREEPATH API'),
                array('label' => 'Authentication', 'icon' => 'lock', 'url' => $this->createUrl('ApiAuth/index'), 'active' => in_array($actionid, array('apiAuth/index', 'apiAuth/home', 'apiAuth/create', 'apiAuth/update'))),
                array('label' => 'Import Jobs', 'icon' => 'briefcase', 'url' => $this->createUrl('ApiConfig/home'), 'active' => in_array($actionid, array('apiConfig/home', 'apiConfig/index'))),
                array('label' => 'NOTIFICATIONS'),
                array('label' => 'Settings', 'icon' => 'cog', 'url' => $this->createUrl('home/notificationAdmin'), 'active' => in_array($actionid, array('home/notificationAdmin'))),
            ),
        ));
        echo "</div></div>";

        echo "<div class=\"span9\">";
//        echo $this->getUniqueId() . '<br>';
//        echo $this->getAction()->getId();
        echo $content;
        echo "</div>";  

        echo "</div>";
    }
    else
    {
        echo $content;
    }

?>

</div>

</body>
        <div style="height: 50px"></div>
        <div style="position:fixed; text-align:center; width:100%; height:20px; background-color:white;border-top: 1px solid rgb(206, 206, 206); padding:5px; bottom:0px; ">

           <a target="blank" href="http://fiu.edu">Florida Interational University</a> | Virtual Job Fair - Senior Project 2014
        </div>

</html>

<?php  if (isset($_GET["notificationRead"]))
		{
			//print "<pre>"; print_r($_GET["notificationRead"]);print "</pre>";return;
			Notification::markHasBeenRead($_GET["notificationRead"]);
		}


?>

<?php // if (isset($_GET["activation"]))
//		{
//			//print "<pre>"; print_r($_GET["notificationRead"]);print "</pre>";return;
//			User::activeEmployer($_GET["activation"]);
//			User::sendEmployerVerificationEmail($_GET["activation"]);
//		}


?>
