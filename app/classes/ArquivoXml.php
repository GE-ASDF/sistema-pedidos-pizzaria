<?php
namespace app\classes;

use DOMDocument;
use app\models\Aulas\Aulas;

Class ArquivoXml{

    public static function valida($arquivo, $field){

        if(self::extensao($_FILES[$arquivo]["name"])){
            $file = new DOMDocument;
            $file->load($_FILES[$arquivo]["tmp_name"]);
            $linhas = $file->getElementsByTagName("Row");
            return self::getNodeValue($linhas, $field) ?? false;   
        }else{
            setFlash("message", "O arquivo deve ter a extensão XML.");
            return redirect(URL_BASE."cadastraraulas");
        }
    }

    private static function extensao($arquivo){
        if(strtolower(pathinfo($arquivo, PATHINFO_EXTENSION)) == "xml"){
            return true;
        }else{
            return false;
        }
    }

    private static function getNodeValue($linhas, $field){
        
        $created = false;
        $primeiraLinha = true;
        $dados[] = array();
        foreach($linhas as $linha){
            if($primeiraLinha == false){
                $idcurso = $linha->getElementsByTagName("Data")->item(0)->nodeValue ?? '';
                $nome = $linha->getElementsByTagName("Data")->item(1)->nodeValue ?? '';
                $descricao = $linha->getElementsByTagName("Data")->item(2)->nodeValue ?? '';
                $nraula = $linha->getElementsByTagName("Data")->item(3)->nodeValue ?? '';
                $material_apoio = $linha->getElementsByTagName("Data")->item(4)->nodeValue ?? '';
                $link = $linha->getElementsByTagName("Data")->item(5)->nodeValue ?? '';
                $exercicios = $linha->getElementsByTagName("Data")->item(6)->nodeValue ?? '';
                $simulados = $linha->getElementsByTagName("Data")->item(7)->nodeValue ?? '';
                
                $dados = ([
                    "idcurso"=>$idcurso,
                    "nome"=>$nome,
                    "descricao"=>$descricao,
                    "nraula"=>$nraula,
                    "material_apoio"=>$material_apoio,
                    "link"=>$link,
                    "exercicios"=>$exercicios,
                    "simulados"=>$simulados,
            ]);
                if($_POST[$field] == $dados[$field]){
                    $created = self::sendToCreate($dados);
                }else{
                    setFlash("message", "O idcurso da tabela deve ser igual ao curso do input.");
                    return redirect(URL_BASE."cadastraraulas");
                }
            }
            $primeiraLinha = false;
        }
        return $created;
    }

    private static function sendToCreate($dados){
        return (new Aulas)->create($dados);
    }
    
}