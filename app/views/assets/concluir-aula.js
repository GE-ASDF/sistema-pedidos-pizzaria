let atualizador = Array.from(document.querySelectorAll(".concluir-aula"));
let mensagem = document.querySelector(".mensagem");
window.onload = function(){ 

    atualizador.forEach(formulario=>{
        formulario.addEventListener("submit", function(e){
            e.preventDefault();
            let idaula = formulario.querySelector("input[name='idaula']").value;
            let idcurso = formulario.querySelector("input[name='idcurso']").value;
            xmlHttpGet("../concluiraula", function(){
                beforeSend(function(){
                });
                success(function(){
                    let response = xhttp.responseText;
                    if(response == 1){
                        e.target.querySelector("i").innerHTML == "check" ? e.target.querySelector("i").innerHTML
                        = "check_box_outline_blank": e.target.querySelector("i").innerHTML = "check";
                        $("#progresso").load(" #progresso")
                        setTimeout(() => {
                            mensagem.innerHTML = ''
                        }, 1500);
                    }else{
                        e.target.querySelector("i").innerHTML == "check" ? e.target.querySelector("i").innerHTML
                        = "check_box_outline_blank": e.target.querySelector("i").innerHTML = "check";
                        $("#progresso").load(" #progresso")
                        setTimeout(() => {
                            mensagem.innerHTML = ''
                        }, 1500);
                    }

                })
            }, "?idaula="+idaula+"&idcurso="+idcurso)
    })
    })
}