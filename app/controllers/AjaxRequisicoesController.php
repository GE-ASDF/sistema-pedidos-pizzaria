<?php
namespace app\controllers;

use app\models\QueryBase;
use app\classes\NotLogged;
use app\classes\Validacao;
use app\core\Controller;

class AjaxRequisicoesController extends Controller{
    
    public function __construct()
    {
        NotLogged::notLogged();
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

   public function fetchPedidoPronto(){

        $dados = [
            "PedidoPronto" => "required",
            "DataPedido" => "required|data",
        ];

        $validar = (new Validacao)->validacao($dados);   
        
        if($validar){
            
            $pedidosProntos = (new QueryBase)->fetchAll("pedidos, clientes", $validar, critery:" WHERE clientes.CodigoCliente = pedidos.CodigoCliente AND PedidoPronto = :PedidoPronto AND DataPedido = :DataPedido ORDER BY HoraPedido DESC");
        
            if($pedidosProntos){
                echo json_encode([
                    "CodigoResposta" => 1,
                    "Pedidos" => $pedidosProntos,
                    "typeMessage" => "success",
                ]);
                return;
            }else{
                $dataActual = date('d/m/Y');
                echo json_encode([
                "CodigoResposta" => 2,
                "typeMessage" => "danger",
                "message" => "Não há pedidos prontos para: {$validar['DataPedido']}"
            ]);
            return; 
        }
        }else{
        echo json_encode([
            "CodigoResposta" => 2,
            "typeMessage" => "danger",
            "message" => "Não foi possível validar os dados"
        ]);
        return; 
    }
}
    public function possuiEstoque(){

        $dados = [
            "CodigoProduto" => "required|existe:produtos",
        ];

        $validar = (new Validacao)->validacao($dados);

        if($validar){
            $findBy = (new QueryBase)->fetch("produtos", $validar, critery:" WHERE CodigoProduto = :CodigoProduto AND TemEstoque = 1");
            if($findBy){
                $estoque = (new QueryBase)->fetch("estoque_produtos", $validar, critery: " WHERE CodigoProduto = :CodigoProduto");
                if($estoque){
                    echo json_encode([
                        "CodigoResposta" => 2,
                        "typeMessage" => "danger",
                        "message" => "Produto em estoque",
                    ]);
                    return;
                }else{
                    echo json_encode([
                        "CodigoResposta" => 2,
                        "typeMessage" => "danger",
                        "message" => "Este produto não tem quantidade suficiente no estoque.",
                    ]);
                    return;
                }
            }else{
                echo json_encode([
                    "CodigoResposta" => 2,
                    "typeMessage" => "",
                    "message" => "",
                ]);
                return;
            }
        }else{
            echo json_encode([
                "CodigoResposta" => 2,
                "typeMessage" => "danger",
                "message" => "Não foi possível validar os dados"
            ]);
            return; 
        }

    }
}