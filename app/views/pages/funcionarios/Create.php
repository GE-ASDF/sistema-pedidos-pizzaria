
<div class="container mt-4">
    <div id="cadastro-mensagem-funcionario" class="mensagem">
        <?php echo getFlash("success"); ?>
        <?php echo getFlash("fail"); ?>
    </div>
    <h1>Cadastro de funcionários</h1>

    <div class="card mt-3">
        <form action="<?php echo URL_BASE ?>funcionarios/insert" id="form-cadastro-funcionarios" method="POST">
            <div class="form-group d-grid">
                <h2 class="text fs-5">Informações pessoais</h2>
                <div class="row">
                    <div class="form-group col-md">
                        <label for="" class="form-label">Nome do funcionário</label>
                        <input type="text" name="NomeFuncionario" class="form-control">
                    </div>
                    <div class="form-group col-md">
                        <label for="" class="form-label">Telefone do funcionário</label>
                        <input type="text" name="TelFuncionario" class="form-control">
                    </div>
                    <div class="form-group col-md">
                        <label for="" class="form-label">CPF do funcionário</label>
                        <input type="text" name="Cpf" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group d-grid mt-4">
                <div class="row">
                <div class="form-group col-md">
                        <label for="" class="form-label">Data de nascimento</label>
                        <input type="text" name="DataNascimento" class="form-control">
                    </div>
                    <div class="form-group col-md">
                        <label for="" class="form-label">Data de admissão</label>
                        <input type="text" name="DataAdmissao" class="form-control">
                    </div>
                    <div class="form-group col-md">
                        <label for="" class="form-label">Data de demissão</label>
                        <input type="text" name="DataDemissao" class="form-control">
                    </div>
                    <div class="form-group col-md">
                        <label for="" class="form-label">Cargo do funcionário</label>
                        <select name="CodigoCargo" id="CodigoCargo" class="form-select">
                            <option value="0">Selecione um cargo</option>
                            <?php if($cargos): ?>
                                <?php foreach($cargos as $cargo): ?>
                                    <option value="<?php echo $cargo->CodigoCargo ?>" class="select-option">
                                        <?php echo $cargo->NomeCargo ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group d-grid mt-4">
                <h2 class="text fs-5">Endereço do funcionario</h2>
                <div class="row">
                    <div class="form-group col-md">
                        <label for="" class="form-label">Rua</label>
                        <input type="text" name="Rua" class="form-control">
                    </div>
                    <div class="form-group col-md">
                        <label for="" class="form-label">Número</label>
                        <input type="text" name="Numero" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md">
                        <label for="" class="form-label">Bairro</label>
                        <input type="text" name="Bairro" class="form-control">
                    </div>
                    <div class="form-group col-md">
                        <label for="" class="form-label">Complemento</label>
                        <input type="text" name="Complemento" class="form-control">
                    </div>
                </div>
            </div>
            <button class="btn btn-primary mt-4" type="submit">Registrar</button>
        </form>
    </div>

</div>

<script>
const formCadastrofuncionarios = document.querySelector("#form-cadastro-funcionarios") ? document.querySelector("#form-cadastro-funcionarios"):"";
const cadastroMensagemfuncionario = document.querySelector("#cadastro-mensagem-funcionario") ? document.querySelector("#cadastro-mensagem-funcionario"):"";

if(formCadastrofuncionarios){
    formCadastrofuncionarios.addEventListener("submit", (e)=>{
        e.preventDefault();
        let datafuncionario = new FormData(formCadastrofuncionarios);
        xmlHttpPost("funcionarios/insert", ()=>{
            beforeSend(()=>{
                cadastroMensagemfuncionario.innerHTML = `
                    <span class="alert d-flex justify-content-between align-items-center alert-primary"> 
                        Aguarde..
                        <span style="cursor:pointer" class="btn-close"></span>
                    </span>
                `
            })
            success(()=>{
                let response = JSON.parse(xhttp.response);
   
                let btnClosesfuncionarios = document.getElementsByClassName("btn-close");

                    cadastroMensagemfuncionario.innerHTML = `
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
        }, datafuncionario)

    })
}

</script>