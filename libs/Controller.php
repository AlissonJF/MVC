<?php

class Controller {

    function __construct() {
        //Controller principal
        $this->view = new View();
    }
    
    /**
     * 
     * @param string $name nome do model
     * @param string $path local dos models
     */
    public function loadModel($name, $modelPath = 'models/') {
        
        $path = $modelPath . $name.'_model.php';
        
        if (file_exists($path)) {
            require_once $modelPath .$name.'_model.php';
            
            $modelName = $name . '_Model';
            $this->model = new $modelName();
        }        
    }

}