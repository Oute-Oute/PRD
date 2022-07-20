/**
 * Permet d'afficher la fenêtre modale d'informations
 */
 function showInfosModalHuman(idHumanResource, resourceName) {
    document.getElementById('human-resource1').innerHTML = resourceName;
    document.getElementById('human-resource2').innerHTML = resourceName;
    document.getElementById('human-resource3').innerHTML = resourceName;
   
    var tableBody = document.getElementById('tbody-human-resource');
    tableBody.innerHTML = ''; // On supprime ce qui a précédemment été écrit dans la modale

    $.ajax({
        type : 'POST',
        url  : '/ajaxHumanResource',
        data : {idHumanResource: idHumanResource},
        dataType : "json",
        success : function(data){  
            tableResource(tableBody, data, "humanresource");
        },
        error: function(data){
            console.log("error");
        }
        });
        
    createCalendarResource("humanresource");
    getWorkingHours(idHumanResource);
    change_tab_human_infos('planning');
    $('#infos-human-resource-modal').modal("show");
}

function showInfosModalHumanCateg(idHumanResourceCategory, resourceCategName) {
    document.getElementById('human-resource-category').innerHTML = resourceCategName;

    var tableBody = document.getElementById('tbody-human-resource-category');
    tableBody.innerHTML = ''; // On supprime ce qui a précédemment été écrit dans la modale

    $.ajax({
        type : 'POST',
        url  : '/ajaxHumanResource',
        data : {idHumanResourceCategory: idHumanResourceCategory},
        dataType : "json",
        success : function(data){  
            tableResource(tableBody, data, "humanresourcecategory");
        },
        error: function(data){
            console.log("error");
        }
        });

    $('#infos-human-resource-category-modal').modal("show");
}

function showInfosModalMaterial(idMaterialResource, resourceName) {
    document.getElementById('material-resource1').innerHTML = resourceName;
    document.getElementById('material-resource2').innerHTML = resourceName;
   
    var tableBody = document.getElementById('tbody-material-resource');
    tableBody.innerHTML = ''; // On supprime ce qui a précédemment été écrit dans la modale

    $.ajax({
        type : 'POST',
        url  : '/ajaxMaterialResource',
        data : {idMaterialResource: idMaterialResource},
        dataType : "json",
        success : function(data){  
            tableResource(tableBody, data, "materialresource");
        },
        error: function(data){
            console.log("error");
        }
        });
        createCalendarResource("materialresource");
    change_tab_material_infos('planning');
    $('#infos-material-resource-modal').modal("show");
}

function showInfosModalMaterialCateg(idMaterialResourceCategory, resourceCategName) {
    document.getElementById('material-resource-category').innerHTML = resourceCategName;

    var tableBody = document.getElementById('tbody-material-resource-category');
    tableBody.innerHTML = ''; // On supprime ce qui a précédemment été écrit dans la modale

    $.ajax({
        type : 'POST',
        url  : '/ajaxMaterialResource',
        data : {idMaterialResourceCategory: idMaterialResourceCategory},
        dataType : "json",
        success : function(data){  
            tableResource(tableBody, data, "materialresourcecategory");
        },
        error: function(data){
            console.log("error");
        }
        });

    $('#infos-material-resource-category-modal').modal("show");
}

function tableResource(tableBody, data, switch_param){
    if(data.length <= 0){
        var tr = document.createElement('TR');
        tableBody.appendChild(tr);
        var td = document.createElement('TD');
        td.setAttribute('colspan', 5);
        switch(switch_param){
            case "humanresource":
                td.append("Pas de catégorie associée à cette ressource !");
            break;
            case "humanresourcecategory":
                td.append("Pas de ressource associée à cette catégorie !");
            break;
            case "materialresource":
                td.append("Pas de catégorie associée à cette ressource !");
            break;
            case "materialresourcecategory":
                td.append("Pas de ressource associée à cette catégorie !");
            break;
        }
        tr.appendChild(td);
    }
    else{
        for(i = 0; i < data.length; i++){
            var tr = document.createElement('TR');
            tableBody.appendChild(tr);
            var td = document.createElement('TD');
            switch(switch_param){
                case "humanresource":
                    td.append(data[i]['humanresourcecategory']);
                break;
                case "humanresourcecategory":
                    td.append(data[i]['humanresource']);
                break;
                case "materialresource":
                    td.append(data[i]['materialresourcecategory']);
                break;
                case "materialresourcecategory":
                    td.append(data[i]['materialresource']);
                break;
            }
            tr.appendChild(td);
        }
    }
}

