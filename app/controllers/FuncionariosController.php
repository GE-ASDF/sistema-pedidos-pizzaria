<?php

namespace app\controllers;

use app\core\Controller;
use app\models\QueryBase;
use app\classes\NotLogged;
use app\classes\Validacao;
use app\classes\BlockNotAdmin;
use app\classes\BlockNotLogged;
use app\classes\ExtractPermissions;

class FuncionariosController extends Controller{

    public function __construct(){   

        NotLogged::notLogged();
        BlockNotLogged::block($this,ExtractPermissions::extract("funcionarios"));
        BlockNotAdmin::block($this, ExtractPermissions::extract("funcionarios"), "Você não tem permissão para realizar esta ação.");

    }

   public function index(){
        $cargos = (new QueryBase)->fetchAll("cargos");
        $dados =[
            "title" => "Cadastro de funcionarios",
            "view" => "pages/funcionarios/Create",
            "cargos" => $cargos,
        ];

        $this->load("template", $dados); 
   } 

   public function insert(){
        $insert = false;

        $dados = [
            "NomeFuncionario" => "required",
            "TelFuncionario" => "optional|unique:funcionarios",
            "DataAdmissao" => "required",
            "DataDemissao" => "optional",
            "DataNascimento" => "required",
            "CodigoCargo" => "optional",
            "Cpf" => "optional|unique:funcionarios",
            "Rua" => "optional",
            "Numero" => "optional",
            "Bairro" => "optional",
            "Complemento" => "optional"
        ];

        
        $validar = (new Validacao)->validacao($dados);

        if($validar){

            $insert = (new QueryBase)->insert("funcionarios", $validar);
                
                if($insert){
                    echo json_encode([
                        "CodigoFuncionario" => $insert,
                        "CodigoResposta" => 1,
                        "typeMessage" => "success",
                        "message" => "O funcionário foi cadastrado com sucesso."
                    ]);
                    return;
                }else{
                    echo json_encode([
                        "CodigoResposta" => 3,
                        "typeMessage" => "danger",
                        "message" => "O funcionário não foi cadastrado. Tente novamente!"
                    ]);
                    return;
                }
            
            
        }else{
            echo json_encode([
                "Ultimo" => "Ultimo",
                "CodigoResposta" => 3,
                "typeMessage" => "danger",
                "message" => "O funcionário não foi cadastrado. </br> Verifique os seguintes parâmetros:</br>
                    <ul>
                        <li>Se o nome do funcionário foi informado;</li>
                        <li>Se o telefone informado já está cadastrado</li>
                        <li>Se o CPF informado já está cadastrado</li>
                        <li>Se a data de admissão foi informada</li>
                    </ul>
                "
            ]);
            return;
        }

   }

}
