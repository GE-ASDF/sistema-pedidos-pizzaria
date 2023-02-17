<?php

namespace app\core;

use PDO;


class Model{
    
    protected $db;
    
    public function __construct() {
		 $opcoes = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND =>"Set NAMES utf8",
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        );
        return $this->db = new PDO("mysql:dbname=".BANCO.";host=".SERVIDOR,USUARIO,SENHA, $opcoes);
    }
}

