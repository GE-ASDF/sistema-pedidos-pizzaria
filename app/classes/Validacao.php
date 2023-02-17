<?php
namespace app\classes;

use app\models\FindBy;
use app\models\QueryBase;
use app\models\Usuarios\Usuarios;
class Validacao{

    public static function validacao(array $validacoes){
        $result = [];
        $param = '';
        foreach($validacoes as $field => $validate){
            $result[$field] = (!str_contains($validate, "|")) ?
            [$validate, $param] = self::validacaoUnica($validate, $field, $param):
            $result[$field] = self::validacaoMultipla($validate, $field, $param);
        }
        if(in_array(false, $result, true)){
            return false;
        }
        return $result;
    }

    private static function validacaoUnica($validate, $field, $param){
        if(str_contains($validate, ":")){
            [$validate, $param] = explode(":", $validate);
        }
        return self::$validate($field, $param);
    }

    private static function validacaoMultipla($validate, $field, $param){
        $result = [];
        $explodeValidatePipe = explode("|", $validate);
            foreach($explodeValidatePipe as $validate){
                if(str_contains($validate, ":")){
                    [$validate, $param] = explode(":", $validate);
                }
                $result[$field] = self::$validate($field, $param);
                if($result[$field] === false || $result[$field] === null){
                    break;
                }
            }
            return $result[$field];
    }

    private static function required($field){
        
        if(!isset($_POST[$field]) || $_POST[$field] === ''){
            if(!isset($_GET[$field]) || $_GET[$field] === ''){
                setFlash($field, "O campo é obrigatório");
                return false;
            }
        }
        
        $resultado = isset($_GET[$field]) ? strip_tags($_GET[$field]):strip_tags($_POST[$field]);
        return $resultado;
    }
    
    private static function email($field){
        $emailIsValid = filter_input(INPUT_POST, $field, FILTER_VALIDATE_EMAIL);
        if(!$emailIsValid){
            setFlash($field, "O campo precisa ter um {$field} válido.");
            return false;
        }
        
        return filter_input(INPUT_POST, $field, FILTER_SANITIZE_EMAIL);
    }

    private static function maxlen($field, $param){
        $data = strip_tags($_POST[$field]);
        if(strlen($data) > $param){
            setFlash($field, "O campo {$field} tem um limite de {$param} caracteres.");
            return false;
        }
        return $data;
    }
    private static function minlen($field, $param){
        $data = strip_tags($_POST[$field]);
        if(strlen($data) < $param){
            setFlash($field, "O campo {$field} tem um limite de {$param} caracteres.");
            return false;
        }
        return $data;
    }
    private static function unique($field, $param){
        $usuario = new QueryBase();
        $campo = strip_tags($_POST[$field]);
        $existe = $usuario->findBy($param, data:$field, critery:$campo);
        if($existe){
            setFlash($field, "Este dado já está cadastrado em nosso banco de dados.");
            return false;
        }        
        return $campo;
    }
    
    private static function existe($field, $param){
        $usuario = new QueryBase();
        $campo = isset($_POST[$field]) ? strip_tags($_POST[$field]):strip_tags($_GET[$field]);

        if($campo){
            $existe = $usuario->findBy($param, data:$field, critery:$campo);
            if(!$existe){
                setFlash($field, "Este {$field} não está cadastrado no nosso banco de dados.");
                return false;
            }  
        }else{
            setFlash($field, "Nenhum valor foi passado para este campo.");
            return false;
        }              
        return $campo;
    }

    private static function image($field, $param){  
        $image = isset($_FILES) && $_FILES[$field] != '' ? $_FILES[$field]:null;
        $extensao = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        $campo = strip_tags($_POST["idusuario"]);
        $usuario = (new Usuarios)->findBy("idusuario", $campo);
        if($extensao == ''){
            if($usuario){
                return $usuario->foto;
            }else{
                setFlash($field, "Escolha uma imagem para continuar");
                return false;
            }
        }
        
        if($extensao != "jpg" && $extensao != "png" && $extensao != "jpeg" && $extensao != "webp"){
            setFlash($field, "Tipo de arquivo não suportado");
            return false;
        }
        move_uploaded_file($image['tmp_name'],"upload/" . $image['name']);
        return $image['name'];
    }
    private static function data($field){

        $data = $_POST[$field] ? $_POST[$field]:$_GET[$field];
        $date = '';
        if(str_contains($data, "-")){
            $newDataArray = explode("-", $data);
            $dateArray = [
                "dia" => $newDataArray[2],
                "mes" => $newDataArray[1],
                "ano" => $newDataArray[0]
            ];
            $date = checkdate($dateArray["mes"], $dateArray["dia"], $dateArray["ano"]);
            if($date){   
                $data = $dateArray["dia"]."/".$dateArray["mes"]."/".$dateArray["ano"];     
                return $data;
            }
        }

        if(str_contains($data, "/")){
            $newDataArray = explode("/", $data);
            $dateArray = [
                "dia" => $newDataArray[0],
                "mes" => $newDataArray[1],
                "ano" => $newDataArray[2]
            ];
            $date = checkdate($dateArray["mes"], $dateArray["dia"], $dateArray["ano"]);
            if($date){   
                return $data;
            }
        }
        return false;
    }

    private static function senha($field){
        $senha = $_POST[$field];
        $newSenha = password_hash($senha, PASSWORD_DEFAULT);
        return $newSenha;
    }
    private static function optional($field){
        $data = strip_tags($_POST[$field]);

        if($data === ""){
            return null;
        }
        return $data;
    }
    private static function telefone($field){
        $data = isset($_POST[$field]) ? strip_tags($_POST[$field]):strip_tags($_GET[$field]);

        if($data){
            $regex = '/^[0-9]{1,50}$/i';
            $teste = preg_match($regex, $data);
            if($teste == 1){
                return $data;
            }else{
                setFlash($field, "Digite um telefone válido. Sem espaços, parênteses e hifen.");
                return false;
            }
        }else{
            return null;
        }
        return $data;
        
    }
    
}