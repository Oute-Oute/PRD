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

function showInfosModalMaterial(resourceId, resourceName, resourceType) {
    document.getElementById('material-resource-id').innerText = resourceId
    document.getElementById('material-resource-name').innerText = resourceName
    document.getElementById('material-resource-available').innerText = resourceType
    $('#infos-material-resource-modal').modal("show");
}

function showNewHumanModalForm(){
    $('#new-human-resource-modal').modal("show");
}

function showNewMaterialModalForm(){
    $('#new-material-resource-modal').modal("show");
}
// #container-modal 
// .modal-form             #form-add-activity