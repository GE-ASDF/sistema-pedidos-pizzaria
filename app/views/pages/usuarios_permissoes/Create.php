
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
    
    <form id="form-cad-usuarios" method="POST" action="<?php echo URL_BASE ?>usuariospermissoes/insert" class="d-grid">

    <div class="row mt-4">
        <div class="form-group col-md">
            <label for="CodigoUsuario" class="form-label fs-5 mt-4">Funcionário</label>
            <select name="CodigoUsuario" id="" class="form-select">
                <?php if($usuarios): ?>
                    <?php foreach($usuarios as $usuario): ?>
                        <option value="<?php echo $usuario->CodigoUsuario ?> "><?php echo $usuario->NomeFuncionario ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <?php echo getFlash("CodigoUsuario"); ?>
        </div>
    </div>
    <div class="form-group mt-4 col-md">
    <div class="form-group col-md">
            <label for="CodigoControle" class="form-label fs-5 mt-4">Controles</label>
            <select name="CodigoControle" id="CodigoControle" class="form-select">
                <option value="0">Selecione um controle</option>
                <?php if($controles): ?>
                    <?php foreach($controles as $controle): ?>
                        <option value="<?php echo $controle->CodigoControle ?> "><?php echo $controle->NomeControle ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <?php echo getFlash("CodigoControle"); ?>
        </div>
    </div>
    <div class="form-group col-md">
            <label for="CodigoMetodo" class="form-label fs-5 mt-4">Métodos</label>
            <div class="form-group" id="metodos">

            </div>
            <?php echo getFlash("CodigoMetodo"); ?>
    </div>
    <div class="p-3 form-group mt-4 d-flex justify-content-start align-items-start">
        <button type="submit" class="btn btn-primary">Registrar</button>
    </div>
    </form>

</div>

</div>

<script>
    const CodigoControle = document.querySelector("#CodigoControle");
const metodosContainer = document.querySelector("#metodos");
    CodigoControle.addEventListener("change", (e)=>{
        metodosContainer.innerHTML = '';
        xmlHttpGet("usuariospermissoes/findBy", ()=>{
            beforeSend(()=>{
                console.log("aguarde");
            })
            success(()=>{
                let response = JSON.parse(xhttp.response);
                console.log(response);
                    if(response.CodigoResposta == 1){                
                    response.Metodos.forEach(metodo => {
                        let div = createElement('div');
                        div.setAttribute("class", "form-group d-flex justify-content-start align-items-center");
                        let inputCheckbox = createElement("input");
                        let label = createElement("label");
                        label.setAttribute("for", metodo.NomeMetodo);
                        label.setAttribute("class", "mx-4")
                        label.innerText = metodo.NomeMetodo;
                        div.appendChild(label);
                        inputCheckbox.setAttribute("type", "checkbox");
                        inputCheckbox.setAttribute("class", "form-checkbox")
                        inputCheckbox.setAttribute("id", metodo.NomeMetodo)
                        inputCheckbox.setAttribute("name", "CodigoMetodo[]");
                        inputCheckbox.setAttribute("value", metodo.CodigoMetodo);
                        div.appendChild(inputCheckbox);
                        metodosContainer.append(div);
                    })
                }else{
                    metodosContainer.innerHTML = `
                    <span class="alert d-flex justify-content-between align-items-center alert-${response.typeMessage}"> 
                        ${response.message}
                        <span style="cursor:pointer" class="btn-close"></span>
                    </span>
                    `
                let btnClosesClientes = document.getElementsByClassName("btn-close");

                    Array.from(btnClosesClientes).forEach(btnCloseCliente => {
                    btnCloseCliente.addEventListener("click", (e)=>{
                        e.target.parentNode.remove();
                    })
                    })
                }
            })
        }, "?CodigoControle="+CodigoControle.value);
    })


    function createElement(nameElement){
        const element = document.createElement(nameElement);
        return element;
    }
</script>

<!-- <script>
const formCadUsuarios = document.querySelector("#form-cad-usuarios") ? document.querySelector("#form-cad-usuarios"):"";
const cadastroMensagemUsuario = document.querySelector("#cadastro-mensagem-usuario") ? document.querySelector("#cadastro-mensagem-usuario"):"";

if(formCadUsuarios){
    formCadUsuarios.addEventListener("submit", (e)=>{
        e.preventDefault();

        let dataProduto = new FormData(formCadUsuarios);

        xmlHttpPost("produtos/insert", ()=>{
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
 -->
