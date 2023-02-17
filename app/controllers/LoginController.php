<?php

namespace app\controllers;

use app\classes\Validacao;
use app\core\Controller;
use app\models\QueryBase;

class LoginController extends Controller{
    

   public function index(){
        $config = (new QueryBase)->fetch("config");
  
        $dados =[
            "title" => "Acesso restrito",
            "view" => "login",
            "config" => $config,
        ];

        $this->load("login", $dados); 
   } 

   public function logar(){

        if($_POST){
            $dados = [
                "login" => "required",
                "senha" => "required",
            ];
            $dadosUsuario = array();
            $validar = (new Validacao)->validacao($dados);
            if($validar){
                $fetch = (new QueryBase)->fetch("usuarios_sistema", $validar, critery:" WHERE login= :login AND senha = :senha");
                if($fetch){
                    $usuario = (new QueryBase)->fetch("usuarios_sistema AS US, funcionarios as F, cargos AS C", 
                    ["login" => $fetch->Login], critery:"
                     WHERE US.login = :login AND F.CodigoFuncionario = US.CodigoFuncionario AND 
                     C.CodigoCargo = F.CodigoCargo");
                    unset($usuario->Senha);
                    echo "<pre>";  
                    $controles = (new QueryBase)->fetchAll("controles AS C, usuarios_permissoes AS UP", ["CodigoUsuario" => $usuario->CodigoUsuario], fields:"DISTINCT C.NomeControle, C.CodigoControle, C.Descricao", critery: "WHERE UP.CodigoUsuario = :CodigoUsuario AND C.CodigoControle = UP.CodigoControle");
                    $bloqueios = array();
                    foreach($controles as $controle){
                        $usuarios_permissoes = (new QueryBase)->fetchAll("usuarios_permissoes AS UP, metodos_controle AS MC", ["CodigoControle" => $controle->CodigoControle, "CodigoUsuario" => $usuario->CodigoUsuario], critery:"
                         where UP.CodigoControle = :CodigoControle AND UP.CodigoUsuario = :CodigoUsuario AND MC.CodigoControle = :CodigoControle AND MC.CodigoMetodo = UP.CodigoMetodo");
                        $bloqueios[] = [
                            "NomeControle" => $controle->NomeControle,
                            "Descricao" => $controle->Descricao,
                            "Metodos" => $usuarios_permissoes,
                        ];
                    }
                  
                    $dadosUsuario = [
                            "CodigoFuncionario" => $usuario->CodigoFuncionario,
                            "NomeFuncionario" => $usuario->NomeFuncionario,
                            "CodigoUsuario" => $usuario->CodigoUsuario,
                            "Cargo" => $usuario->NomeCargo,
                            "Bloqueios" => $bloqueios,
                        ];
                        $_SESSION[SESSION_LOGIN] = $dadosUsuario;
                        redirect(URL_BASE);
                    

                }else{
                    setFlash("fail", "Nenhum usuário foi encontrado. Tente novamente!");
                    redirect(URL_BASE."login");
                }
            }else{
                setFlash("fail", "Não foi possível validar os dados. Tente novamente!");
                redirect(URL_BASE."login");
            }
        }else{
            redirect(URL_BASE."login");
        }

   }
   public function logout(){

    if(isset($_SESSION[SESSION_LOGIN])){
        unset($_SESSION[SESSION_LOGIN]);
        redirect(URL_BASE."login");
    }

   }
}
