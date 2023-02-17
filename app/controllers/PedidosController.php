<?php

namespace app\controllers;

use app\classes\BlockNotAdmin;
use app\core\Controller;
use app\models\QueryBase;
use app\classes\NotLogged;
use app\classes\Validacao;
use app\classes\BlockNotLogged;
use app\classes\ExtractPermissions;
use app\classes\IsAdmin;

class PedidosController extends Controller{

    public function __construct(){   

        NotLogged::notLogged();
        BlockNotLogged::block($this, ExtractPermissions::extract("pedidos"));
        BlockNotAdmin::block($this, ExtractPermissions::extract("pedidos"), "Você não tem permissão para realizar esta ação");
        
    }

   public function index(){
        redirect(URL_BASE);
   } 

   public function select(){
        $pedidos = (new QueryBase)->fetchAll("pedidos", fields:"CodigoPedido");
        if($pedidos){
            echo $pedidos;
        }else{
            echo 0;
        }
   }
   public function insert(){
        $validar = [
            "CodigoCliente" => "required|existe:clientes",
            "CodigoFuncionario" => "optional",
            "CodigoTipoPedido" => "optional|existe:tipo_pedido",
            "DataPedido" => "required",
            "HoraPedido" => "required",
        ];
        $validar = (new Validacao)->validacao($validar);

        if($validar){
            $insert = (new QueryBase)->insert("pedidos", $validar);
            if($insert){
                setFlash("success", "O pedido foi registrado.","success");
                redirect(URL_BASE."detalhespedido/?CodigoPedido={$insert['CodigoUltimoRegistro']}");
            }else{  
                setFlash("fail", "O pedido não foi cadastrado. Tente novamente!");
                redirect(URL_BASE);
            }
        }else{
            setFlash("fail", "Não foi possível validar os dados. Tente novamente!");
            redirect(URL_BASE);
        }
   }

public function insert2(){
    $validar = [
        "CodigoCliente" => "required|existe:clientes",
        "DataPedido" => "required",
        "HoraPedido" => "required",
    ];
    $validar = (new Validacao)->validacao($validar);

    if($validar){
        $insert = (new QueryBase)->insert("pedidos", $validar);
        if($insert){
            echo json_encode([
                "CodigoPedido" => $insert["CodigoUltimoRegistro"],
                "typeMessage" => "success",
                "message" => "O pedido para este cliente foi registrado."
                ]);
            return;
        }else{  
            echo json_encode([
                "typeMessage" => "danger",
                "message" => "Não foi possível criar um novo pedido para este cliente."
                ]);
            return;
        }
    }else{
        echo json_encode([
        "typeMessage" => "danger",
        "message" => "Não foi possível validar os dados. Tente novamente!"
        ]);
        return;
    }
}   
    
   
}
