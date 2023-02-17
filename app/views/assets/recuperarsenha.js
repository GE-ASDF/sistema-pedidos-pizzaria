let formRecuperar = document.querySelector("#recuperar-senha")
let formCadastrar = document.querySelector("#cadastrar-usuario")
let mensagem = document.querySelectorAll(".mensagem")
let btnRecuperar = document.querySelector("#btnRecuperar")


window.onload = function(){

    formRecuperar.onsubmit = function(e){
        e.preventDefault();
        let recuperar = new FormData(formRecuperar);
        xmlHttpPost("login/recuperarsenha", function(){
            beforeSend(function(){
                mensagem[1].innerHTML = `<span class="alert alert-success"> Carregando </span>`
            });
            success(function(){

                let response = xhttp.responseText;
                console.log(response);
                if(response == 1){
                    mensagem[1].innerHTML = `<span class="alert alert-success"> Senha recuperada. Verifique seu e-mail </span>`
                    let closeModal = formRecuperar.querySelector(".close-modal")
                    setTimeout(() => {
                        closeModal.click();
                    }, 1500);
                }
                if(response != 1){
                    mensagem[1].innerHTML = `<span class="alert alert-danger">A senha não foi recuperada. Tente novamente. </span>`
                }
            })
        }, recuperar)
    }

    formCadastrar.onsubmit = function(e){
        e.preventDefault();
        let cadastrar = new FormData(formCadastrar);
        xmlHttpPost("login/cadastrar", function(){
            beforeSend(function(){
                mensagem[0].innerHTML = `<span class="alert alert-success"> Carregando </span>`
            });
            success(function(){

                let response = xhttp.responseText;
                
                if(response == 1){
                    mensagem[0].innerHTML = `<span class="alert alert-success"> Cadastrado com sucesso </span>`
                   let allInputs = formCadastrar.querySelectorAll("input.form-control")
                    Array.from(allInputs).forEach(input=>{
                        input.value = '';
                    })
                    let closeModal = formCadastrar.querySelector(".close-modal")
                    setTimeout(() => {
                        closeModal.click();
                    }, 1500);
                }
                if(response != 1){
                    mensagem[0].innerHTML = `<span class="alert alert-danger">Cadastro não efetuado. Tente novamente. </span>`
                }
            })
        }, cadastrar)
    }
}