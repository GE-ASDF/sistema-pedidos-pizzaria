<?php

namespace app\controllers;

use app\classes\IsAdmin;
use app\core\Controller;
use app\classes\AjaxType;
use app\models\QueryBase;
use app\classes\NotLogged;
use app\classes\Validacao;
use app\classes\BlockNotAdmin;
use app\classes\BlockNotLogged;
use app\classes\ExtractPermissions;

class CargosController extends Controller{

    public function __construct(){   

        NotLogged::notLogged();
        BlockNotLogged::block($this,ExtractPermissions::extract("cargos"));
        BlockNotAdmin::block($this, ExtractPermissions::extract("cargos"), "Você não tem permissão para realizar esta ação.");
    }

   public function index(){

        $CodigoCargo = isset($_GET["CodigoCargo"]) ? strip_tags($_GET["CodigoCargo"]):"";

        $dados =[
            "title" => "Cadastro de cargos",
            "view" => "pages/cargos/Create",
        ];
        if($CodigoCargo){
            $cargo = (new QueryBase)->fetch("cargos", ["CodigoCargo"=> $CodigoCargo], critery:" WHERE CodigoCargo = :CodigoCargo");
            $dados = [
                "cargo" => $cargo,
                "title" => "Cadastro de cargos",
                "view" => "pages/cargos/Create",
            ];
        }
        $this->load("template", $dados); 
        
   } 

   public function insert(){

        $insert = false;

        $dados = [
            "NomeCargo" => "required",
            "SalarioCargo" => "required",
            "TemPromocao" => "optional",
            "TemComissao" => "optional",
            "ValorComissao" => "optional",
        ];
        
        
        $validar = (new Validacao)->validacao($dados);


        if($validar){

            $insert = (new QueryBase)->insert("cargos", $validar);
                
                if($insert){
                    echo json_encode([
                        "CodigoCargo" => $insert,
                        "CodigoResposta" => 1,
                        "typeMessage" => "success",
                        "message" => "O cargo foi cadastrado com sucesso."
                    ]);
                    return;
                }else{
                    echo json_encode([
                        "CodigoResposta" => 3,
                        "typeMessage" => "danger",
                        "message" => "O cargo não foi cadastrado. Tente novamente!"
                    ]);
                    return;
                }
            
            
        }else{
            echo json_encode([
                "Ultimo" => "Ultimo",
                "CodigoResposta" => 3,
                "typeMessage" => "danger",
                "message" => "O cargo não foi cadastrado."
            ]);
            return;
        }

   }
   public function allCargos(){
        $cargos = (new QueryBase)->fetchAll("cargos");
        echo json_encode($cargos);
        return;
   }
   public function delete(){

        $dados = [
            "CodigoCargo" => "required|existe:cargos",
        ];

        $validar = (new Validacao)->validacao($dados);

        if($validar){
            var_dump($validar);
            $delete = (new QueryBase)->delete("cargos", $validar, " WHERE CodigoCargo = :CodigoCargo");
            if($delete){
                setFlash("success", "O cargo foi excluído com sucesso.", "success");
                redirect(URL_BASE."/cargos");
            }else{
                setFlash("fail", "O cargo não foi excluído. Tente novamente!");
                redirect(URL_BASE."/cargos");
            }
        }else{
            setFlash("fail", "O cargo não foi excluído. Ocorreu um erro. Tente novamente!");
            redirect(URL_BASE."/cargos");
        }
   }
   public function update(){
    $insert = false;

    $dados = [
        "CodigoCargo"=>"required|existe:cargos",
        "NomeCargo" => "required",
        "SalarioCargo" => "required",
        "TemPromocao" => "optional",
        "TemComissao" => "optional",
        "ValorComissao" => "optional",
    ];
    
    
    $validar = (new Validacao)->validacao($dados);

    if($validar){

        $insert = (new QueryBase)->update("cargos", $validar, toIgnore:"CodigoCargo", critery:" WHERE CodigoCargo = :CodigoCargo");
            
            if($insert){
                echo json_encode([
                    "CodigoCargo" => $insert,
                    "CodigoResposta" => 1,
                    "typeMessage" => "success",
                    "message" => "O cargo foi atualizado com sucesso."
                ]);
                return;
            }else{
                echo json_encode([
                    "CodigoResposta" => 3,
                    "typeMessage" => "danger",
                    "message" => "O cargo não foi atualizado. Tente novamente!"
                ]);
                return;
            }
        
        
    }else{
        echo json_encode([
            "Ultimo" => "Ultimo",
            "CodigoResposta" => 3,
            "typeMessage" => "danger",
            "message" => "Não foi possível validar os dados. Tente novamente!"
        ]);
        return;
    }

}
}
