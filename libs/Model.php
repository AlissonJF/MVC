<?php

class Model {

    function __construct() {
        
        $this->db = new Databasemysql();
        $this->dbora = new Database(DB_TNS,DB_USER, DB_PASS);
        
    }

}