function btnClose(){

    let btnsClose = document.getElementsByClassName("btn-close");
    
    Array.from(btnsClose).forEach(btnsClose => {
        btnsClose.addEventListener("click", (e)=>{
            e.target.parentNode.remove();
        })
    })
    
}