<?php	
	if (!defined('SKDBM')) { die('Define missing'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>SwiftKit DataBase Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }
    </style>
    <link href="/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="/css/bootstrap-modal.css" rel="stylesheet">
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="index.php">SwiftKit</a>
            <div class="nav-collapse collapse">
                <p class="navbar-text pull-right">
                    <a href="/login.php" class="navbar-link">Log out <?php echo ucfirst( $_COOKIE['user_name'] ); ?></a>
                </p>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span3">
            <div class="well sidebar-nav">
                <ul class="nav nav-list">
                    <li class="nav-header skill-header nav-parent-expand">Skills</li>
                    <?php
                    foreach ($menus['skills'] AS $snum => $skill)
                    {
                        echo '
                            <li class="skill nav-child hide ' . ( isset( $_GET['skill'] ) && $_GET['skill'] == $skill ? 'active' : '' ) . '"><a href="index.php?s=skills&p=list&skill=' . $skill . '">
                                <img src="images/skillicons/' . $skill . '.png" alt="skill icon" style="max-height: 20px;" /> 
                                ' . ucfirst($skill) . '
                            </a></li>
                        ';
                    }
                    ?>
                    <li class="nav-header">Other elements</li>
                    <li class="<?php if ( isset( $_GET['s'] ) && $_GET['s'] == 'quests' ) echo 'active'; ?>"><a href="index.php?s=quests&p=list">Quests</a></li>
                    <li class="<?php if ( isset( $_GET['s'] ) && $_GET['s'] == 'tasks' ) echo 'active'; ?>"><a href="index.php?s=tasks&p=list">Tasks</a></li>
                    <li class="<?php if ( isset( $_GET['s'] ) && $_GET['s'] == 'fairyrings' ) echo 'active'; ?>"><a href="index.php?s=fairyrings&p=list">Fairy rings</a></li>
                    <li class="nav-header">Atlas</li>
                    <?php
                    foreach ($menus['atlas'] AS $snum => $atlas)
                    {
                        echo '
                            <li class="' . ( isset( $_GET['atlas'] ) && $_GET['atlas'] == $atlas ? 'active' : '' ) . '"><a href="index.php?s=atlas&p=list&atlas=' . $atlas . '">
                                ' . ucfirst( $atlas ) . '
                            </a></li>
                        ';
                    }
                    ?>
                    <li class="nav-header">Utilities</li>
                    <li class="<?php if ( isset( $_GET['s'] ) && $_GET['s'] == 'admin' && $_GET['p'] == 'logs' ) echo 'active'; ?>"><a href="index.php?s=admin&p=logs">Logs</a></li>
                    <li class="<?php if ( isset( $_GET['s'] ) && $_GET['s'] == 'issues' && $_GET['p'] == 'list' ) echo 'active'; ?>"><a href="index.php?s=issues&p=list">Issue tracker</a></li>
                    <?php
                        if ( ua() >= 50 )
                        {
                    ?>
                    <li class="nav-header">Administrator</li>
                    <li class="<?php if ( isset( $_GET['s'] ) && $_GET['s'] == 'admin' && $_GET['p'] == 'export' ) echo 'active'; ?>"><a href="index.php?s=admin&p=export">Export</a></li>
                    <?php
                        }
                        if ( ua() >= 100 )
                        {
                    ?>
                    <li class="<?php if ( isset( $_GET['s'] ) && $_GET['s'] == 'admin' && $_GET['p'] == 'users' ) echo 'active'; ?>"><a href="index.php?s=admin&p=users">Users</a></li>
                    <?php
                        }
                    ?>
                </ul>
            </div><!--/.well -->
        </div><!--/span-->

        <div class="span9">
            <div class="row-fluid">
                <!-- Add breadcrumbs, always nice knowing where you are -->
                <ul class="breadcrumb">
                    <li>
                        <a href="index.php">Home</a>  <span class="divider">/</span>
                    </li>
                    <?php
                        if ( $section != 'home' )
                        {
                            echo '
                                <li>
                                    <a href="index.php">
                                        ' . ucfirst( $section ) . '
                                    </a>  <span class="divider">/</span>
                                </li>
                            ';
                        }
                        if ( isset($_GET['skill'] ) )
                        {
                            echo '
                                <li>
                                    <a href="#">
                                        ' . ucfirst( $_GET['skill'] ) . '
                                    </a>  <span class="divider">/</span>
                                </li>
                            ';
                        }
                        if ( $page != 'home' && !isset( $_GET['skill'] ) )
                        {
                            echo '
                                <li>
                                    <a href="#">
                                        ' . ucfirst( $page ) . '
                                    </a>
                                </li>
                            ';
                        }
                    ?>
                </ul>
            </div>

            <div class="row-fluid" id="notifications"></div>

            <div class="row-fluid">