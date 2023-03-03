var SELECT_ID = 0;
var NB_CATEGORY = 0;
var hrArray = [];
var hcrArray = [];
var mrArray = [];
var mcrArray = [];

var HUMAN_RESOURCE_APPOINTMENTS = new Object()
HUMAN_RESOURCE_APPOINTMENTS.scheduledAppointments = new Array()

var MATERIAL_RESOURCE_APPOINTMENTS = new Object()
MATERIAL_RESOURCE_APPOINTMENTS.scheduledAppointments = new Array()

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('type').value == "categories") {
        if (document.getElementById('resourcetype').value == "human") {
            change_tab_human("categories");
        }
        else {
            change_tab_material("categories");
        }
    }
});

/**
  * Allows to display the modal that is used to create a new human resource
 */
function showNewHumanModalForm() {
    $('#new-human-resource-modal').modal("show");
}

/**
  * Allows to display the modal that is used to create a new material resource
 */
function showNewMaterialModalForm() {
    $('#new-material-resource-modal').modal("show");
}

/**
  * Allows to display the modal that is used to create a new category of human resource
 */
function showNewHumanCategModalForm() {
    $('#new-human-resource-category-modal').modal("show");
}


/**
  * Allows to display the modal that is used to create a new category of material resource
 */
function showNewMaterialCategModalForm() {
    $('#new-material-resource-category-modal').modal("show");
}


/**
  * Allows to display the modal that is used to edit a human resource category
 */
function showEditHumanCategModalForm(id, name) {
    document.getElementById('idcategoryedit').value = id;
    document.getElementById('categorynameedit').value = name;
    $('#edit-human-resource-category-modal').modal("show");
}


/**
  * Allows to display the modal that is used to edit material resource category
 */
function showEditMaterialCategModalForm(id, name) {
    document.getElementById('idcategoryedit').value = id;
    document.getElementById('categorynameedit').value = name;
    $('#edit-material-resource-category-modal').modal("show");
}

/**
 * Allows to check fields in the modal form to create a human resource
 */
function humanResourceVerify(type) {
    // D'abord on recupere la div qui contient toutes les activity
    let categoriesContainer
    let btnAdd
    let divWorkingHoursBegin
    let divWorkingHoursEnd
    let nbCategory
    let nbOfDay
    let pbWorkingHoursSolo = false;
    let endHigherThanBegin = false;
    var nbCateg = 0;
    if (type == "new") {
        categoriesContainer = document.getElementById('categories-container')
        btnAdd = document.getElementById('btn-none-add-human-resource')
        divWorkingHoursBegin = document.getElementById('working-hours-input-begin')
        divWorkingHoursEnd = document.getElementById('working-hours-input-end')
        nbCategory = document.getElementById('nbCategory');
        nbOfDay = 7
    }
    if (type == "auto") {
        categoriesContainer = document.getElementById('categories-container-auto')
        btnAdd = document.getElementById('btn-none-add-human-resource-auto')
        divWorkingHoursBegin = document.getElementById('working-hours-input-begin-auto')
        divWorkingHoursEnd = document.getElementById('working-hours-input-end-auto')
        nbCategory = document.getElementById('nbCategory-auto');
        nbOfDay = 1
    }
    for (let i = 0; i <= categoriesContainer.children.length - 1; i++) {
        if (categoriesContainer.children[i].children[0].checked) {
            categoriesContainer.children[i].children[0].setAttribute('name', 'id-category-' + nbCateg)
            categoriesContainer.children[i].children[0].setAttribute('id', 'id-category-' + nbCateg)
            categoriesContainer.children[i].children[1].setAttribute('id', 'lbl-category-' + nbCateg)
            nbCateg = nbCateg + 1;
        }
    }
    for (let j = 0; j < nbOfDay; j++) {
        if ((divWorkingHoursBegin.children[j].value == '' && divWorkingHoursEnd.children[j].value != '') || (divWorkingHoursBegin.children[j].value != '' && divWorkingHoursEnd.children[j].value == '')) {
            pbWorkingHoursSolo = true;
        }
        if ((divWorkingHoursBegin.children[j].value > divWorkingHoursEnd.children[j].value)) {
            endHigherThanBegin = true;
        }
    }
    nbCategory.value = nbCateg
    if (pbWorkingHoursSolo == true) {
        alert('Veuillez saisir l\'heure de début et de fin, ou aucun des deux horaires pour chaque jour de disponibilité !')
    }
    else if (endHigherThanBegin == true) {
        alert('Veuillez saisir des horaires de début antérieures à celles de fin pour chaque jour de disponibilité !')
    }
    else {
        btnAdd.click();
    }
}


