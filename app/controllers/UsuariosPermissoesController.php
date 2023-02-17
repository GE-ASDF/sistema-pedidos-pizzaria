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

class UsuariosPermissoesController extends Controller{

    public function __construct(){  

        NotLogged::notLogged();
        BlockNotLogged::block($this, ExtractPermissions::extract("usuariospermissoes"));
        BlockNotAdmin::block($this, ExtractPermissions::extract("usuariospermissoes"), "Você não tem permissão para realizar esta ação.");
        
    }

   public function index(){
    $usuarios_sistema = (new QueryBase)->fetchAll("usuarios_sistema as US, funcionarios AS F", fields:"NomeFuncionario, CodigoUsuario", critery:" WHERE US.CodigoFuncionario = F.CodigoFuncionario");
    $controles = (new QueryBase)->fetchAll("controles");
        $dados =[
            "title" => "Cadastro de permissões do usuário",
            "view" => "pages/usuarios_permissoes/Create",
            "usuarios" => $usuarios_sistema,
            "controles" => $controles,
        ];

        $this->load("template", $dados); 
   } 

   public function insert(){

        $insert = false;
        $CodigosMetodos = isset($_POST["CodigoMetodo"]) ? $_POST["CodigoMetodo"]:"";

        if(!$CodigosMetodos){
            setFlash("CodigoMetodo","Nenhum método foi escolhido.");
            redirect(URL_BASE."usuariospermissoes");
        }
      
    
        $dados = [
            "CodigoUsuario" => "required|existe:usuarios_sistema",
            "CodigoControle" => "required|existe:controles",    
        ];

        $validar = (new Validacao)->validacao($dados);
        
        if($validar){
            
            foreach($CodigosMetodos as $CodigoMetodo){
                $dadosNovos = [
                    "CodigoUsuario" => $validar['CodigoUsuario'],
                    "CodigoControle" => $validar['CodigoControle'],
                    "CodigoMetodo" => $CodigoMetodo,
                ];
               
                $findBy = (new QueryBase)->fetch("usuarios_permissoes", $dadosNovos, critery:" WHERE CodigoUsuario = :CodigoUsuario AND CodigoControle = :CodigoControle AND CodigoMetodo = :CodigoMetodo");
                if($findBy){
                    setFlash("fail", "Este usuário já possui estas permissões cadastradas.");
                    redirect(URL_BASE."usuariospermissoes");
                }else{

                    $insert = (new QueryBase)->insert("usuarios_permissoes", $dadosNovos);
                    
                    if($insert){
                        echo json_encode([
                            "CodigoResposta" => 1,
                            "CodigoUsuario" => $insert["CodigoUltimoRegistro"],
                            "typeMessage" => "success",
                            "message" => "As permissões foram cadastrados com sucesso para o usuário."
                        ]);
                        setFlash("success", "As permissões foram cadastrados com sucesso para o usuário.", "success");
                        redirect(URL_BASE . "usuariospermissoes");
                    }else{
                        echo json_encode([
                            "CodigoResposta" => 3,
                            "typeMessage" => "danger",
                            "message" => "As permissões não foram cadastrados para o usuário. Tente novamente!"
                        ]);
                        setFlash("fail", "As permissões não foram cadastrados para o usuário. Tente novamente!");
                        redirect(URL_BASE . "usuariospermissoes");
                    }
                }
            }
        }else{
            echo json_encode([
                "Ultimo" => "Ultimo",
                "CodigoResposta" => 3,
                "typeMessage" => "danger",
                "message" => "Não foi possivel validar os dados. Tente novamente!",
            ]);
            setFlash("fail", "Não foi possivel validar os dados. Tente novamente!");
            redirect(URL_BASE . "usuariospermissoes");
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
            "CodigoControle" => "required",
        ];
     
        $validar = (new Validacao)->validacao($dados);
        
        if($validar){
            $findBy = (new QueryBase)->fetchAll("metodos_controle", $validar, critery:" WHERE CodigoControle = :CodigoControle");
            if($findBy){
                echo json_encode(["CodigoResposta"=> 1, "Metodos" => $findBy]);
                return;
            }else{  
                echo json_encode([
                    "CodigoResposta" => 2,
                    "typeMessage" => "danger",
                    "message" => "Este controle não possui métodos cadastrados no nosso banco de dados"
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
