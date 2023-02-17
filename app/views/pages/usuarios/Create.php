
<div class="container mt-4">
<div id="cadastro-mensagem-usuario" class="cadastro-mensagem-usuario d-flex justify-content-center align-items-center">
    <?php echo getFlash("success"); ?>    
    <?php echo getFlash("fail"); ?>    
</div>
<div class="card">
    <div class="header">
        <h1 class="title fs-1"><?php echo $title ?></h1>
    </div>
</div>

<div class="card mt-4">
    
    <form id="form-cad-usuarios" method="POST" action="<?php echo URL_BASE ?>usuarios/insert" class="d-grid">

    <div class="row mt-4">
        <div class="form-group col-md">
            <label for="CodigoFuncionario" class="form-label fs-5 mt-4">Funcionário</label>
            <select name="CodigoFuncionario" id="" class="form-select">
                <?php if($funcionarios): ?>
                    <?php foreach($funcionarios as $funcionario): ?>
                        <option value="<?php echo $funcionario->CodigoFuncionario ?> "><?php echo $funcionario->NomeFuncionario ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <?php echo getFlash("CodigoFuncionario"); ?>
        </div>
    </div>
    <div class="form-group mt-4 col-md">
        <label for="Login" class="form-label fs-5">Login</label>
        <input type="text" id="Login" name="Login" class="form-control">
        <?php echo getFlash("Login"); ?>
        <label for="Senha" class="form-label fs-5">Senha</label>
        <input type="text" id="Senha" name="Senha" class="form-control">
        <?php echo getFlash("Senha"); ?>
    </div>
    <div class="p-3 form-group mt-4 d-flex justify-content-start align-items-start">
        <button type="submit" class="btn btn-primary">Registrar</button>
    </div>
    </form>

</div>

</div>

<script>
const formCadUsuarios = document.querySelector("#form-cad-usuarios") ? document.querySelector("#form-cad-usuarios"):"";
const cadastroMensagemUsuario = document.querySelector("#cadastro-mensagem-usuario") ? document.querySelector("#cadastro-mensagem-usuario"):"";

if(formCadUsuarios){
    formCadUsuarios.addEventListener("submit", (e)=>{
        e.preventDefault();

        let dataProduto = new FormData(formCadUsuarios);

        xmlHttpPost("usuarios/insert", ()=>{
            beforeSend(()=>{
                cadastroMensagemUsuario.innerHTML = `
                    <span class="alert d-flex justify-content-between align-items-center alert-primary"> 
                        Aguarde..
                        <span style="cursor:pointer" class="btn-close"></span>
                    </span>
                `
            })
            success(()=>{
                let response = JSON.parse(xhttp.response);
   
                let btnClosesfuncionarios = document.getElementsByClassName("btn-close");

                    cadastroMensagemUsuario.innerHTML = `
                    <span class="alert d-flex justify-content-between align-items-center alert-${response.typeMessage}"> 
                        ${response.message}
                        <span style="cursor:pointer" class="btn-close"></span>
                    </span>
                `                  
                
                Array.from(btnClosesfuncionarios).forEach(btnClosefuncionario => {
                    btnClosefuncionario.addEventListener("click", (e)=>{
                        e.target.parentNode.remove();
                    })
                })
            })
            erro((erro)=>{
                console.log(erro)
            })
        }, dataProduto)

    })
}

</script>