/**
 * Allows to check fields in the modal form to create a material resource
 */
function materialResourceVerify() {

    // D'abord on recupere la div qui contient toutes les activity
    let categoriesContainer = document.getElementById('categories-container')
    let btnAdd = document.getElementById('btn-none-add-human-resource')
    let nbCategory = document.getElementById('nbCategory');
    var nbCateg = 0;
    // On parcours toutes nos activités 
    // On set leur 'name' et on verifie leurs contenus
    for (let i = 0; i <= categoriesContainer.children.length - 1; i++) {
        if (categoriesContainer.children[i].children[0].checked) {
            categoriesContainer.children[i].children[0].setAttribute('name', 'id-category-' + nbCateg)
            categoriesContainer.children[i].children[0].setAttribute('id', 'id-category-' + nbCateg)
            categoriesContainer.children[i].children[1].setAttribute('id', 'lbl-category-' + nbCateg)
            nbCateg = nbCateg + 1;
        }

    }
    nbCategory.value = nbCateg
    btnAdd.click();
}


/**
 * Allows to check fields in the edit modal form to create a human resource
 */
function materialResourceVerifyEdit() {

    // D'abord on recupere la div qui contient toutes les activity
    let categoriesContainer = document.getElementById('categories-container')
    let btnAdd = document.getElementById('btn-none-edit-human-resource')
    let nbCategory = document.getElementById('nbCategory');
    var nbCateg = 0;
    // On parcours toutes nos activités 
    // On set leur 'name' et on verifie leurs contenus
    for (let i = 0; i <= categoriesContainer.children.length - 1; i++) {
        if (categoriesContainer.children[i].children[0].checked) {
            categoriesContainer.children[i].children[0].setAttribute('name', 'id-category-' + nbCateg)
            categoriesContainer.children[i].children[0].setAttribute('id', 'id-category-' + nbCateg)
            categoriesContainer.children[i].children[1].setAttribute('id', 'lbl-category-' + nbCateg)
            nbCateg = nbCateg + 1;
        }

    }
    nbCategory.value = nbCateg
    btnAdd.click();
}

function hasDuplicates(array) {
    var valuesSoFar = Object.create(null);
    for (var i = 0; i < array.length; ++i) {
        var value = array[i];
        if (value in valuesSoFar) {
            return true;
        }
        valuesSoFar[value] = true;
    }
    return false;
}


/**
 * Allows to hide a create modal form. Called when you click somewhere else than the modal
 */
function hideNewModalForm() {
    $('#new-human-resource-category-modal').modal("hide");
    $('#new-human-resource-modal').modal("hide");
    $('#new-material-resource-category-modal').modal("hide");
    $('#new-material-resource-modal').modal("hide");
}

/**
 * Allows to hide an edit modal form. Called when you click somewhere else than the modal
 */
function hideEditModalForm() {

    $('#edit-human-resource-category-modal').modal("hide");
    $('#edit--human-resource-modal').modal("hide");
    $('#edit-material-resource-category-modal').modal("hide");
    $('#edit--material-resource-modal').modal("hide");
    $('#edit--unavailability-material-resource-modal').modal("hide");
    $('#edit--unavailability-human-resource-modal').modal("hide");

}

/**
 * Allows to change the selected tab in the material resource page
 */
function change_tab_material(id) {
    document.getElementById("resources").className = "notselected";
    document.getElementById("categories").className = "notselected";
    var paginator = document.getElementById('paginator');
    document.getElementById(id).className = "selected";

    let resources = document.getElementById("list-material-resources")
    let categories = document.getElementById("list-material-categories")
    if (id == 'resources') {
        categories.style.display = 'none'
        resources.style.display = 'block'
        paginator.style.display = '';
    }
    else {
        categories.style.display = 'block'
        resources.style.display = 'none'
        paginator.style.display = 'none';
    }
}

/**
 * Allows to change the selected tab in the human resource page
 */
function change_tab_human(id) {
    document.getElementById("resources").className = "notselected";
    document.getElementById("categories").className = "notselected";
    var paginator = document.getElementById('paginator');
    document.getElementById(id).className = "selected";
    let resources = document.getElementById("list-human-resources");
    let categories = document.getElementById("list-human-categories");
    if (id == 'resources') {
        categories.style.display = 'none';
        resources.style.display = 'block';
        paginator.style.display = '';
    }
    else {
        categories.style.display = 'block';
        resources.style.display = 'none';
        paginator.style.display = 'none';
    }
}

