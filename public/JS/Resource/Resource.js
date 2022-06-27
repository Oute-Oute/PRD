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

function showNewModalFormInfos(resourceId, resourceName, resourceType) {
    document.getElementById('resource-id').innerText = resourceId
    document.getElementById('resource-name').innerText = resourceName
    document.getElementById('resource-type').innerText = resourceType
    $('#infos-resource-modal').modal("show");
}

// #container-modal 
// .modal-form             #form-add-activity