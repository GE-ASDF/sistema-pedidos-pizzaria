
<div class="container mt-5">
<div id="atualizar-mensagem-produto" class="atualizar-mensagem-produto d-flex justify-content-center align-items-center">
    <?php echo getFlash("success"); ?>    
    <?php echo getFlash("fail"); ?>    
</div>
<div class="card">
    <div class="header">
        <h1 class="title fs-1"><?php echo $title ?></h1>
    </div>
</div>

<div class="card mt-4">
    
    <form id="atualizar-form-produto" method="POST" action="<?php echo URL_BASE ?>produtos/update" class="d-grid">

    <div class="row">
        <div class="form-group col-md">
            <label for="NomeProduto" class="form-label fs-5">Nome do produto</label>
            <input autofocus type="text" id="NomeProduto" value="<?php echo isset($produto) ? $produto->NomeProduto:"" ?>" name="NomeProduto" class="form-control">
            <input type="hidden" id="CodigoProduto" value="<?php echo isset($produto) ? $produto->CodigoProduto:"" ?>" name="CodigoProduto" class="form-control">
            <?php echo getFlash("NomeProduto"); ?>
        </div>
        <div class="form-group col-md">
            <label for="PrecoProduto" class="form-label fs-5">Preço do produto</label>
            <input onchange="this.value = this.value.replace(/,/g, '.')" value="<?php echo isset($produto) ? number_format($produto->PrecoProduto, 2, ",","."):"" ?>" type="text" id="PrecoProduto" name="PrecoProduto" class="form-control">
            <?php echo getFlash("PrecoProduto"); ?>
        </div>
        <div class="form-group col-md">
            <label for="PesoProduto" class="form-label fs-5">Peso do produto(não é obrigatório):</label>
            <input onchange="this.value = this.value.replace(/,/g, '.')" value="<?php echo isset($produto) ? number_format($produto->PesoProduto, 2, ",","."):"" ?>" type="text" id="PesoProduto" name="PesoProduto" class="form-control">
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
            <label for="CodigoFornecedor" class="form-label fs-5 mt-4">Fornecedor </label>
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
            <input <?php echo $produto->TemEstoque == 1 ? "checked":"" ?> value="1" type="radio" id="TemEstoque" name="TemEstoque" class="form-radio"> Sim
            <input <?php echo $produto->TemEstoque != 1 ? "checked":"" ?> value="0" type="radio" id="TemEstoque" name="TemEstoque" class="form-radio"> Não
            <?php echo getFlash("TemEstoque"); ?>
    </div>
    <div style="display:<?php echo $produto->TemEstoque == 1 ? "block":"none" ?>" class="form-group mt-4 col-md">
        <label for="Quantidade" class="form-label fs-5">Quantidade em estoque</label>
        <input value="<?php echo $estoque->Quantidade ?>" type="text" id="Quantidade" name="Quantidade" class="form-control">
        <?php echo getFlash("TemEstoque"); ?>
    </div>
    <div class="p-3 form-group mt-4 d-flex justify-content-start align-items-start">
        <button type="submit" class="btn btn-primary">Registrar</button>
    </div>
    </form>

</div>

</div>

<script>
const formAtualizarProduto = document.querySelector("#atualizar-form-produto") ? document.querySelector("#atualizar-form-produto"):"";
const atualizarMensagemProduto = document.querySelector("#atualizar-mensagem-produto") ? document.querySelector("#atualizar-mensagem-produto"):"";

if(formAtualizarProduto){
    formAtualizarProduto.addEventListener("submit", (e)=>{
        e.preventDefault();

        let dataProduto = new FormData(formAtualizarProduto);

        xmlHttpPost("update", ()=>{
            beforeSend(()=>{
                atualizarMensagemProduto.innerHTML = `
                    <span class="alert d-flex justify-content-between align-items-center alert-primary"> 
                        Aguarde..
                        <span style="cursor:pointer" class="btn-close"></span>
                    </span>
                `
            })
            success(()=>{
                let response = JSON.parse(xhttp.response);
   
                let btnClosesfuncionarios = document.getElementsByClassName("btn-close");

                    atualizarMensagemProduto.innerHTML = `
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

