<?php

class MongoWrapper {

    private $mongo;
    private $connection_settings;

    function __construct($conn=""){
        $this->connection_settings = $conn;
        $this->mongo = new MongoClient($conn);
    }



    function getDatabaseList(){
        $result = $this->mongo->admin->command(array("listDatabases" => 1));
        return $result['databases'];
    }

    function humanViewSize($bytes){
        $precision = 2;
        $kilobyte = 1024;
        $megabyte = $kilobyte * 1024;
        $gigabyte = $megabyte * 1024;
        $terabyte = $gigabyte * 1024;
        if (($bytes >= 0) && ($bytes < $kilobyte)) {
            return $bytes . ' B';

        } elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
            return round($bytes / $kilobyte, $precision) . ' KB';

        } elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
            return round($bytes / $megabyte, $precision) . ' MB';

        } elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
            return round($bytes / $gigabyte, $precision) . ' GB';

        } elseif ($bytes >= $terabyte) {
            return round($bytes / $terabyte, $precision) . ' TB';
        } else {
            return $bytes . ' B';
        }
    }

    function getCollectionList($dbname){
        $db = $this->mongo->$dbname;
        $list = $db->listCollections();
        $collection_list = array();
        foreach($list as $connection){
            $coll_name = str_replace($dbname.".","",$connection);
            $coll_cunt = $db->$coll_name->count();
            $collection_list[] = array("name"=>$coll_name,"count"=>$coll_cunt);
        }
        return $collection_list;
    }

    function loadData($dbname, $collection, $limit=100){
        if(!empty($collection) && !empty($dbname)){
            $collection         = filter_var($collection, FILTER_SANITIZE_STRING);
            $db_name            = filter_var($dbname, FILTER_SANITIZE_STRING);
            $limit              = filter_var($limit, FILTER_SANITIZE_NUMBER_INT);

            $db = $this->mongo->$db_name;
            $coll = $db->$collection;;
            return $coll->find();


        }
        return false;
    }

    /**
     * Create new database
     * @param $post
     * @return int
     */
    function create_database($post){
        if(!empty($post['name'])){
            $new_database = filter_var($post['name'], FILTER_SANITIZE_STRING);
            if(!empty($new_database)){
                $db = $this->mongo->$new_database;
                $list = $db->listCollections();
                return 1;
            }
        }
        return 0;
    }

    /**
     * Create new collection
     * @param $post
     */
    function create_collection($post){
        if(!empty($post['name']) && !empty($post['db'])){
            $new_collection  = filter_var($post['name'], FILTER_SANITIZE_STRING);
            $db_name         = filter_var($post['db'], FILTER_SANITIZE_STRING);
            $db = $this->mongo->$db_name;
            $res = $db->createCollection($new_collection);
            return 1;
        }
        return 0;
    }

    /**
     * Drop database
     * @param $post
     * @return int
     */
    function drop_database($post){
        if(!empty($post['name'])){
            $droped_database = filter_var($post['name'], FILTER_SANITIZE_STRING);
            if(!empty($droped_database)){
                $db = $this->mongo->$droped_database;
                $db->drop();
                return 1;
            }
        }
        return 0;
    }

    /**
     * Drop collection
     * @param $post
     * @return int
     */
    function drop_collection($post){
        if(!empty($post['name']) && !empty($post['db'])){
            $droped_collection  = filter_var($post['name'], FILTER_SANITIZE_STRING);
            $db_name            = filter_var($post['db'], FILTER_SANITIZE_STRING);
            $db = $this->mongo->$db_name;
            $coll = $db->$droped_collection;
            $coll->drop();
            return 1;
        }
        return 0;
    }

    function remove_object($post){
        if(!empty($post['collection']) && !empty($post['db']) && !empty($post['mongo_id'])){
            $collection  = filter_var($post['collection'], FILTER_SANITIZE_STRING);
            $db_name            = filter_var($post['db'], FILTER_SANITIZE_STRING);
            $mongo_id  = filter_var($post['mongo_id'], FILTER_SANITIZE_STRING);
            $db = $this->mongo->$db_name;
            $c_collection = $db->$collection;
            $res = $c_collection->remove(array("_id"=>new MongoId($mongo_id)));
            return 1;
        }
        return 0;
    }


}