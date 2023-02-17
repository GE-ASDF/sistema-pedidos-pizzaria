let atualizador = Array.from(document.querySelectorAll(".atualizador"));
let mensagem = document.querySelector(".mensagem");
window.onload = function(){ 

    atualizador.forEach(formulario=>{
        formulario.addEventListener("submit", function(e){
            e.preventDefault();
            let curso = formulario.querySelector("input").value;
            xmlHttpGet("listarcursos/ativo", function(){
                beforeSend(function(){
                    mensagem.innerHTML = `<span class="alert alert-danger">Carregando</span> `;
                });
                success(function(){
                    let response = xhttp.responseText;
                    if(response == 1){
                        mensagem.innerHTML = `<span class="alert alert-success">Atualizado com sucesso.</span> `;
                        e.target.querySelector("i").innerHTML == "check" ? e.target.querySelector("i").innerHTML
                         = "check_box_outline_blank": e.target.querySelector("i").innerHTML = "check";
                        setTimeout(() => {
                            mensagem.innerHTML = ''
                        }, 1500);
                    }else{
                        mensagem.innerHTML = `<span class="alert alert-danger">Tente novamente</span> `;
                        setTimeout(() => {
                            mensagem.innerHTML = ''
                        }, 1500);
                    }

                })
            }, "?idcurso="+curso)
        })
    })
  
}