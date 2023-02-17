
<div class="container mt-4">
    <div id="cadastro-mensagem-cargo" class="mensagem">
        <?php echo getFlash("success"); ?>
        <?php echo getFlash("fail"); ?>
    </div>
    <h1>Cadastro de cargos</h1>

    <div class="card mt-3">
        <form id="form-cadastro-cargos" method="POST">
            <div class="form-group d-grid">
                <h2 class="text fs-5">Informações do cargo</h2>
                <div class="row">
                    <div class="form-group col-md">
                        <label for="NomeCargo" class="form-label">Nome do cargo</label>
                        <input id="NomeCargo" value="<?php echo isset($cargo) ? $cargo->NomeCargo:"" ?>" type="text" name="NomeCargo" class="form-control">
                        <input value="<?php echo isset($_GET["CodigoCargo"]) ? strip_tags($_GET["CodigoCargo"]):"" ?>" type="hidden" name="CodigoCargo" class="form-control">
                    </div>
                    <div class="form-group col-md">
                        <label for="SalarioCargo" class="form-label">Salário do cargo</label>
                        <input id="SalarioCargo" type="text" value="<?php echo isset($cargo) ? $cargo->SalarioCargo:"" ?>" name="SalarioCargo" class="form-control">
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="form-group col-md">
                        <label for="TemPromocao" class="form-label">Tem promoção?</label>
                        <input data-checked="<?php echo isset($cargo) && $cargo->TemPromocao == 1 ? "true":"false" ?>" value="<?php echo isset($cargo) && $cargo->TemPromocao == 1 ? 1:0 ?>" id="TemPromocao" <?php echo isset($cargo) && $cargo->TemPromocao == 1 ? "checked":"" ?>  type="checkbox" class="form-check">Sim
                        <input type="hidden" name="TemPromocao" value="<?php echo isset($cargo) && $cargo->TemPromocao == 1 ? 1:0 ?>" class="form-control">
                    </div>
                    <div class="form-group col-md">
                        <label for="TemComissao" class="form-label">Tem comissão?</label>
                        <input data-checked="<?php echo isset($cargo) && $cargo->TemComissao == 1 ? "true":"false" ?>" value="<?php echo isset($cargo) && $cargo->TemComissao == 1 ? 1:0 ?>" id="TemComissao" <?php echo isset($cargo) && $cargo->TemComissao == 1 ? "checked":"" ?>  type="checkbox" class="form-check">Sim
                        <input type="hidden" name="TemComissao"  value="<?php echo isset($cargo) && $cargo->TemComissao == 1 ? 1:0 ?>" class="form-control">
                    </div>
                    <div class="form-group col-md">
                        <label for="ValorComissao" class="form-label">Valor da comissão</label>
                        <input id="ValorComissao" value="<?php echo isset($cargo) && $cargo->ValorComissao ? $cargo->ValorComissao:0 ?>" type="text" name="ValorComissao" class="form-control">
                    </div>
                </div>
            </div>
            <button class="btn btn-primary mt-4" type="submit">Registrar</button>
        </form>
    </div>

    <div class="mt-4">
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>Código do cargo</th>
                    <th>Nome do cargo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="cargos-tbody">
               
            </tbody>
        </table>
    </div>

</div>

<script>
const formCadastrocargos = document.querySelector("#form-cadastro-cargos") ? document.querySelector("#form-cadastro-cargos"):"";
const cadastroMensagemcargo = document.querySelector("#cadastro-mensagem-cargo") ? document.querySelector("#cadastro-mensagem-cargo"):"";
let allCheckboxes = Array.from(document.querySelectorAll("input[type='checkbox']"));
let atributoChecked = [];

allCheckboxes.forEach(checkboxe => {
    
    atributoChecked.push({
        situacao: checkboxe.dataset
    })
    
    checkboxe.addEventListener("click", (e)=>{
        let targetClick = e.target.dataset.checked.toLowerCase();
        let identity = e.target.id;
        let actualInput = document.querySelector(`input[name='${identity}'`)
        if(targetClick == "true"){
            e.target.removeAttribute("checked");
            e.target.dataset.checked = "false";
            actualInput.value = 0;
        }else{
            e.target.setAttribute("checked", "checked");
            e.target.dataset.checked = "true";
            actualInput.value = 1;
        }
    })
})

if(formCadastrocargos){
    formCadastrocargos.addEventListener("submit", (e)=>{
        e.preventDefault();
        let datacargo = new FormData(formCadastrocargos);
        xmlHttpPost("cargos/<?php echo isset($_GET["CodigoCargo"]) ? 'update':'insert' ?>", ()=>{
            beforeSend(()=>{
                cadastroMensagemcargo.innerHTML = `
                    <span class="alert d-flex justify-content-between align-items-center alert-primary"> 
                        Aguarde..
                        <span style="cursor:pointer" class="btn-close"></span>
                    </span>
                `
            })
            success(()=>{
                let response = JSON.parse(xhttp.response);
   
                let btnClosescargos = document.getElementsByClassName("btn-close");

                if(response){

                    cadastroMensagemcargo.innerHTML = `
                    <span class="alert d-flex justify-content-between align-items-center alert-${response.typeMessage}"> 
                    ${response.message}
                    <span style="cursor:pointer" class="btn-close"></span>
                    </span>
                    `                  
                }
                
                Array.from(btnClosescargos).forEach(btnClosecargo => {
                    btnClosecargo.addEventListener("click", (e)=>{
                        e.target.parentNode.remove();
                    })
                })
                fetchCargos();
            })
        }, datacargo)

    })
}

window.onload = function(){
    fetchCargos();
}

function fetchCargos(){
    fetch("cargos/allCargos").then(response=>{
        return response.json()
    }).then(response =>{
        if(response){
            createTable(response);
        }
    })
}

function createTable(cargos){
    let tbodyCargos = document.querySelector("#cargos-tbody")
    tbodyCargos.innerHTML = '';
    cargos.forEach(cargo =>{
    return tbodyCargos.innerHTML += `
        <tr>
            <td>${cargo.CodigoCargo}</td>
            <td>${cargo.NomeCargo}</td>
            <td class="d-flex">
            <form class="p-1" action="<?php echo URL_BASE ?>cargos" method="GET">
                <input type="hidden" value="${cargo.CodigoCargo}" name="CodigoCargo">
                <button class="btn btn-primary">Editar</button>
            </form>
            <form class="p-1" action="<?php echo URL_BASE ?>cargos/delete" method="GET">
                <input type="hidden" value="${cargo.CodigoCargo}" name="CodigoCargo">
                <button class="btn btn-danger">Apagar</button>
            </form>
            </td>
        </tr>
    `
    })
}
</script>