var autocompleteArray = [];


/**
 * Allows to display a modal to add a user
 */
function addUser() {
  document.getElementById("usernameAdd").value = "";
  usernameNew()
  $("#add-user-modal").modal("show");
}

/**
 * Allows to display a modal to edit a user
 */
function editUser(idEdit, usernameEdit, firstnameEdit, lastnameEdit, roleEdit) {
  document.getElementById("iduserEdit").value = idEdit;
  document.getElementById("usernameEdit").value = usernameEdit;
  document.getElementById("firstnameEdit").value = firstnameEdit;
  document.getElementById("lastnameEdit").value = lastnameEdit;
  document.getElementById("passwordEdit").value = null;
  document.getElementById("roleEdit").value = roleEdit;
  usernameEditTest()
  $("#edit-user-modal").modal("show");
}

/**
 * Allows to prevent the deletion or edition of his own admin account
 */
document.addEventListener("DOMContentLoaded", () => {
  actualUser = document.getElementById("OwnUsername").value;
  actualUser = actualUser.replace(" ", ""); //La fonction innerHtml rajoute un espace, on le supprime
  document.getElementById("buttonEdit" + actualUser).disabled = true;
  document.getElementById("buttonErase" + actualUser).disabled = true;
});

/**
 * Allows to display a modal to add a user
 */
function showPatient(id) {
  document.getElementById("iduser").value = id;
  document.getElementById("username").value = username;
  document.getElementById("role").value = role;
  $("#show-user-modal").modal("show");
}

/**
 * Allows to edit the username of the user
 */
function usernameEditTest() {
  let listeUser = JSON.parse(document.getElementById("userList").value);
  let usernamerequest = document.getElementById("usernameEdit").value;
  let id = document.getElementById("iduserEdit").value;
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

/**
 * Allows to create a new user
 */
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

/**
 * Allows to hide the creation of user modal when a click is performed somewhere else than the window
 */
function hideNewModalForm() {
  $("#add-user-modal").modal("hide");
}

/**
 * Allows to hide the edition of user modal when a click is performed somewhere else than the window
 */
function hideEditModalForm() {
  $("#edit-user-modal").modal("hide");
}

/**
 * @brief this function display only the searched resource in the list
 * @param {*} selected - the selected resource
 */
function filterUser(selected = null) {
  var trs = document.querySelectorAll('#tableUser tr:not(.headerUser)');//get all the rows of the table
  for (let i = 0; i < trs.length; i++) {
    trs[i].style.display = 'none';//hide all the rows
  }
  table = document.getElementById('userTable');// get the table
  var tr = document.createElement('tr');//create a new row
  table.appendChild(tr);
  var id = document.createElement('td');//create the id cell
  id.append(selected.id);
  tr.appendChild(id);
  var username = document.createElement('td');//create the username cell
  username.append(selected.username);
  tr.appendChild(username);
  var name = document.createElement('td');
  name.append(selected.lastname + " " + selected.firstname);//create the name cell
  tr.appendChild(name);
  var role = document.createElement('td');//create the role cell
  role.append(selected.role[1]);
  tr.appendChild(role);
  var buttons = document.createElement('td');//create the buttons cell
  var edit = document.createElement('button');//create the edit button
  edit.setAttribute('type', 'button');
  edit.setAttribute('id', 'buttonEdit' + selected.username+selected.id);
  edit.setAttribute('class', 'btn-edit btn-secondary');
  edit.setAttribute('onclick', "editUser('" + selected.id + "', '" + selected.username + "', '" + selected.firstname + "', '" + selected.lastname + "' )");
  edit.append('Editer');
  var deleteButton = document.createElement('button');//create the delete button
  deleteButton.setAttribute('class', 'btn-delete btn-secondary');
  deleteButton.append('Supprimer');
  deleteButton.setAttribute('id', 'buttonErase' + selected.username+selected.id);
  deleteButton.setAttribute('onclick', 'showPopup('+selected.id+')');
  //add the buttons to the cell
  buttons.appendChild(edit);
  buttons.appendChild(deleteButton);
  tr.appendChild(buttons);
  actualUser = document.getElementById("OwnUsername").value;
  actualUser = actualUser.replace(" ", ""); //La fonction innerHtml rajoute un espace, on le supprime
  document.getElementById("buttonEdit" + actualUser+selected.id).disabled = true;
  document.getElementById("buttonErase" + actualUser+selected.id).disabled = true;
  paginator = document.getElementById('paginator');
  paginator.style.display = 'none'; //On cache le paginateur
}

/**
     * @brief Display all the resources of a type
     */
function displayAll() {
  var trs = document.querySelectorAll('#tableUser tr:not(.headerUser)');//get all the rows of the table
  var input = document.getElementById('autocompleteInputUserName');//get the input field
  if (input.value == '') {//if the input field is empty
    for (let i = 0; i < trs.length; i++) {
      if (trs[i].style.display == 'none') {
        trs[i].style.display = 'table-row';//display all the rows
      }
      else if (trs[i].className != 'original') {//if the row is not the original one (e.g if it is the one created with the search bar)
        trs[i].remove()//remove the row
      }
    }
    paginator = document.getElementById('paginator');
    paginator.style.display = ''; //display the paginator
  }
}

/**
 * Allows to show a popup to confirm the deletion of a user
 */
function showPopup(id){
  document.getElementById("form-user-delete").action = "/user/" + id + "/delete"
  $('#modal-popup').modal('show')
}