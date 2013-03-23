
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Yet Another MongoDB Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/bootstrap/css/bootstrap.css" rel="stylesheet">
    <style>
        body {
            padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
        }
    </style>
    <link href="/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

    <script src="/js/jquery.js"></script>
    <script src="/js/yama.js"></script>
    <script src="./bootstrap/js/bootstrap.js"></script>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="/bootstrap/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <!--
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="../assets/ico/favicon.png">
    -->
</head>

<body>
<!-- TOP MENU -->
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li>
                        <div class="btn-group">
                            <a class="btn dropdown-toggle" id="selected_db" data-toggle="dropdown" href="#">
                                <?php if(!empty($_GET['dbname'])):
                                    echo $_GET['dbname'];
                                else:
                                ?>
                                Select db
                                <?php endif;?>
                                <span class="caret"></span>
                            </a>
                            <ul id="database_list" class="dropdown-menu">
                                <?php foreach($DB_LIST as $c_db):?>
                                    <li><a href="/?dbname=<?php echo $c_db['name']?>"><?php echo $c_db['name']?>&nbsp;[<?php echo $c_db['empty']==1?"empty":$c_db['sizeOnDisk']?>]</a></li>
                                <?php endforeach;?>
                            </ul>
                        </div>
                    </li>
                    <li><a href="javascript:void(0)" onclick="showCreateDatabaseWindow()">Create database</a></li>
                    <li><a href="javascript:void(0)" onclick="dropDatabase('<?php echo !empty($_GET['dbname'])?$_GET['dbname']:""?>')">Drop database</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>
<!-- MAIN CONTAINER -->
<div class="container<?php echo empty($_GET['dbname'])?" hide":"";?>">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="container">

                <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <!-- Be sure to leave the brand out there if you want it shown -->
                <!--<a class="brand" href="#">Project name</a>-->
                <ul class="nav">
                    <li class="dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                            <?php if(!empty($_GET['collection'])):
                                echo $_GET['collection'];
                            else:
                            ?>
                                Select collection
                            <?php endif;?>
                            <b class="caret"></b>
                        </a>
                        <ul id="collection_list" class="dropdown-menu">
                            <?php foreach($COLLECTION_LIST as $collection):?>
                                <li><a href="/?<?php echo "dbname=".$C_DB_NAME."&collection=".$collection['name']?>"><?php echo $collection['name']?> &nbsp;[<?php echo $collection['count']?>]</a></li>
                            <?php endforeach;?>

                        </ul>
                    </li>
                    <li><a href="javascript:void(0)" onclick="showCreateCollectionWindows('<?php echo !empty($_GET['dbname'])?$_GET['dbname']:""?>');">Create collection</a></li>
                    <li><a href="javascript:void(0)" onclick="dropCollection('<?php echo !empty($_GET['dbname'])?$_GET['dbname']:""?>','<?php echo !empty($_GET['collection'])?$_GET['collection']:""?>')">Drop collection</a></li>

                </ul>

                <!-- Everything you want hidden at 940px or less, place within here -->
                <div class="nav-collapse collapse">
                    <!-- .nav, .navbar-search, .navbar-form, etc -->
                </div>

            </div>
        </div>
    </div>

    <div class="container">
        1231231

    </div>


</div> <!-- /container -->

<!-- Modal CREATE-->
<div id="createNewElement" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Create new database</h3>
    </div>
    <div class="modal-body">
        <p><input type="text" id="new_name" placeholder="New name" value=""/></p>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" id="btn_create">Create</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>

    </div>
</div>

<!-- Modal CONFIRM-->

<div id="confirmWindow" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="confirmModalLabel">Confirm</h3>
    </div>
    <div class="modal-body">
        <p id="confirmText">Are you sure?</p>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" id="btn_ok">OK</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>

    </div>
</div>