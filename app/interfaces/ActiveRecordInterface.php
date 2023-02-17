<?php
namespace app\interfaces;

interface ActiveRecordInterface{
    
    public function execute(ActiveRecordExecuteInterface $activeRecordInterface);
    public function __set($atributos, $valor);
    public function __get($atributos);
    public function getTable();
    public function getAtributos();

}