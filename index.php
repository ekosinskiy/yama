<?php
/**
 * Created by JetBrains PhpStorm.
 * User: atlete
 * Date: 21.03.13
 * Time: 0:17
 * To change this template use File | Settings | File Templates.
 */
include_once 'bundles/php/driver.php';
$mdb_driver = new MongoWrapper("localhost:27017");
$DB_LIST = $mdb_driver->getDatabaseList();

$C_DB_NAME = "";

if(!empty($_GET['dbname'])){
    $dbname = filter_var($_GET['dbname'], FILTER_SANITIZE_STRING);
    $C_DB_NAME = $dbname;
    $COLLECTION_LIST = $mdb_driver->getCollectionList($dbname);
}

if(!empty($_GET['dbname']) && !empty($_GET['collection'])){
    $COLL_EL = $mdb_driver->loadData($_GET['dbname'], $_GET['collection']);
}


if(!empty($_GET['cmd'])){
    $cmd = filter_var($_GET['cmd'], FILTER_SANITIZE_STRING);
    if(method_exists($mdb_driver, $cmd)){
        echo $mdb_driver->$cmd($_POST);
        exit;
    }
}

include_once 'bundles/php/_header.php';

include_once 'bundles/php/_footer.php';