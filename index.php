<?php

require 'config.php';

// funcao que carrega as classes automaticamente
spl_autoload_register('myAutoloader');

function myAutoloader($class) {
    /*caso ignorar load da classe*/
    if (strpos($class, 'Fpdi') === false) {
        require_once LIBS . $class .".php";
    }
}

// carrega o bootstrap - inicializador
$bootstrap = new Bootstrap();

// caminhos opcionais
//$bootstrap->setControllerPath();
//$bootstrap->setModelPath();
//$bootstrap->setDefaultFile();
//$bootstrap->setErrorFile();
$bootstrap->init();