
var autocompleteArray = [];
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

function showInfosPatient(idPatient, lastname, firstname) {
    document.getElementById('patient').innerHTML = lastname + ' ' + firstname;
   
    var tableBody = document.getElementById('tbodyShow');
    tableBody.innerHTML = ''; // On supprime ce qui a précédemment été écrit dans la modale

    $.ajax({
        type : 'POST',
        url  : '/ajaxPatient',
        data : {idPatient: idPatient},
        dataType : "json",
        success : function(data){        
            tableAppointment(tableBody, data);
        },
        error: function(data){
            console.log("error");
        }
        });
    
    $('#infos-patient-modal').modal("show");
}

function tableAppointment(tableBody, data,){
    if(data.length <= 0){
        var tr = document.createElement('TR');
        tableBody.appendChild(tr);
        var td = document.createElement('TD');
        td.setAttribute('colspan', 5);
        td.append("Pas de parcours prévus pour ce patient dans les prochains jours.");
        tr.appendChild(td);
        console.log(tableBody)
    }
    else{
        for(i = 0; i < data.length; i++){
            var tr = document.createElement('TR');
            tableBody.appendChild(tr);
            var td1 = document.createElement('TD');
            var td2 = document.createElement('TD');
            td1.append(data[i]['pathwayname']);
            td2.append(data[i]['date']);
            tr.appendChild(td1);tr.appendChild(td2);
        }
    }
}

function filterPatient(selected=null){
    var trs = document.querySelectorAll('#tablePatient tr:not(.headerPatient)');
console.log(selected)
    for(let i=0; i<trs.length; i++){
            trs[i].style.display='none';
    }
    table=document.getElementById('patientTable');
    var tr=document.createElement('tr');
    table.appendChild(tr);
    var id=document.createElement('td');
    id.append(selected.id);
    tr.appendChild(id);
    var lastname=document.createElement('td');
    lastname.append(selected.lastname);
    tr.appendChild(lastname);
    var firstname=document.createElement('td');
    firstname.append(selected.firstname);
    tr.appendChild(firstname);
    var buttons=document.createElement('td');
    var infos=document.createElement('button');
    infos.setAttribute('class','btn-infos btn-secondary');
    infos.setAttribute('onclick','showInfosPatient('+selected.id+',"'+selected.lastname+'","'+selected.firstname+'")');
    infos.append('Informations');
    var edit=document.createElement('button');
    edit.setAttribute('class','btn-edit btn-secondary');
    edit.setAttribute('onclick','editPatient('+selected.id+',"'+selected.lastname+'","'+selected.firstname+'")');
    edit.append('Editer');
    var form=document.createElement('form');
    form.setAttribute('action','/patient/'+selected.id+"/delete");
    form.setAttribute('style','display:inline');
    form.setAttribute('method','POST');
    form.setAttribute('id','formDelete'+selected.id);
    form.setAttribute('onsubmit','return confirm("Voulez-vous vraiment supprimer ce patient ?")');
    var deleteButton=document.createElement('button');
    deleteButton.setAttribute('class','btn-delete btn-secondary');
    deleteButton.append('Supprimer');
    deleteButton.setAttribute('type','submit');
    buttons.appendChild(infos);
    buttons.appendChild(edit);
    form.appendChild(deleteButton);
    buttons.appendChild(form);
    tr.appendChild(buttons);
  }

  function displayAll() {
    var trs = document.querySelectorAll('#tablePatient tr:not(.headerPatient)');
    var input = document.getElementById('autocompleteInputLastname');
    console.log(input.value)
    if(input.value == ''){
    for(let i=0; i<trs.length; i++){
        if(trs[i].style.display == 'none'){
            trs[i].style.display='table-row';
        }
        else{
            trs[i].remove()
        }
    }
}
}
 

function hideNewModalForm() {
    $('#add-patient-modal').modal("hide");
}
  
function hideEditModalForm() {
    $('#edit-patient-modal').modal("hide");
}

