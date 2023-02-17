<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="<?php echo URL_BASE ?>assets/css/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script>
        document.documentElement.classList.add("js");
    </script>
</head>
<body>
    

    <section style="height:100vh" class="container-fluid bg-primary d-flex align-items-center justify-content-center">
   
        <div class="login container d-flex flex-column justify-content-center align-items-center">
            <div class="mensagem">
                <?php echo getFlash("success"); ?>
                <?php echo getFlash("fail"); ?>
            </div>
            <div class="row mb-4">
                <div class="col-xl">
                    <div data-anime="100" class="fadeInDown row">
                       
                    </div>
                    <div data-anime="100" class="fadeInDown row">
                        
                    </div>
                </div>
            </div>
            <div data-anime="300" class="fadeInDown row rounded bg-dark text-light p-3">
                <div class="row">
                    <div class="mb-3 d-flex flex-column align-items-center justify-content-center">
                        <i class="medium material-icons">person</i>
                        <h5 class="title"><?php echo $config->Nome ?></h5>
                    </div>
                </div>
               
                <form method="POST" action="<?php echo URL_BASE . "login/logar"?>">
                <div class="form-group mb-3">
                        <label for="email">Usuário</label>
                        <input class="form-control" type="email" name="login" id="email">
                        <small> <?php echo getFlash("login") ?></small>
                    </div>
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input class="form-control" type="password" name="senha" id="password">
                        <small><?php echo getFlash("senha") ?></small>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Logar" class="btn btn-primary mt-4">
                        <button type="button" class="btn btn-dark mt-4 flex-start" data-bs-toggle="modal" data-bs-target="#recuperarSenha">
                            Recuperar senha
                        </button>
                    </div>
                </form>
                <button type="button" class="btn btn-dark mt-4 flex-start" data-bs-toggle="modal" data-bs-target="#cadastre-se">
                    Cadastre-se
                </button>
            </div>
        </div>

    </section>


<!-- Modal -->
<div class="modal fade" id="cadastre-se" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="mensagem p-3 mb-4 d-flex justify-content-center align-items-center">
    <div class="d-flex justify-content-center align-items-center">
        <div class="p-3 d-flex justify-content-center align-items-center">
            <?php echo getFlash("email") ?>
        </div>
    </div>
</div>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        
        <h5 class="modal-title" id="exampleModalLabel">Cadastre-se</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body bg-light">
        <form action="<?php echo URL_BASE ."login/cadastrar" ?>" method="POST" id="cadastrar-usuario">
            <div class="form-group mb-2">
                <label for="nome">Nome</label>
                <input class="form-control" type="text" name="nome">
            </div>
            <div class="form-group mb-2">
                <label for="sobrenome">Sobrenome</label>
                <input class="form-control" type="text" name="sobrenome">
            </div>
            <div class="form-group mb-2">
                <label for="email">E-mail</label>
                <input class="form-control" type="email" name="email">
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input class="form-control" type="password" name="senha">
            </div>
            <button type="submit" class="btn btn-primary mt-4">Salvar</button>
            <button type="button" class="btn btn-secondary mt-4 close-modal" data-bs-dismiss="modal">Fechar</button>
        </form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="recuperarSenha" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="mensagem p-3 mb-4 d-flex justify-content-center align-items-center">
    <div class="d-flex justify-content-center align-items-center">
        <div class="p-3 d-flex justify-content-center align-items-center">
            <?php echo getFlash("email") ?>
        </div>
    </div>
</div>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        
        <h5 class="modal-title" id="exampleModalLabel">Digite seu e-mail</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body bg-light">
        <form id="recuperar-senha">
            <div class="form-group mb-2">
                <label for="email2">E-mail:</label>
                <input class="form-control" type="email" name="email">
            </div>
            <button id="btnRecuperar" type="submit" class="btn btn-primary mt-4">Salvar</button>
            <button type="button" class="btn btn-secondary mt-4 close-modal" data-bs-dismiss="modal">Fechar</button>
        </form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

<script src="<?php echo URL_BASE ?>app/views/assets/xhttp.js"></script>
<script src="<?php echo URL_BASE ?>app/views/assets/recuperarsenha.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
<script src="<?php echo URL_BASE ?>assets/js/simple-anime.js"></script>
<script src="<?php echo URL_BASE ?>assets/js/script.js"></script>
</body>
</html>
