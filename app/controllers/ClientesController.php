<?php

namespace app\controllers;

use app\core\Controller;
use app\classes\AjaxType;
use app\models\QueryBase;
use app\classes\NotLogged;
use app\classes\Validacao;
use app\classes\BlockNotAdmin;
use app\classes\BlockNotLogged;
use app\classes\ExtractPermissions;

class ClientesController extends Controller{

    public function __construct(){  

        NotLogged::notLogged();
        BlockNotLogged::block($this, ExtractPermissions::extract("clientes"));
        BlockNotAdmin::block($this, ExtractPermissions::extract("clientes"), "Você não tem permissão para realizar esta ação");
        
    }

   public function index(){
    $tipoPedidos = (new QueryBase)->fetchAll("tipo_pedido");

        $dados =[
            "title" => "Cadastro de clientes",
            "view" => "pages/clientes/Create",
            "tipoPedidos" => $tipoPedidos,
        ];

        $this->load("template", $dados); 
   } 

   public function insert(){

        $insert = false;
            
        $dados = [
            "NomeCliente" => "required",
            "TelCliente" => "optional|telefone|unique:clientes",
            "Rua" => "optional",
            "Numero" => "optional",
            "Bairro" => "optional",
            "Complemento" => "optional"
        ];

        $validar = (new Validacao)->validacao($dados);

        if($validar){

            $insert = (new QueryBase)->insert("clientes", $validar);
                
                if($insert){
                    echo json_encode([
                        "CodigoResposta" => 1,
                        "CodigoCliente" => $insert["CodigoUltimoRegistro"],
                        "typeMessage" => "success",
                        "message" => "O cliente foi cadastrado com sucesso."
                    ]);
                    // setFlash("success", "O cliente foi cadastrado com sucesso!", "success");
                    // setOld("CodigoCliente", $insert["CodigoUltimoRegistro"]);
                    // redirect(URL_BASE . "clientes");
                    return;
                }else{
                    echo json_encode([
                        "CodigoResposta" => 3,
                        "typeMessage" => "danger",
                        "message" => "O cliente não foi cadastrado. Tente novamente!"
                    ]);
                    // setFlash("fail", "O cliente não foi cadastrado!");
                    // redirect(URL_BASE . "clientes");
                    return;
                }
            
            
        }else{
            echo json_encode([
                "Ultimo" => "Ultimo",
                "CodigoResposta" => 3,
                "typeMessage" => "danger",
                "message" => "
                    <div class='d-flex flex-column'>
                    O cliente não foi cadastrado. <strong>Verifique os seguintes parâmetros:</strong>
                    <ul>
                        <li>Se o nome do cliente foi informado;</li>
                        <li>Se o telefone informado já está cadastrado;</li>
                        <li>Se o telefone informado é válido.</li>
                    </ul>
                    </div>
                "
            ]);
            // setFlash("fail", "O cliente não foi cadastrado. </br> Verifique os seguintes parâmetros:</br>
            // <ul>
            //     <li>Se o nome do cliente foi informado;</li>
            //     <li>Se o telefone informado já está cadastrado</li>
            // </ul>");
            // redirect(URL_BASE . "clientes");
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
