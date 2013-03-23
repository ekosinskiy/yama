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
}