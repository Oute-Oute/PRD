//function permettant d'ouvrir la modale d'ajout d'un patient
function addPatient(){
    $('#add-patient-modal').modal("show");
}

//function permettant d'ouvrir la modale d'édition d'un patient
function editPatient(id, lastname, firstname) {
    //on initialise les informations affichées avec les données du patient modifié
    document.getElementById('idpatient').value = id;
    document.getElementById('lastname').value = lastname;
    document.getElementById('firstname').value = firstname;

    //on affiche la modale
    $('#edit-patient-modal').modal("show");
}

function showInfosPatient(lastname, firstname, appointmentArray) {
    console.log(appointmentArray);
    document.getElementById('patient').innerHTML = lastname + ' ' + firstname;
    length = appointmentArray.split('{').length;
    console.log(length);
    appointmentArray = appointmentArray.split('"');
   
    var tableBody = document.getElementById('tbodyShow');
    tableBody.innerHTML = ''; // On supprime ce qui a précédemment été écrit dans la modale

    if(length <= 1){
        var tr = document.createElement('TR');
            tableBody.appendChild(tr);
            var td = document.createElement('TD');
            td.setAttribute('colspan', 5);
            td.append("Pas de parcours prévus");
            tr.appendChild(td);
    }
    else{
        for (var i = 0; i < length-1; i++) {
            var tr = document.createElement('TR');
            tableBody.appendChild(tr);
            var td1 = document.createElement('TD');
            var td2 = document.createElement('TD');
            td1.append(appointmentArray[3+8*i]);
            td2.append(appointmentArray[7+8*i]);
            tr.appendChild(td2);tr.appendChild(td1);
        }
    }
    
    $('#infos-patient-modal').modal("show");
}

function filterAppointment(idInput){
    var trs = document.querySelectorAll('#tablePatient tr:not(.headerPatient)');
    var filter = document.querySelector('#'+idInput).value
    var regex = new RegExp(filter, 'i')
    const isFoundInTds = td => regex.test(td.innerHTML)
    const isFound = childrenArr => childrenArr.some(isFoundInTds)
    const setTrStyleDisplay = ({ style, children }) => {
      style.display = isFound([
        ...children // <-- All columns
      ]) ? '' : 'none' 
    }
    trs.forEach(setTrStyleDisplay)
  }

function hideNewModalForm() {
    $('#add-patient-modal').modal("hide");
}
  
function hideEditModalForm() {
    $('#edit-patient-modal').modal("hide");
}

