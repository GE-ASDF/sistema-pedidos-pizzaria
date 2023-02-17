<?php
namespace app\interfaces;

use Reflection;
use ReflectionClass;

abstract class ActiveRecord implements ActiveRecordInterface{

    protected $table = null;
    protected $atributos = [];
    protected $valor;

    
    public function __construct($atributos, $valor){
        if(!$this->table){
            $this->table = strtolower((new ReflectionClass($this))->getShortName());
        }
    }

    public function __set($atributos, $valor){
        $this->atributos[$atributos] = $valor;
    }

    public function __get($atributos){
        return $atributos[$atributos];
    }

    public function geTable(){
        return $this->table;
    }

    public function getAtributos(){
        return $this->atributos;
    }

    public function execute(ActiveRecordExecuteInterface $activeRecordExecuteInterface){
        return $activeRecordExecuteInterface->execute($this);
    }
    
}