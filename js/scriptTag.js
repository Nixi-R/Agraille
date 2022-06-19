const tagContainer = document.querySelector('.tag-container');
const select = document.querySelector('.tag-container select');
const buttonTag = document.querySelector('#button_input');
var n = 0;
let tags = [];
let option = ["Aucune","centilitres","litres","grammes","kilos","cuil à soupe","cuil à café"];


function createTag(label) {
    n++
    const div = document.createElement('div');
    div.setAttribute('class', 'tag');
    const span = document.createElement('span');
    span.innerHTML = label;
    const inputHide = document.createElement("input");
    inputHide.value = label;
    inputHide.name = "ingredient_"+n;
    inputHide.type = "hidden";
    const quantiteInput = document.createElement("input");
    quantiteInput.name = "quantite_"+n;
    quantiteInput.type = "number";
    quantiteInput.min = "0";
    quantiteInput.max = "100";
    const mesureSelect = document.createElement("select")
    mesureSelect.name = "mesure_"+n;
    for(let j = 0; j<option.length; j++){
        let mesureOption= document.createElement("option");
        mesureOption.value = option[j];
        mesureOption.text = option[j];
        mesureSelect.appendChild(mesureOption);
    } 
    n = n-2
    const closeBtn = document.createElement('i');
    closeBtn.setAttribute('class', 'material-icons');
    closeBtn.setAttribute('data-item', label);
    closeBtn.innerHTML = 'close'; 
    div.appendChild(mesureSelect);
    div.appendChild(quantiteInput);
    div.appendChild(span);
    div.appendChild(closeBtn);
    div.appendChild(inputHide);
    return div;
}


function reset(){
    document.querySelectorAll('.tag').forEach(function(tag, i) {
        tag.parentElement.removeChild(tag);
    })
}

function addTags() {
    reset();
    tags.slice().reverse().forEach(function(tag){
        const select = createTag(tag);
        tagContainer.prepend(select);
    })
}

buttonTag.addEventListener('click', function(e){
    tags.push(select.value);
    addTags();
    n++;
})

document.addEventListener('click', function(e){
    if(e.target.tagName == 'I'){
        const value = e.target.getAttribute('data-item');
        const index = tags.indexOf(value);
        tags = [...tags.slice(0, index), ...tags.slice(index + 1)];
        addTags();
    }
})