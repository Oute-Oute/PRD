var autocompleteArray = [];

function addUser() {
  document.getElementById("usernameAdd").value = "";
  $("#add-user-modal").modal("show");
}

function editUser(idEdit, usernameEdit, firstnameEdit, lastnameEdit, roleEdit) {
  document.getElementById("iduserEdit").value = idEdit;
  document.getElementById("usernameEdit").value = usernameEdit;
  document.getElementById("firstnameEdit").value = firstnameEdit;
  document.getElementById("lastnameEdit").value = lastnameEdit;
  document.getElementById("roleEdit").value = roleEdit;
  $("#edit-user-modal").modal("show");
}

//Fonction empechant la suppresion ou l'edition de son propre compte administrateur
document.addEventListener("DOMContentLoaded", () => {
  actualUser = document.getElementById("OwnUsername").value;
  actualUser = actualUser.replace(" ", ""); //La fonction innerHtml rajoute un espace, on le supprime
  document.getElementById("buttonEdit" + actualUser).disabled = true;
  document.getElementById("buttonErase" + actualUser).disabled = true;
});

function showPatient(id) {
  document.getElementById("iduser").value = id;
  document.getElementById("username").value = username;
  document.getElementById("role").value = role;
  $("#show-user-modal").modal("show");
}

function usernameEditTest() {
  let listeUser = JSON.parse(document.getElementById("userList").value);
  let usernamerequest = document.getElementById("usernameEdit").value;
  let id = document.getElementById("iduserEdit").value;
  console.log(usernamerequest);
  let dispo = true;
  for (let i = 0; i < listeUser.length; i++) {
    if (usernamerequest == listeUser[i].username && !(id == listeUser[i].id)) {
      dispo = false;
      break;
    }
  }
  if (!dispo) {
    document.getElementById("buttonConfirmEdit").disabled = true;
    document.getElementById("ErrorUserEdit").style.visibility = "visible";
  } else {
    document.getElementById("buttonConfirmEdit").disabled = false;
    document.getElementById("ErrorUserEdit").style.visibility = "hidden";
  }
}
function usernameNew() {
  var listeUser = JSON.parse(document.getElementById("userList").value);
  let usernamerequest = document.getElementById("usernameAdd").value;
  let dispo = true;
  for (let i = 0; i < listeUser.length; i++) {
    if (usernamerequest == listeUser[i].username) {
      dispo = false;
      break;
    }
  }
  if (!dispo) {
    document.getElementById("buttonConfirmAdd").disabled = true;
    document.getElementById("ErrorUser").style.visibility = "visible";
  } else {
    document.getElementById("buttonConfirmAdd").disabled = false;
    document.getElementById("ErrorUser").style.visibility = "hidden";
  }
}

function hideNewModalForm() {
  $("#add-user-modal").modal("hide");
}

function hideEditModalForm() {
  $("#edit-user-modal").modal("hide");
}

/**
 * Allows to filter patients to not display all of them
 */
function filterUser(selected = null) {
  var trs = document.querySelectorAll('#tableUser tr:not(.headerUser)');
  for (let i = 0; i < trs.length; i++) {
    trs[i].style.display = 'none';
  }
  table = document.getElementById('userTable');
  var tr = document.createElement('tr');
  table.appendChild(tr);
  var id = document.createElement('td');
  id.append(selected.id);
  tr.appendChild(id);
  var username = document.createElement('td');
  username.append(selected.username);
  tr.appendChild(username);
  var name = document.createElement('td');
  name.append(selected.lastname + " " + selected.firstname);
  tr.appendChild(name);
  var role = document.createElement('td');
  role.append(selected.role[1]);
  tr.appendChild(role);
  var buttons = document.createElement('td');
  var edit = document.createElement('button');
  edit.setAttribute('type', 'button');
  edit.setAttribute('id', 'buttonEdit' + selected.username);
  edit.setAttribute('class', 'btn-edit btn-secondary');
  edit.setAttribute('onclick', "editUser('" + selected.id + "', '" + selected.username + "', '" + selected.firstname + "', '" + selected.lastname + "' )");
  edit.append('Editer');
  var form = document.createElement('form');
  form.setAttribute('action', '/user/' + selected.id + "/delete");
  form.setAttribute('style', 'display:inline');
  form.setAttribute('method', 'POST');
  form.setAttribute('id', 'formDelete' + selected.id);
  form.setAttribute('onsubmit', 'return confirm("Voulez-vous vraiment supprimer cet utilisateur ?")');
  var deleteButton = document.createElement('button');
  deleteButton.setAttribute('class', 'btn-delete btn-secondary');
  deleteButton.append('Supprimer');
  deleteButton.setAttribute('type', 'submit');
  buttons.appendChild(edit);
  form.appendChild(deleteButton);
  buttons.appendChild(form);
  tr.appendChild(buttons);
  paginator = document.getElementById('paginator');
  paginator.style.display = 'none';
}

/**
 * Allows to display all patients without any filter
 */
function displayAll() {
  var trs = document.querySelectorAll('#tableUser tr:not(.headerUser)');
  var input = document.getElementById('autocompleteInputUserName');
  console.log(input.value)
  if (input.value == '') {
    for (let i = 0; i < trs.length; i++) {
      console.log(trs[i].className)
      if (trs[i].style.display == 'none') {
        trs[i].style.display = 'table-row';
      }
      else if (trs[i].className != 'original') {
        trs[i].remove()
      }
    }
    paginator = document.getElementById('paginator');
    paginator.style.display = '';
  }
}

