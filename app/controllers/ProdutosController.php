<?php

namespace app\controllers;

use app\core\Uri;
use app\classes\IsAdmin;
use app\core\Controller;
use app\models\QueryBase;
use app\classes\NotLogged;
use app\classes\Validacao;
use app\classes\BlockNotAdmin;
use app\classes\BlockNotLogged;
use app\classes\ExtractPermissions;

class ProdutosController extends Controller{

    public function __construct(){   
        NotLogged::notLogged();              
        BlockNotLogged::block($this, [ExtractPermissions::extract("produtos", true)]);
        BlockNotAdmin::block($this, ExtractPermissions::extract("produtos"),"Você não tem permissão para realizar esta ação.");
    }

   public function index(){

        $unidades = (new QueryBase)->fetchAll("unidades_medida");       
        $categorias = (new QueryBase)->fetchAll("categorias_produto");   
        $fornecedores = (new QueryBase)->fetchAll("fornecedores");   

        $dados =[
            "title" => "Cadastro de produtos",
            "view" => "pages/produtos/Create",
            "unidades" => $unidades,
            "categorias" => $categorias,
            "fornecedores" => $fornecedores
        ];

        $this->load("template", $dados); 
   } 
   public function editar(){

    $dados = [
        "CodigoProduto" => "required|existe:produtos",
    ];

    $validar = (new Validacao)->validacao($dados);

 
    if($validar){

        $produto = (new QueryBase)->fetch("produtos AS P", $validar, critery:" WHERE P.CodigoProduto = :CodigoProduto");
        $unidades = (new QueryBase)->fetchAll("unidades_medida");       
        $categorias = (new QueryBase)->fetchAll("categorias_produto");       
        $fornecedores = (new QueryBase)->fetchAll("fornecedores"); 

        if($produto->TemEstoque <> 0){
            $estoque = (new QueryBase)->fetch("estoque_produtos", ["CodigoProduto" => $produto->CodigoProduto], critery:" WHERE CodigoProduto = :CodigoProduto");
            $dados =[
                "title" => "Cadastro de produtos",
                "view" => "pages/produtos/Edit",
                "produto" => $produto,
                "estoque" => $estoque,
                "unidades" => $unidades,
                "categorias" => $categorias,
                "fornecedores" => $fornecedores
            ];

        }else{
            
            $dados =[
                "title" => "Cadastro de produtos",
                "view" => "pages/produtos/Edit",
                "produto" => $produto,
                "unidades" => $unidades,
                "categorias" => $categorias,
                "fornecedores" => $fornecedores
            ];
        }

        $this->load("template", $dados); 
    }else{
        redirect(URL_BASE."listarprodutos");
    }

   }

   public function insert(){
    
    if($_POST){

        $dados = [
            "NomeProduto" => "required",
            "PrecoProduto" => "required",
            "PesoProduto" => "optional",
            "CodigoUnidadeMedida" => "required",
            "CodigoCategoria" => "required",
            "CodigoFornecedor" => "required",
            "TemEstoque" => "optional"
        ];

        $validar = (new Validacao)->validacao($dados);

        if($validar){

            $insert = (new QueryBase)->insert("produtos", $validar);

            if($insert){
                echo json_encode([
                    "CodigoProduto" => $insert["CodigoUltimoRegistro"],
                    "typeMessage" => "success",
                    "message" => "O produto foi atualizado com sucesso."
                ]);
                return;
            }else{
                echo json_encode([
                    "typeMessage" => "danger",
                    "message" => "O produto não foi atualizado. Tente novamente mais tarde"
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
    }else{
        echo json_encode([
            "typeMessage" => "danger",
            "message" => "O método de envio dos dados deve ser POST."
        ]);
        return;
    }

   }

   public function update(){

    if($_POST){

        $dados = [
            "CodigoProduto"=> "required",
            "NomeProduto" => "required",
            "PrecoProduto" => "required",
            "PesoProduto" => "optional",
            "CodigoUnidadeMedida" => "required",
            "CodigoCategoria" => "required",
            "CodigoFornecedor" => "required",
            "TemEstoque" => "optional",
            "Quantidade" => "optional",
        ];

        $validar = (new Validacao)->validacao($dados);
    
        if($validar){
       
            $update = (new QueryBase)->update("produtos", $validar, toIgnore:"CodigoProduto",critery:" WHERE CodigoProduto = :CodigoProduto");
            if($dados['Quantidade'] >= 0){
                $findBy = (new QueryBase)->fetch("estoque_produtos", ['CodigoProduto' => $dados['CodigoProduto']], critery:' WHERE CodigoProduto = :CodigoProduto');
                if($findBy->Quantidade <= $dados['Quantidade']){
                    
                }
            }else{

            }
            if($update){
                echo json_encode([
                    "CodigoProduto" => $update["CodigoUltimoRegistro"],
                    "typeMessage" => "success",
                    "message" => "O produto foi atualizado com sucesso."
                ]);
                if($dados['Quantidade'] >= 0){
                    $findBy = (new QueryBase)->fetch("estoque_produtos", ['CodigoProduto' => $dados['CodigoProduto']], critery:' WHERE CodigoProduto = :CodigoProduto');
                    if($findBy->Quantidade <= $dados['Quantidade']){
                        
                    }
                }else{
                    echo json_encode([
                        "CodigoProduto" => $dados["CodigoProduto"],
                        "typeMessage" => "danger",
                        "message" => "Não foi possível alterar a quantidade em estoque. Tente novamente!"
                    ]);
                    return;
                }
                return;
            }else{
                echo json_encode([
                    "typeMessage" => "danger",
                    "message" => "O produto não foi atualizado. Tente novamente mais tarde"
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
    }else{
        echo json_encode([
            "typeMessage" => "danger",
            "message" => "O método de envio dos dados deve ser POST."
        ]);
        return;
    }
   }
   
}

