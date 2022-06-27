function showNewModalForm() {
    containerModal = document.getElementById('container-modal')
    containerModal.style.display = "flex";
    
    formAddActivity = document.getElementById('form-add-activity');
    formAddActivity.style.display = "none";

    formAddResource = document.getElementById('form-add-resource');
    formAddResource.style.display = "none";

    formAddResourcetype = document.getElementById("form-add-resourcetype");
    formAddResourcetype.style.display = "flex";

    console.log('hello')
}

function hideNewModalForm() {
    containerModal = document.getElementById('container-modal')
    containerModal.style.display = "none";

    formAddActivity = document.getElementById('form-add-activity');
    formAddActivity.style.display = "none";

    formAddActivity = document.getElementById('form-add-resource');
    formAddActivity.style.display = "none";

    formAddResourcetype = document.getElementById('form-add-resourcetype');
    formAddResourcetype.style.display = "none";
}

function showNewModalFormInfos(resourceTypeId, resourceTypeCategory, resourceTypeType) {
    document.getElementById('resource-type-id').innerText = resourceTypeId
    document.getElementById('resource-type-category').innerText = resourceTypeCategory
    document.getElementById('resource-type-type').innerText = resourceTypeType
    $('#infos-resource-type-modal').modal("show");
}

// #container-modal 
// .modal-form             #form-add-activity