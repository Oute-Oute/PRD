/**
 * @file consultation-planning.js
 * @brief This file contains the js scripts for the consultation planning page, essentially the calendar.
 * @author Thomas Blumstein
 * @version 2.0
 * @date 2022/07
 */

var calendar; // var globale pour le calendrier
var date = new Date(); //create a default date
var dateStr = date.toDateString();
var headerResources = "Patients";
const height = document.querySelector("div").clientHeight;
headerResources = "Patients";

/**

 * @brief This function is called when we want the value in the url
 * @param {*} param the value to get
 * @returns the value of the param
 */
function $_GET(param) {
  var vars = {};
  window.location.href.replace(location.hash, "").replace(
    /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
    function (key, value) {
      // callback
      vars[key] = value !== undefined ? value : "";
    }
  );

  if (param) {
    return vars[param] ? vars[param] : null;
  }
  return vars;
}

//update the date with the date in url
dateStr = $_GET("date");
date = new Date(dateStr);


document.addEventListener("DOMContentLoaded", function () {
if (document.getElementById("typeResources").value != "") {
  headerResources = document.getElementById("typeResources").value; //get the type of resources to display in the list
  headerResources = headerResources; //set the space in the header
  headerResources = headerResources; //set the comma in the header
} else {
  headerResources = "Patients";
}
document.querySelectorAll("#header-type")[0].innerText=headerResources;
  createCalendar(headerResources); //create the calendar
});

/**
 * @brief This function create the list of events to display in the calendar
 * @returns a list of the events of the calendar
 */
function createEvents() {
  var events = JSON.parse(
    document.getElementById("events").value.replaceAll("3aZt3r", " ")
  ); //get the events from the hidden input


  return events;
}

/**
 * @brief This function is called when we want to add a new comment
 */
function changeComment(){
  document.getElementById("new-comment").value = "";
  $("#ethic-comment-modal").modal("show"); //open the window
}

/**
 * @brief This function is called when we add a new comment
 */
function validComment(){
  var newComment = document.getElementById("new-comment").value;
  var idScheduledActivity = document.getElementById("id-scheduled-activity").value;
  var userName = document.getElementById("OwnUsername").value;
  console.log(idScheduledActivity, userName)

  $.ajax({
    type : 'POST',
    url  : '/ajaxEthicsAddComment',
    data : {newComment: newComment, idScheduledActivity: idScheduledActivity, userName: userName},
    dataType : "json",
    success : function(data){
      var event = calendar.getEventById(idScheduledActivity)
      var author = data["userLastname"] + " " + data["userFirstname"];
      event._def.extendedProps.comments.push({
        comment: data["newComment"],
        idcomment: data["idComment"],
        author: author
      });
      event._def.ui.backgroundColor = '#841919';
      event._def.ui.borderColor = '#841919';
      event.setStart(event.start);
    },
    error: function(data){
        console.log("error");
    }
  });
}

/**
 * 
 * @param {*} idComment 
 */
function deleteComment(idDivComment){
  var idComment = idDivComment.split('-')[1];

  $.ajax({
    type : 'POST',
    url  : '/ajaxEthicsDeleteComment',
    data : {idComment: idComment},
    dataType : "json",
    success : function(data){
      var event = calendar.getEventById(data["idScheduledActivity"]);
      for(var i = 0; i < event._def.extendedProps.comments.length; i++){
        if(event._def.extendedProps.comments[i].idcomment == data["idComment"]){
          event._def.extendedProps.comments.splice(i, 1);
        }
      }

      if(event._def.extendedProps.comments.length == 0){
        event._def.ui.backgroundColor = '#339d39';
        event._def.ui.borderColor = '#339d39';
        event.setStart(event.start);
      }
      $("#ethic-activity-modal").modal("hide"); //close the window
      openActivityModal(event);
    },
    error: function(data){
        console.log("error");
    }
  });

  /*var authorUsername = document.getElementById('username-' + idComment).value;
  var username = document.getElementById("OwnUsername").value;

  var confirm;
  if(username == authorUsername){
    confirm("Voulez-vous vraiment supprimer le commentaire de : " + authorUsername + " ?");
  }
  else {
    confirm("Voulez-vous vraiment supprimer votre commentaire ?");
  }
  console.log(idComment, document.getElementById("OwnUsername").value)*/
}