/**
 * @brief this function display only the searched resource in the list
 * @param {*} type - type of resource (human or material)
 * @param {*} selected - the selected resource
 */
function filterResource(type, selected = null) {
    if (selected.id != "notfound") {
        var categoriesStr = [];
        //create a string of the categories of a resource
        for (var i = 0; i < selected["categories"].length - 1; i++) {
            categoriesStr += selected["categories"][i]["category"] + ", ";
        }
        if (selected["categories"].length > 0) {
            categoriesStr += selected["categories"][selected["categories"].length - 1]["category"];
        }

        var Type = type.charAt(0).toUpperCase() + type.slice(1); //equal to type.capitalize()
        var trs = document.querySelectorAll('#table' + Type + 'Resource tr:not(.header' + Type + 'Resource)');//get all the rows of the table
        for (let i = 0; i < trs.length; i++) {
            trs[i].style.display = 'none';//hide all the rows
        }
        table = document.getElementById(type + 'Table');//get the table
        var tr = document.createElement('tr');//create a row
        table.appendChild(tr);
        var resourceName = document.createElement('td');//create the name cell
        resourceName.append(selected.value);
        tr.appendChild(resourceName);
        var categoriestd = document.createElement('td');//create the categories cell
        categoriestd.append(categoriesStr);
        tr.appendChild(categoriestd);
        var buttons = document.createElement('td');//create the buttons cell
        var infos = document.createElement('button');//create information button
        infos.setAttribute('class', 'btn-infos btn-secondary');
        infos.setAttribute('onclick', 'showInfosModal' + Type + '(' + selected.id + ',"' + selected.value + '")');
        infos.append('Informations');
        var edit = document.createElement('button');//create edit button
        edit.setAttribute('class', 'btn-edit btn-secondary');
        edit.setAttribute('onclick', 'showEditModalForm' + Type + '(' + selected.id + ',"' + selected.value + '")');
        edit.append('Editer');
        var unavailabilities = document.createElement('button');//create unavailability button
        unavailabilities.setAttribute('class', 'btn-add btn-secondary');
        unavailabilities.setAttribute('onclick', 'showUnavailability' + Type + '(' + selected.id + ',"' + selected.value + '")');
        unavailabilities.append('Indisponibilités');
        var deleteButton = document.createElement('button'); //create delete button
        deleteButton.setAttribute('class', 'btn-delete btn-secondary');
        deleteButton.append('Supprimer');
        deleteButton.setAttribute('onclick', 'verifyHumanResourceScheduledAppointments(' + selected.id + ')');
        //add all buttons to the cell
        buttons.appendChild(infos);
        buttons.appendChild(unavailabilities);
        buttons.appendChild(edit);
        buttons.appendChild(deleteButton);
        tr.appendChild(buttons);
        paginator = document.getElementById('paginator');
        paginator.style.display = 'none';//hide paginator
    }
}

/**
 * @brief Display all the resources of a type
 * @param type type of the resource (human or material)
 */
function displayAll(type) {
    var trs = document.querySelectorAll('#table' + type + 'Resource tr:not(.header' + type + 'Resource)');//get all the rows of the table
    var input = document.getElementById('autocompleteInput' + type + 'Name');//get the input field
    if (input.value == '') {//if the input field is empty
        for (let i = 0; i < trs.length; i++) {//display all the rows
            if (trs[i].style.display == 'none') {
                trs[i].style.display = 'table-row';
            }
            else if (trs[i].className != 'original') {//if the row is not the original one (e.g if it is the one created with the search bar)
                trs[i].remove()//remove the row
            }
        }
        paginator = document.getElementById('paginator');
        paginator.style.display = '';//display the paginator
    }
}

/**
 * Allows to filter human resources according to entered category name
 */
function filterHumanResourceCategory(idInput, selected = null) {
    var trs = document.querySelectorAll('#tableHumanResourceCategory tr:not(.headerHumanResourceCategory)');
    if (selected == null) {
        var filter = document.querySelector('#' + idInput).value;
    }
    else {
        var filter = selected;
    }
    for (let i = 0; i < trs.length; i++) {
        var regex = new RegExp(filter, 'i');
        var name = trs[i].cells[0].outerText;
        if (hcrArray.indexOf(name) == -1) {
            hcrArray.push(name);
        }
        if (regex.test(name) == false) {
            trs[i].style.display = 'none';
        }
        else {
            trs[i].style.display = '';
        }
    }
}


