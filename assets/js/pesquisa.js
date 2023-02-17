let TXTBUSCA = document.querySelector("#txtBusca");

if(TXTBUSCA){
    
    const tbody = document.querySelector("#tbody");
    TXTBUSCA.addEventListener("keyup", function(){
        
        let filtro = TXTBUSCA.value.toLowerCase().trim();
    // let tr = tbody.getElementsByTagName("tr")
    let tr = tbody.getElementsByClassName("linha")
    
    for(let posicao in tr){
        
        if(true === isNaN(posicao)){
            continue;
        }
        let value = tr[posicao].innerHTML.toLowerCase().trim();
        if(true === value.includes(filtro)){
            tr[posicao].style.display = '';
        }else{
            tr[posicao].style.display = "none"
        }
    }

})

}