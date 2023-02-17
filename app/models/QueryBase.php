<?php
namespace app\models;

use app\core\Model;

class QueryBase extends Model{

    public function insert(string $table, array $data, $fields = '', $critery = ''){
        $sql = "INSERT INTO {$table}(";
        $sql .= implode(", ", array_keys($data)) .") VALUES(";
        $sql .= ":".implode(", :", array_keys($data)) . ")";
        $prepare = $this->db->prepare($sql);
        foreach($data as $key => $value){
            $prepare->bindValue(":{$key}", $value);
        }
        $prepare->execute();
        return [
            "CodigoUltimoRegistro" => $this->db->lastInsertId(),
            "LinhasAlteradas" => $prepare->rowCount()
        ];
        // return $prepare->rowCount();
    }

    public function update(string $table, array $data, $toIgnore = '', $fields = '', $critery = ''){
        $sql = "UPDATE {$table} SET ";
        

        foreach($data as $key => $dado){
            if($key === $toIgnore){
                continue;
            }else{
                $sql .= " {$key}=:{$key},";
            }
        }

        $sql = rtrim($sql, ",");
        $sql .= " {$critery} ";
        $prepare = $this->db->prepare($sql);
        $prepare->execute($data);
        return [
            "CodigoUltimoRegistro" => $this->db->lastInsertId(),
            "LinhasAlteradas" => $prepare->rowCount()
        ];
        // return $prepare->rowCount();
    }


    public function findBy(string $table, array|string $data = array(), string $fields = '*', $critery = ''){
        $sql = "SELECT {$fields} FROM {$table} WHERE {$data} = :{$data}";
        $query = $this->db->prepare($sql);
        $query->execute([":{$data}" => $critery]);
        return $query->fetch();
    }

    public function fetchAll(string $table, array|string $data = array(), string $fields = "*", string $critery = ''){
        $sql = "SELECT {$fields} FROM {$table} $critery";
        $query = $this->db->prepare($sql);
        $query->execute($data);
        return $query->fetchAll();
    }
    
    public function fetch(string $table, array $data = array(), string $fields = "*", string $critery = ''){
        $sql = "SELECT {$fields} FROM {$table} {$critery}";
        $query = $this->db->prepare($sql);
        $query->execute($data);
        return $query->fetch();
    }
    public function delete(string $table, array|string $data = array(), string $critery = ''){
        $sql = "DELETE FROM {$table} {$critery}";
        $query = $this->db->prepare($sql);
        $query->execute($data);
        return $query->rowCount();
    }
}