/**
 * 
 * @param {*} idComment 
 */
function editComment(idComment){

}

/**
 * 
 * @param {*} event 
 */
function openActivityModal(event){
  //get the data of the event
  var id = event._def.publicId; //get the id of the event
  var activity = calendar.getEventById(id); //get the event with the id
  var start = activity.start; //get the start date of the event
  var end = activity.end; //get the end date of the event
  var humanResources = activity.extendedProps.humanResources; //get the human resources of the event
  var humanResourcesNames = ""; //create a string with the human resources names
  if (humanResources.length > 0) {
    for (var i = 0; i < humanResources.length - 1; i++) {
      //for each human resource except the last one
      if (humanResources[i][1] != undefined) {
        //if the human resource exist
        humanResourcesNames += humanResources[i] + ", "; //add the human resource name to the string with a ; and a space
      }
    }
    humanResourcesNames += humanResources[i]; //add the last human resource name to the string
  } else humanResourcesNames = "Aucune ressource associée";
  var materialResources = activity.extendedProps.materialResources; //get the material resources of the event
  var materialResourcesNames = ""; //create a string with the material resources names

  if (materialResources.length > 0) {
    for (var i = 0; i < materialResources.length - 1; i++) {
      //for each material resource except the last one
      if (materialResources[i] != undefined) {
        //if the material resource exist
        materialResourcesNames += materialResources[i] + ", "; //add the material resource name to the string with a ; and a space
      }
    }

    materialResourcesNames += materialResources[i]; //add the last material resource name to the string
  } else materialResourcesNames = "Aucune ressource associée";

  var divComments = document.getElementById("comments");
  divComments.innerHTML = '';
  var comments = activity.extendedProps.comments; //get the comments of the event
  if (comments.length > 0) {
    for (var i = 0; i < comments.length; i++) {
      //for each comment except the last one
      if (comments[i] != undefined) {
        //if the comment exist
        let divComment = document.createElement('div');
        divComment.setAttribute('class', 'div-comment');
        divComment.setAttribute('id', 'comment' + (comments[i].idcomment));
        divComment.style.maxHeight = '150px'

        let divUsername = document.createElement('input')
        divUsername.setAttribute('id', 'username-' + comments[i].idcomment)
        divUsername.setAttribute('type', 'hidden')
        divUsername.setAttribute('value', comments[i].authorusername)

        let divContainerP = document.createElement('div');
        divContainerP.setAttribute('class', 'container-p');

        let p = document.createElement('p');
        p.style.width = '80%';
        p.innerHTML = comments[i].author + " : " + comments[i].comment; //add the comment to the string with a ; and a space
        divContainerP.appendChild(p);

        let imgDelete = new Image();
        imgDelete.src = '../../img/delete.svg'
        imgDelete.setAttribute('id', 'imgd-' + comments[i].idcomment)
        imgDelete.setAttribute('onclick', 'deleteComment(this.id)')
        imgDelete.setAttribute('title', 'Supprimer l\'activité du parcours')
        imgDelete.style.width = '20px'
        imgDelete.style.cursor = 'pointer'

        let imgEdit = new Image();
        imgEdit.src = '../../img/edit.svg'
        imgEdit.setAttribute('id', 'imge-' + comments[i].idcomment)
        imgEdit.setAttribute('onclick', 'editComment(this.id)')
        imgEdit.setAttribute('title', 'Édition de l\'activité')
        imgEdit.style.width = '20px'
        imgEdit.style.cursor = 'pointer'
        imgEdit.style.marginRight = '10px'

        let divImages = document.createElement('div')
        divImages.setAttribute('class', 'btns')
        divImages.appendChild(imgEdit)
        divImages.appendChild(imgDelete)

        divComment.appendChild(divUsername);
        divComment.appendChild(divContainerP);
        divComment.appendChild(divImages);
        divComments.appendChild(divComment);
      }
    }
  } else document.getElementById("comments").innerHTML = "Aucun commentaire";

  document.getElementById("id-scheduled-activity").value = event._def.publicId; //set id scheduled activity

  //set data to display in the modal window
  $("#start").val(start.toISOString().substring(0, 19)); //set the start date of the event
  $("#end").val(end.toISOString().substring(0, 19)); //set the end date of the event
  document.getElementById("show-title").innerHTML = activity.title; //set the title of the event
  $("#parcours").val(activity.extendedProps.pathway); //set the pathway of the event
  $("#patient").val(activity.extendedProps.patient); //set the patient of the event
  $("#rh").val(humanResourcesNames); //set the human resources of the event
  $("#rm").val(materialResourcesNames); //set the material resources of the event
  console.log(document.getElementById("comments").innerHTML)
  $("#ethic-activity-modal").modal("show"); //open the window
}

