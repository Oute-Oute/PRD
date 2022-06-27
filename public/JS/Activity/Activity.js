
//function permettant l'ouverture de la modal d'ajout d'un parcours
function showNewModalForm(){
    $('#new-activity-modal').modal("show");
}

function showEditModalForm(id, name, duration) {
    let iid = document.getElementById('activityid').value = id
    console.log(iid)
    document.getElementById('name').value = name
    document.getElementById('duration').value = duration
    $('#edit-activity-modal').modal("show");
}

function hideEditModalForm() {
    $('#edit-activity-modal').modal("hide");
}