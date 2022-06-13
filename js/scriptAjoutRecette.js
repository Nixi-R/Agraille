let XHR = new XMLHttpRequest();
XMLHttpRequest.open(post,'mysql:host=localhost;dbname=agrailledb;charset=utf8', false, "root","");

    let button = document.getElementById("boutton_ingredient");
    let section = document.getElementById("ingredient_container");
    let i = 1;
    console.log("p")

    button.addEventListener('click',function(event){
        i++
            let input = document.createElement('input');
            let select_ingredient = document.createElement('select');
            let select_mesure = document.createElement('select');
            let option_mesure = document.createElement('option');
 
            select_ingredient.name = "ingredient_" + i;
            select_ingredient.textContent = `<?php while($i = $ingredient->fetch()){echo "<option>".$i["ingredient"]."</option>";}?>`;
            section.appendChild(select_ingredient);
            console.log(select_ingredient);

    })