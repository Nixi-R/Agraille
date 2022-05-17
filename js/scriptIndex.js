//Menu burger
const hamburger = document.querySelector(".hamburger");
const navMenu = document.querySelector(".nav-menu");

hamburger.addEventListener("click", () =>{
    hamburger.classList.toggle("active");
    navMenu.classList.toggle("active");

})

document.querySelectorAll(".nav-link").forEach(n => n.addEventListener("click", () => {
    hamburger.classList.remove("active");
    navMenu.classList.remove("active");

}))

//profil
const arrow = document.querySelector(".container_arrow");
const profilMenu = document.querySelector(".profil_menu");

arrow.addEventListener("click", () => {
    profilMenu.classList.toggle("active");
})

