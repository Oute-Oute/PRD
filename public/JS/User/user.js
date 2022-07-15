function addUser() {
  $('#add-user-modal').modal("show");
}

function editUser(idEdit, usernameEdit) {
  document.getElementById('iduserEdit').value = idEdit;
  document.getElementById('usernameEdit').value = usernameEdit;

  $('#edit-user-modal').modal("show");

}

//Fonction empechant la suppresion ou l'edition de son propre compte administrateur  
document.addEventListener("DOMContentLoaded", () => {
  actualUser = document.getElementById('OwnUsername').innerHTML;
  actualUser = actualUser.replace(' ', '') //La fonction innerHtml rajoute un espace, on le supprime
  document.getElementById('buttonEdit' + actualUser).disabled = true
  document.getElementById('buttonErase' + actualUser).disabled = true
})


function showPatient(id) {
  document.getElementById('iduser').value = id;
  document.getElementById('username').value = username;
  document.getElementById('role').value = role;
  $('#show-user-modal').modal("show");
}

function usernameEdit() {

  let listeUser = JSON.parse(document.getElementById("userList").value);
  let usernamerequest = document.getElementById('usernameEdit').value;
  let id = document.getElementById('iduserEdit').value;
  console.log(usernamerequest)
  let dispo = true
  for (let i = 0; i < listeUser.length; i++) {
    if (usernamerequest == listeUser[i].username && !(id == listeUser[i].id)) {
      dispo = false;
      break;
    }
  }
  if (!dispo) {
    document.getElementById('buttonConfirmEdit').disabled = true;
    document.getElementById('ErrorUserEdit').style.visibility = 'visible';
  }
  else {
    document.getElementById('buttonConfirmEdit').disabled = false;
    document.getElementById('ErrorUserEdit').style.visibility = 'hidden';
  }

}
function usernameNew() {

  var listeUser = JSON.parse(document.getElementById("userList").value);
  let usernamerequest = document.getElementById('usernameAdd').value;
  let dispo = true
  for (let i = 0; i < listeUser.length; i++) {
    if (usernamerequest == listeUser[i].username) {
      dispo = false;
      break;
    }
  }
  if (!dispo) {
    document.getElementById('buttonConfirmAdd').disabled = true;
    document.getElementById('ErrorUser').style.visibility = 'visible';
  }
  else {
    document.getElementById('buttonConfirmAdd').disabled = false;
    document.getElementById('ErrorUser').style.visibility = 'hidden';
  }

}

function hideNewModalForm() {
  $('#add-user-modal').modal("hide");
}

function hideEditModalForm() {
  $('#edit-user-modal').modal("hide");
}


function filterUser(idInput) {
  var trs = document.querySelectorAll('#tableUser tr:not(.headerUser)');
  var filter = document.querySelector('#' + idInput).value;
  for (let i = 0; i < trs.length; i++) {
    var regex = new RegExp(filter, 'i');
    var username = trs[i].cells[1].outerText;
    if (regex.test(username) == false) {
      trs[i].style.display = 'none';
    }
    else {
      trs[i].style.display = '';
    }
  }
}

