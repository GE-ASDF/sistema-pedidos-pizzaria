const modalClose = document.querySelector(".close-modal");
const modalOpen = document.querySelector(".open-modal");
const modal = document.querySelector(".modal");

modalOpen.addEventListener("click", function(){
    modal.style.opacity = "1";
    modal.style.top = "0px";
})

modalClose.addEventListener("click", function(){
    modal.style.opacity = "0";
    modal.style.top = "-1000px";
})