function getWorkingHours(id){
    var WORKING_HOURS_FILTERED =  WORKING_HOURS.filter(function(WORKING_HOUR) {
        return WORKING_HOUR.humanresource_id == id;
    });

    let beginHours = document.getElementById('working-hours-infos-begin')
    let endHours = document.getElementById('working-hours-infos-end')
    for(let y = 0; y<WORKING_HOURS_FILTERED.length; y++){
        switch (WORKING_HOURS_FILTERED[y].dayweek) {
            case 0:

            beginHours.children[6].value = WORKING_HOURS_FILTERED[y].starttime.date.substring(11,16)
            endHours.children[6].value = WORKING_HOURS_FILTERED[0].endtime.date.substring(11,16)

            break;

            case 1:

            beginHours.children[0].value = WORKING_HOURS_FILTERED[y].starttime.date.substring(11,16)
            endHours.children[0].value= WORKING_HOURS_FILTERED[y].endtime.date.substring(11,16)

            break;

            case 2:

            beginHours.children[1].value = WORKING_HOURS_FILTERED[y].starttime.date.substring(11,16)
            endHours.children[1].value = WORKING_HOURS_FILTERED[y].endtime.date.substring(11,16)

            break;

            case 3:

            beginHours.children[2].value = WORKING_HOURS_FILTERED[y].starttime.date.substring(11,16)
            endHours.children[2].value = WORKING_HOURS_FILTERED[y].endtime.date.substring(11,16)

            break;

            case 4:

            beginHours.children[3].value = WORKING_HOURS_FILTERED[y].starttime.date.substring(11,16)
            endHours.children[3].value = WORKING_HOURS_FILTERED[y].endtime.date.substring(11,16)

            break;

            case 5:

            beginHours.children[4].value = WORKING_HOURS_FILTERED[y].starttime.date.substring(11,16)
            endHours.children[4].value = WORKING_HOURS_FILTERED[y].endtime.date.substring(11,16)

            break;

            case 6:

            beginHours.children[5].value = WORKING_HOURS_FILTERED[y].starttime.date.substring(11,16)
            endHours.children[5].value = WORKING_HOURS_FILTERED[y].endtime.date.substring(11,16)

            break;
        }
    }
}

function change_tab_human_infos(id)
{
  document.getElementById("planning").className="notselected";
  document.getElementById("workinghours").className="notselected";
  document.getElementById("categoriesbyresource").className="notselected";
  document.getElementById(id).className="selected";

  let planning = document.getElementById("human-resource-planning");
  let workinghours = document.getElementById("human-resource-working-hours");
  let categories = document.getElementById("human-resource-categories");
  
  switch(id){
    case 'planning':
        planning.style.display = 'block';
        workinghours.style.display = 'none';
        categories.style.display = 'none';
    break;
    case 'workinghours':
        planning.style.display = 'none';
        workinghours.style.display = 'block';
        categories.style.display = 'none';
    break;
    case 'categoriesbyresource':
        planning.style.display = 'none'
        workinghours.style.display = 'none';
        categories.style.display = 'block';
    break;
  }
}

function change_tab_material_infos(id)
{
  document.getElementById("planning").className="notselected";
  document.getElementById("categoriesbyresource").className="notselected";
  document.getElementById(id).className="selected";

  let planning = document.getElementById("material-resource-planning");
  let categories = document.getElementById("material-resource-categories");
  
  switch(id){
    case 'planning':
        planning.style.display = 'block';
        categories.style.display = 'none';
    break;
    case 'categoriesbyresource':
        planning.style.display = 'none';
        categories.style.display = 'block';
    break;
  }
}

/**
 * @brief This function is called when we want to create or recreate the calendar
 * @param {*} resources the type of resources to display (Patients, Resources...)
 */
 function createCalendarResource(type) {
    console.log("createCalendarResource");
    date = new Date(); //create a new date with the date in the hidden input
    var calendarEl = document.getElementById("calendar-hr"); //create the calendar variable

    //create the calendar
    calendar = new FullCalendar.Calendar(calendarEl, {
        schedulerLicenseKey: "CC-Attribution-NonCommercial-NoDerivatives", //we use a non commercial license
        timeZone: 'UTC',
    initialView: 'timeGridWeek',
    headerToolbar: {
        left: '',
        center: '',
        right: ''
    },
    events: 'https://fullcalendar.io/api/demo-feeds/events.json',
      eventDidMount: function (info) {
        $(info.el).tooltip({
          //title: info.event.extendedProps.description,
          placement: "top",
          trigger: "hover",
          container: "body",
        });
      },
    });
    calendar.render(); //render the calendar
}   
  function addTargetsToCalendar(targets) {
    targets.forEach(element => {
      calendar.addEvent({
        allDay: true,
        start: element.start,
        description: element.description,
        display:'background',
        color:element.color,
  
    });
    
  });
  calendar.render();
  }