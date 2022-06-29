function showNewModalForm() {
    containerModal = document.getElementById('container-modal')
    containerModal.style.display = "flex";
    
    formAddActivity = document.getElementById('form-add-activity');
    formAddActivity.style.display = "none";

    formAddResource = document.getElementById('form-add-resource');
    formAddResource.style.display = "flex";

    formAddResourcetype = document.getElementById('form-add-resourcetype');
    formAddResourcetype.style.display = "none";
}

function hideNewModalForm() {
    containerModal = document.getElementById('container-modal')
    containerModal.style.display = "none";

    formAddActivity = document.getElementById('form-add-activity');
    formAddActivity.style.display = "none";

    formAddResource = document.getElementById('form-add-resource');
    formAddResource.style.display = "none";

    formAddResourcetype = document.getElementById('form-add-resourcetype');
    formAddResourcetype.style.display = "none";
}

function showInfosModalHuman(resourceId, resourceName, resourceType) {
    document.getElementById('human-resource-id').innerText = resourceId
    document.getElementById('human-resource-name').innerText = resourceName
    document.getElementById('human-resource-available').innerText = resourceType
    $('#infos-human-resource-modal').modal("show");
}

function showInfosModalMaterial(resourceId, resourceName) {
    document.getElementById('material-resource-id').innerText = resourceId
    document.getElementById('material-resource-name').innerText = resourceName
    $('#infos-material-resource-modal').modal("show");
}

function showInfosModalHumanCateg(humanResourceCategId, humaneResourceCategName) {
    document.getElementById('human-resource-category-id').innerText = humanResourceCategId
    document.getElementById('human-resource-category-name').innerText = humaneResourceCategName
    $('#infos-human-resource-category-modal').modal("show");
}

function showInfosModalMaterialCateg(materialResourceCategId, materialResourceCategName) {

    document.getElementById('material-resource-category-id').innerText = materialResourceCategId
    document.getElementById('material-resource-category-name').innerText = materialResourceCategName
    $('#infos-material-resource-category-modal').modal("show");
}

function showNewHumanModalForm(){
    $('#new-human-resource-modal').modal("show");
}

function showNewMaterialModalForm(){
    $('#new-material-resource-modal').modal("show");
}

function showNewHumanCategModalForm() {
    $('#new-human-resource-category-modal').modal("show");
}

function showNewMaterialCategModalForm() {
    $('#new-material-resource-category-modal').modal("show");
}

function handleAddHumanCategory() {

    var select = document.createElement("SELECT");
    select.setAttribute("id", "mySelect");
    let categoriesContainer = document.getElementById('categories-container')
    categoriesContainer.appendChild(select);

    var items = ["Foo","Bar","Zoo"];
    for(var i = 0;i<items.length;i++) {
      var item = items[i];
      var newOption = document.createElement("option");
      newOption.setAttribute("value", item);
      var textNode = document.createTextNode(item);
      newOption.appendChild(textNode);
      select.appendChild(newOption);
    }
}
// #container-modal 
// .modal-form             #form-add-activity