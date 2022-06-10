const tagContainer = document.querySelector('.tag-container');
const input = document.querySelector('.tag-container input');
const buttonTag = document.querySelector('#button_input');
let i = 0;
let tags = [];

function createTag(label) {
    const div = document.createElement('div');
    div.setAttribute('class', 'tag');
    const span = document.createElement('span');
    span.innerHTML = label;
    const inputHide = document.createElement("input");
    inputHide.value = label;
    inputHide.name = "ingredient_prop_"+i;
    inputHide.type = "hidden";
    const closeBtn = document.createElement('i');
    closeBtn.setAttribute('class', 'material-icons');
    closeBtn.setAttribute('data-item', label);
    closeBtn.innerHTML = 'close'; 

    div.appendChild(span);
    div.appendChild(closeBtn);
    div.appendChild(inputHide);
    return div;
}

function reset(){
    document.querySelectorAll('.tag').forEach(function(tag) {
        tag.parentElement.removeChild(tag);
    })
}

function addTags() {
    reset();
    tags.slice().reverse().forEach(function(tag){
        const input = createTag(tag);
        tagContainer.prepend(input);
    })
}

buttonTag.addEventListener('click', function(e){
        i++
        tags.push(input.value);
        addTags();
        input.value = "";
})

document.addEventListener('click', function(e){
    if(e.target.tagName == 'I'){
        const value = e.target.getAttribute('data-item');
        const index = tags.indexOf(value);
        tags = [...tags.slice(0, index), ...tags.slice(index + 1)];
        addTags();
    }

})