/**
 * Allows to filter material resources according to entered category name
 */
function filterMaterialResourceCategory(idInput, selected = null) {
    var trs = document.querySelectorAll('#tableMaterialResourceCategory tr:not(.headerMaterialResourceCategory)');
    if (selected == null) {
        var filter = document.querySelector('#' + idInput).value;
    }
    else {
        var filter = selected;
    }
    for (let i = 0; i < trs.length; i++) {
        var regex = new RegExp(filter, 'i');
        var name = trs[i].cells[0].outerText;
        if (mcrArray.indexOf(name) == -1) {
            mcrArray.push(name);
        }
        if (regex.test(name) == false) {
            trs[i].style.display = 'none';
        }
        else {
            trs[i].style.display = '';
        }
    }
}

function getHumanResourceScheduledAppointments(index) {
    return $.ajax({
        type: 'GET',
        url: '/human-resource/' + index + '/appointments',
        dataType: "json",
    });
}

async function verifyHumanResourceScheduledAppointments(idHumanResource) {
    document.getElementById("form-human-resource-delete").action = "/human-resource/" + idHumanResource + "/delete"
    try {
        HUMAN_RESOURCE_APPOINTMENTS.scheduledAppointments = await getHumanResourceScheduledAppointments(idHumanResource)
        if (HUMAN_RESOURCE_APPOINTMENTS.scheduledAppointments.length > 0) {
            showHumanResourceScheduledAppointmentsModal()
        }
        else {
            $('#human-resource-modal-scheduled-appointments').modal('show');
            let body = document.getElementById('scheduled-appointments-body')
            body.style.overflowY = "hidden"
            body.innerHTML = "Voulez-vous vraiment supprimer cette ressource ?"
            document.getElementById("modal-subtitle").innerText = ""
        }
    }
    catch (err) {
        console.log(err);
    }
}

function showHumanResourceScheduledAppointmentsModal() {
    $('#human-resource-modal-scheduled-appointments').modal('show');
    document.getElementById("modal-subtitle").innerText = "En supprimant cette ressource, les RDV suivants seront affectés :"
    let body = document.getElementById('scheduled-appointments-body')
    body.innerHTML = ""
    for (let indexScheduledAppointment = 0; indexScheduledAppointment < HUMAN_RESOURCE_APPOINTMENTS.scheduledAppointments.length; indexScheduledAppointment++) {
        let p = document.createElement('p')

        lastname = HUMAN_RESOURCE_APPOINTMENTS.scheduledAppointments[indexScheduledAppointment].lastname
        firstname = HUMAN_RESOURCE_APPOINTMENTS.scheduledAppointments[indexScheduledAppointment].firstname
        pathwayname = HUMAN_RESOURCE_APPOINTMENTS.scheduledAppointments[indexScheduledAppointment].pathwayname
        date = HUMAN_RESOURCE_APPOINTMENTS.scheduledAppointments[indexScheduledAppointment].date
        p.innerHTML = date + ' - ' + lastname + ' ' + firstname + ' - ' + pathwayname
        body.appendChild(p)
    }
}

function getMaterialResourceScheduledAppointments(index) {
    return $.ajax({
        type: 'GET',
        url: '/material-resource/' + index + '/appointments',
        dataType: "json",
    });
}

async function verifyMaterialResourceScheduledAppointments(idMaterialResource) {
    document.getElementById("form-material-resource-delete").action = "/material-resource/" + idMaterialResource + "/delete"
    try {
        MATERIAL_RESOURCE_APPOINTMENTS.scheduledAppointments = await getMaterialResourceScheduledAppointments(idMaterialResource)
        if (MATERIAL_RESOURCE_APPOINTMENTS.scheduledAppointments.length > 0) {
            showMaterialResourceScheduledAppointmentsModal()
        }
        else {
            $('#material-resource-modal-scheduled-appointments').modal('show');
            let body = document.getElementById('scheduled-appointments-body')
            body.style.overflowY = "hidden"
            body.innerHTML = "Voulez-vous vraiment supprimer cette ressource ?"
            document.getElementById("modal-subtitle").innerText = ""
        }
    }
    catch (err) {
        console.log(err);
    }
}

