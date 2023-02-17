<?php

namespace app\controllers;

use app\core\Controller;
use app\models\QueryBase;
use app\classes\NotLogged;
use app\classes\Validacao;
use app\classes\BlockNotAdmin;
use app\classes\BlockNotLogged;
use app\classes\ExtractPermissions;

class UsuariosController extends Controller{

    public function __construct(){  

        NotLogged::notLogged();
        BlockNotLogged::block($this, ExtractPermissions::extract("usuarios"));
        BlockNotAdmin::block($this, ExtractPermissions::extract("usuarios"), "Você não tem permissão para realizar esta ação.");
        
    }

   public function index(){
    $funcionarios = (new QueryBase)->fetchAll("funcionarios");

        $dados =[
            "title" => "Cadastro de usuários",
            "view" => "pages/usuarios/Create",
            "funcionarios" => $funcionarios,
        ];

        $this->load("template", $dados); 
   } 

   public function insert(){

        $insert = false;
            
        $dados = [
            "CodigoFuncionario"=> "required|existe:funcionarios|unique:usuarios_sistema",
            "Login" => "required|unique:usuarios_sistema",
            "Senha" => "required|minlen:3",
        ];

        $validar = (new Validacao)->validacao($dados);

        if($validar){

            $insert = (new QueryBase)->insert("usuarios_sistema", $validar);
                
                if($insert){
                    echo json_encode([
                        "CodigoResposta" => 1,
                        "CodigoUsuario" => $insert["CodigoUltimoRegistro"],
                        "typeMessage" => "success",
                        "message" => "O usuário foi cadastrado com sucesso."
                    ]);
                    // setFlash("success", "O usuário foi cadastrado com sucesso!", "success");
                    // redirect(URL_BASE . "usuarios");
                    return;
                }else{
                    echo json_encode([
                        "CodigoResposta" => 3,
                        "typeMessage" => "danger",
                        "message" => "O usuario não foi cadastrado. Tente novamente!"
                    ]);
                    // setFlash("fail", "O usuario não foi cadastrado!");
                    // redirect(URL_BASE . "usuarios");
                    return;
                }
            
            
        }else{
            echo json_encode([
                "Ultimo" => "Ultimo",
                "CodigoResposta" => 3,
                "typeMessage" => "danger",
                "message" => "
                    <div class='d-flex flex-column'>
                    O usuario não foi cadastrado. <strong>Verifique os seguintes parâmetros:</strong>
                    <ul>
                        <li>Se o nome do usuario foi informado;</li>
                        <li>Se este usuário já não está cadastrado no sistema;</li>
                        <li>Se login e senha foram informados.</li>
                    </ul>
                    </div>
                "
            ]);
            // setFlash("fail", "O usuario não foi cadastrado. </br> Verifique os seguintes parâmetros:</br>
            // <ul>
            //     <li>Se o nome do usuario foi informado;</li>
            //     <li>Se o Login informado já está cadastrado</li>
            //     <li>Se o Senha foi informado.</li>
            // </ul>");
            // redirect(URL_BASE . "usuarios");
            return;
        }

   }
   public function delete(){
        $dados = [
            "CodigoCliente" => "required|existe:clientes",
        ];

        $validar = (new Validacao)->validacao($dados);
        
        if($validar){
            $delete = (new QueryBase)->delete("clientes", $validar, " WHERE CodigoCliente = :CodigoCliente");
            if($delete){
                setFlash("success", "O cliente foi deletado com sucesso", "success");
                redirect(URL_BASE."listarclientes");
            }else{
                setFlash("success", "O cliente não foi deletado.");
                redirect(URL_BASE."listarclientes");
            }
        }else{
            setFlash("success", "O cliente não foi deletado, pois não foi possível validar os dados.");
            redirect(URL_BASE."listarclientes");
        }

   }
   public function findBy(){

        $dados = [
            "TelCliente" => "required",
        ];

        $validar = (new Validacao)->validacao($dados);
      
        if($validar){
            $findBy = (new QueryBase)->fetch("clientes", $validar, critery:" WHERE TelCliente = :TelCliente");
            if($findBy){
                echo json_encode([
                    "CodigoResposta" => 1,
                    "CodigoCliente" => $findBy->CodigoCliente,
                    "typeMessage" => "success",
                    "message" => "O cliente já está cadastrado em nosso banco de dados."
                ]);
                return;
            }else{  
                echo json_encode([
                    "CodigoResposta" => 2,
                    "typeMessage" => "danger",
                    "message" => "Este telefone não foi encontrado no nosso banco de dados."
                ]);
                return; 
            }
        }else{
            echo json_encode([
                "CodigoResposta" => 3,
                "typeMessage" => "danger",
                "message" => "Não foi possível validar os dados. Tente novamente!"
            ]);
            return;
        }

   }
}