/**
 * @brief This function is called when we want to create or recreate the calendar
 * @param {*} typeResource the type of resources to display (Patients, Resources...)
 */
function createCalendar(typeResource,useCase, slotDuration,resourcesToDisplay = undefined) {
  var events = createEvents();
  if (document.getElementById("Date").value != null) {
    //if the date is not null (if the page is not the first load)
    dateStr = document.getElementById("Date").value; //get the date from the hidden input
  }
  if(typeResource=="Patients"){
    resourcesColumn=[{
      headerContent: "Nom", //set the label of the column
      field: "lastname", //set the field of the column
    },
    {
      headerContent: "Prénom", //set the label of the column
      field: "firstname", //set the field of the column
    }]
  }
  else{
    resourcesColumn=[{
      headerContent: "Nom", //set the label of the column
      field: "title", //set the field of the column
    },
    {
      headerContent: "Catégories", //set the label of the column
      field: "categoriesString", //set the field of the column
    }]
  }
  if(slotDuration === undefined){
    // if the slot duartion is not defined, we make sure to apply the default one
    slotDuration = "00:20:00"
  }
  
  date = new Date(dateStr); //create a new date with the date in the hidden input
  var calendarEl = document.getElementById("calendar"); //create the calendar variable
  //create the calendar
  calendar = new FullCalendar.Calendar(calendarEl, {
    schedulerLicenseKey: "CC-Attribution-NonCommercial-NoDerivatives", //we use a non commercial license
    initialView: "resourceTimelineDay", //set teh format of the calendar
    slotDuration: slotDuration, //set the duration of the slot
    scrollTimeReset:false, //dont change the view of the calendar
    locale: "fr", //set the language in french
    timeZone: "Europe/Paris", //set the timezone for France
    selectable: false, //set the calendar to be selectable
    editable: false, //set the calendar not to be editable
    height: $(window).height()*0.75, //set the height of the calendar to fit with a standard display
    handleWindowResize: true, //set the calendar to be resizable
    eventDurationEditable: false, //set the event duration not to be editable
    nowIndicator: true, //display the current time
    selectConstraint: "businessHours", //set the select constraint to be business hours
    eventMinWidth: 1, //set the minimum width of the event
    headerToolbar: {
      //delete the toolbar
      start: null,
      center: null,
      end: null,
    },
    resourceAreaColumns: resourcesColumn,
    slotLabelFormat: {
      //set the format of the time
      hour: "2-digit", //2-digit, numeric
      minute: "2-digit", //2-digit, numeric
      meridiem: false, //lowercase, short, narrow, false (display of AM/PM)
      hour12: false, //set to 24h format
    },
    resourceOrder: "title", //display the resources in the alphabetical order of their names
    resourceAreaWidth: "20%", //set the width of the resources area
    events: events, //set the events
    filterResourcesWithEvents: true,


    eventDidMount: function (info) {
      $(info.el).tooltip({
        title: info.event.extendedProps.description,
        placement: "top",
        trigger: "hover",
        container: "body",
      });
    },

    //when we click on an event, display a modal window with the event information
    eventClick: function (event) {
      openActivityModal(event.event);
    },
  });
  //change the type of the calendar(Patients, Resources...)
  switch (typeResource) {
    case "Patients": //if we want to display by the patients
      var tempArray = JSON.parse(
        document.getElementById("appointments").value.replaceAll("3aZt3r", " ")
      ); //get the data of the appointments
      for (var i = 0; i < tempArray.length; i++) {
        var temp = tempArray[i];
        patient = temp["patient"]; //get the resources data
        if (calendar.getResourceById(patient[0]["id"]) == null) {
          //if the resource is not already in the calendar
          calendar.addResource({
            //add the resources to the calendar
            id: patient[0]["id"], //set the id of the resource
            title: patient[0]["title"], //set the title of the resource
            lastname: patient[0]["lastname"], //set the lastname of the resource
            firstname: patient[0]["firstname"], //set the firstname of the resource
            businessHours: {
              //set the business hours of the resource
              startTime: patient[0]["businessHours"]["startTime"], //set the start time of the business hours
              endTime: patient[0]["businessHours"]["endTime"], //set the end time of the business hours
            },
          });
        }
      }
      break;
    case "Ressources Humaines": //if we want to display by the resources
    if(resourcesToDisplay!=undefined){
      var tempArray=resourcesToDisplay
    }
    else{
      var tempArray = JSON.parse(document.getElementById("human").value.replaceAll("3aZt3r", " ")); //get the data of the resources
    }
      for (var i = 0; i < tempArray.length; i++) {
        var temp = tempArray[i]; //get the resources data
        if (calendar.getResourceById(temp["id"]) == null) {
          //if the resource is not already in the calendar
          var businessHours = []; //create an array to store the working hours
          for (var j = 0; j < temp["workingHours"].length; j++) {
            businesstemp = {
              //create a new business hour
              startTime: temp["workingHours"][j]["startTime"], //set the start time
              endTime: temp["workingHours"][j]["endTime"], //set the end time
              daysOfWeek: [temp["workingHours"][j]["day"]], //set the day
            };
            businessHours.push(businesstemp); //add the business hour to the array
          }
          categories=temp["categories"];
          var categoriesStr = ""; //create a string with the human resources names
          var categoriesArray=[];
          if (categories.length > 0) {
            for (var i = 0; i < categories.length - 1; i++) {
              //for each human resource except the last one
              categoriesStr += categories[i]["name"] + ", "; //add the human resource name to the string with a ; and a space
              categoriesArray.push(categories[i]["name"]);
            }
            categoriesStr += categories[i]["name"]; //add the last human resource name to the string
          } else categoriesStr = "Défaut";
          calendar.addResource({
            //add the resources to the calendar
            id: temp["id"], //set the id
            title: temp["title"], //set the title            
            categoriesString: categoriesStr, //set the type
            businessHours: businessHours, //get the business hours
            categories:categoriesArray,
          });
        }
      }
      break;
    case "Ressources Matérielles": //if we want to display by the resources
    if(resourcesToDisplay!=undefined){
      var tempArray=resourcesToDisplay
    }
    else{
      var tempArray = JSON.parse(
        document.getElementById("material").value.replaceAll("3aZt3r", " ")
      ); //get the data of the resources
    }
      for (var i = 0; i < tempArray.length; i++) {
        var temp = tempArray[i]; //get the resources data
        if (temp != undefined) {
          if (calendar.getResourceById(temp["id"]) == null) {
            //if the resource is not already in the calendar
            categories=temp["categories"];
          var categoriesStr = ""; //create a string with the human resources names
          var categoriesArray=[];
          if (categories.length > 0) {
            for (var i = 0; i < categories.length - 1; i++) {
              
            
              //for each human resource except the last one
              categoriesStr += categories[i]["name"] + ", "; //add the human resource name to the string with a ; and a space
              categoriesArray.push(categories[i]["name"]);
            }
            categoriesStr += categories[i]["name"]; //add the last human resource name to the string
          } else categoriesStr = "Défaut";
            calendar.addResource({
              //add the resources to the calendar
              id: temp["id"], //set the id
              title: temp["title"], //set the title
              categoriesString: categoriesStr, //set the type
              categories:categoriesArray,
              
            });
          }
        }
      }
      break;
  }
  headerResources = typeResource;
  calendar.gotoDate(date); //go to the date we want to display
  calendar.render(); //display the calendar
}

$(window).resize(function () {
  calendar.setOption('height', $(window).height()*0.75);
});




