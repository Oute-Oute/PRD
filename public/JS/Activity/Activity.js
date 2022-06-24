function showNewModalForm() {
    containerModal = document.getElementById('container-modal')
    containerModal.style.display = "flex";
    console.log("container flex")

    formAddActivity = document.getElementById('form-add-activity');
    formAddActivity.style.display = "flex";
    console.log("act flex")

    formAddResource = document.getElementById('form-add-resource');
    formAddResource.style.display = "none";
    console.log("res none")

    formAddResourcetype = document.getElementById('form-add-resourcetype');
    formAddResourcetype.style.display = "none";
    console.log("restype none")
}

function hideNewModalForm() {
    containerModal = document.getElementById('container-modal')
    containerModal.style.display = "none";
    console.log("container none")

    formAddActivity = document.getElementById('form-add-activity');
    formAddActivity.style.display = "none";
    console.log("act none")

    formAddResource = document.getElementById('form-add-resource');
    formAddResource.style.display = "none";
    console.log("res none")

    formAddResourcetype = document.getElementById('form-add-resourcetype');
    formAddResourcetype.style.display = "none";
    console.log("restype none")
}



// #container-modal 
// .modal-form             #form-add-activity