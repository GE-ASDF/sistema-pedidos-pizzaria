
<div class="container mt-4">
<div id="cadastro-mensagem-produto" class="cadastro-mensagem-produto d-flex justify-content-center align-items-center">
    <?php echo getFlash("success"); ?>    
    <?php echo getFlash("fail"); ?>    
</div>
<div class="card">
    <div class="header">
        <h1 class="title fs-1"><?php echo $title ?></h1>
    </div>
</div>

<div class="card mt-4">
    
    <form id="form-cad-produtos" method="POST" action="<?php echo URL_BASE ?>produtos/insert" class="d-grid">

    <div class="row">
        <div class="form-group col-md">
            <label for="NomeProduto" class="form-label fs-5">Nome do produto</label>
            <input autofocus type="text" id="NomePizza" name="NomeProduto" class="form-control">
            <?php echo getFlash("NomeProduto"); ?>
        </div>
        <div class="form-group col-md">
            <label for="PrecoProduto" class="form-label fs-5">Preço do produto</label>
            <input onchange="this.value = this.value.replace(/,/g, '.')"  type="text" id="PrecoProduto" name="PrecoProduto" class="form-control">
            <?php echo getFlash("PrecoProduto"); ?>
        </div>
        <div class="form-group col-md">
            <label for="PesoProduto" class="form-label fs-5">Peso do produto(não é obrigatório):</label>
            <input onchange="this.value = this.value.replace(/,/g, '.')"  type="text" id="PesoProduto" name="PesoProduto" class="form-control">
            <?php echo getFlash("PesoProduto"); ?>
        </div>
    </div>
    <div class="row mt-4">
        <div class="form-group col-md">
            <label for="CodigoUnidadeMedida" class="form-label fs-5 mt-4">Unidade de medida </label>
            <select name="CodigoUnidadeMedida" id="" class="form-select">
                <?php if($unidades): ?>
                    <?php foreach($unidades as $unidade): ?>
                        <option value="<?php echo $unidade->CodigoUnidadeMedida ?> "><?php echo $unidade->NomeUnidadeMedida ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <?php echo getFlash("CodigoUnidadeMedida"); ?>
        </div>
        <div class="form-group col-md">
        <label for="CodigoUnidadeMedida" class="form-label fs-5 mt-4">Categoria do produto </label>
        <select name="CodigoCategoria" id="" class="form-select">
                <?php if($categorias): ?>
                    <?php foreach($categorias as $categoria): ?>
                        <option value="<?php echo $categoria->CodigoCategoria ?> "><?php echo $categoria->NomeCategoria ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <?php echo getFlash("PrecoPromocao"); ?>
        </div>
        <div class="form-group col-md">
            <label for="CodigoFornecedor" class="form-label fs-5 mt-4">Unidade de medida </label>
            <select name="CodigoFornecedor" id="" class="form-select">
                <?php if($fornecedores): ?>
                    <?php foreach($fornecedores as $fornecedor): ?>
                        <option value="<?php echo $fornecedor->CodigoFornecedor ?> "><?php echo $fornecedor->NomeFornecedor ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <?php echo getFlash("CodigoFornecedor"); ?>
        </div>
    </div>
    <div class="form-group mt-4 col-md">
            <label for="TemEstoque" class="form-label fs-5">Tem estoque?</label>
            <input value="1" type="radio" id="TemEstoque" name="TemEstoque" class="form-radio"> Sim
            <input checked value="0" type="radio" id="TemEstoque" name="TemEstoque" class="form-radio"> Não
            <?php echo getFlash("TemEstoque"); ?>
    </div>
    <div class="p-3 form-group mt-4 d-flex justify-content-start align-items-start">
        <button type="submit" class="btn btn-primary">Registrar</button>
    </div>
    </form>

</div>

</div>

<script>
const formCadProdutos = document.querySelector("#form-cad-produtos") ? document.querySelector("#form-cad-produtos"):"";
const cadastroMensagemProduto = document.querySelector("#cadastro-mensagem-produto") ? document.querySelector("#cadastro-mensagem-produto"):"";

if(formCadProdutos){
    formCadProdutos.addEventListener("submit", (e)=>{
        e.preventDefault();

        let dataProduto = new FormData(formCadProdutos);

        xmlHttpPost("produtos/insert", ()=>{
            beforeSend(()=>{
                cadastroMensagemProduto.innerHTML = `
                    <span class="alert d-flex justify-content-between align-items-center alert-primary"> 
                        Aguarde..
                        <span style="cursor:pointer" class="btn-close"></span>
                    </span>
                `
            })
            success(()=>{
                let response = JSON.parse(xhttp.response);
   
                let btnClosesfuncionarios = document.getElementsByClassName("btn-close");

                    cadastroMensagemProduto.innerHTML = `
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