function showMaterialResourceScheduledAppointmentsModal() {
    $('#material-resource-modal-scheduled-appointments').modal('show');
    document.getElementById("modal-subtitle").innerText = "En supprimant cette ressource, les RDV suivants seront affectés :"
    let body = document.getElementById('scheduled-appointments-body')
    body.innerHTML = ""
    for (let indexScheduledAppointment = 0; indexScheduledAppointment < MATERIAL_RESOURCE_APPOINTMENTS.scheduledAppointments.length; indexScheduledAppointment++) {
        let p = document.createElement('p')

        lastname = MATERIAL_RESOURCE_APPOINTMENTS.scheduledAppointments[indexScheduledAppointment].lastname
        firstname = MATERIAL_RESOURCE_APPOINTMENTS.scheduledAppointments[indexScheduledAppointment].firstname
        pathwayname = MATERIAL_RESOURCE_APPOINTMENTS.scheduledAppointments[indexScheduledAppointment].pathwayname
        date = MATERIAL_RESOURCE_APPOINTMENTS.scheduledAppointments[indexScheduledAppointment].date
        p.innerHTML = date + ' - ' + lastname + ' ' + firstname + ' - ' + pathwayname
        body.appendChild(p)
    }
}

function showHumanResourceCategoryModal(id) {
    $('#delete-human-resource-category-modal').modal('show');
    document.getElementById("form-human-resource-category-delete").setAttribute("action", "/human-resource-category/" + id + "/delete")
    let body = document.getElementById('resources-body')
    body.innerHTML = ""

    $.ajax({
        type: "POST",
        url: "/ajaxHumanResource",
        data: { idHumanResourceCategory: id },
        dataType: "json",
        success: function (data) {
            if (data.length > 0) {
                document.getElementById("modal-subtitle-category").innerText = "En supprimant cette catégorie, les RDV des ressources suivantes seront affectés :"
                for (let indexResource = 0; indexResource < data.length; indexResource++) {
                    let p = document.createElement('p')

                    p.innerHTML = data[indexResource]['humanresource']
                    body.appendChild(p)
                }
            }
            else {
                body.style.overflowY = "hidden"
                body.innerHTML = "Voulez-vous vraiment supprimer cette catégorie ?"
                document.getElementById("modal-subtitle-category").innerText = ""
            }

        },
        error: function () {
            console.log("error");
        },
    });
}

function showMaterialResourceCategoryModal(id) {
    $('#delete-material-resource-category-modal').modal('show');
    document.getElementById("form-material-resource-category-delete").setAttribute("action", "/material-resource-category/" + id + "/delete")
    let body = document.getElementById('resources-body')
    body.innerHTML = ""

    $.ajax({
        type: "POST",
        url: "/ajaxMaterialResource",
        data: { idMaterialResourceCategory: id },
        dataType: "json",
        success: function (data) {
            if (data.length > 0) {
                document.getElementById("modal-subtitle-category").innerText = "En supprimant cette catégorie, les RDV des ressources suivantes seront affectés :"
                for (let indexResource = 0; indexResource < data.length; indexResource++) {
                    let p = document.createElement('p')

                    p.innerHTML = data[indexResource]['materialresource']
                    body.appendChild(p)
                }
            }
            else {
                body.style.overflowY = "hidden"
                body.innerHTML = "Voulez-vous vraiment supprimer cette catégorie ?"
                document.getElementById("modal-subtitle-category").innerText = ""
            }

        },
        error: function () {
            console.log("error");
        },
    });
}

function resetWorkingHours(day, page) {
    console.log("resetWorkingHours")
    if (page == 'new') {
        begin = document.getElementById('working-hours-input-begin')
        end = document.getElementById('working-hours-input-end')
    }
    if (page == 'edit') {
        begin = document.getElementById('working-hours-input-begin-edit')
        end = document.getElementById('working-hours-input-end-edit')
    }
    if (page == 'auto') {
        begin = document.getElementById('working-hours-input-begin-auto')
        end = document.getElementById('working-hours-input-end-auto')
    }

    inputBegin = begin.getElementsByTagName('input')
    inputEnd = end.getElementsByTagName('input')

    inputBegin[day].value = ""
    inputEnd[day].value = ""
}

function showAutoHumanModalForm() {
    console.log("showAutoHumanModalForm")
    $('#auto-human-resource-modal').modal("show");
}

function showAutoMaterialModalForm() {
    $('#auto-material-resource-modal').modal("show");
}