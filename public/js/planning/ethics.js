/**
 * @file consultation-planning.js
 * @brief This file contains the js scripts for the consultation planning page, essentially the calendar.
 * @author Thomas Blumstein 
 * @author Vincent Blanco
 * @version 2.0
 * @date 2022/07
 */

var calendar; // var globale pour le calendrier
var date = new Date(); //create a default date
var dateStr = date.toDateString();
var headerResources = "Ressources Humaines";
const height = document.querySelector("div").clientHeight;

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
  headerResources = "Ressources Humaines";
}
document.querySelectorAll("#header-type")[0].innerText=headerResources;
  createCalendar(headerResources); //create the calendar
});


/**
 * @brief This function is called when we want to add a new comment
 */
function changeComment(id){
  document.getElementById("new-comment").value = "";
  document.getElementById("save-comment").disabled = true;
  $("#ethic-comment-modal").modal("show"); //open the window
  document.getElementById("save-comment").onclick = function(){
    validComment(id);
  }
  document.getElementById("cancelNewComment").onclick = function(){
    openActivityModal("old",id);
  }
} 

/**
 * @brief This function is called when we add a new comment
 */
function validComment(){
  var newComment = document.getElementById("new-comment").value;
  var idScheduledActivity = document.getElementById("id-scheduled-activity").value;
  var userName = document.getElementById("OwnUsername").value;

  $.ajax({
    type : 'POST',
    url  : '/ajaxEthicsAddComment',
    data : {newComment: newComment, idScheduledActivity: idScheduledActivity, userName: userName},
    dataType : "json",
    success : function(data){
      var event = calendar.getEventById(idScheduledActivity)
      events=JSON.parse(document.getElementById("events").value.replaceAll("3aZt3r", " "))
      var author = data["userLastname"] + " " + data["userFirstname"];
      event._def.extendedProps.comments.push({
        comment: data["newComment"],
        idcomment: data["idComment"],
        author: author
      });
      for(var i = 0; i < events.length; i++){
        if(events[i].id == event._def.publicId){
          events[i].extendedProps.comments.push({
            comment: data["newComment"],
            idcomment: data["idComment"],
            author: author
          });
          events[i].color='#841919';
          
        }
      }
      event._def.ui.backgroundColor = '#841919';
      event._def.ui.borderColor = '#841919';
      event.setStart(event.start);
      $("#ethic-activity-modal").modal("show"); //close the window
      openActivityModal("old",idScheduledActivity);
      document.getElementById("events").value = JSON.stringify(events);
    },
    error: function(data){
        console.log("error");
    }
  });
}

/**
 * @brief This function is called when we want to delete a comment
 * @param {*} idCommentDiv the id of the comment to edit
 */

function deleteCommentModale(idCommentDiv){
  $("#ethic-activity-modal").modal("hide");//close the window
  var idComment = idCommentDiv.split("-")[1];
  document.getElementById("delete-confirm-comment-id").value = idComment;
  var authorUsername = document.getElementById('username-' + idComment).value;
  var author = document.getElementById('author-' + idComment).value;
  var username = document.getElementById("OwnUsername").value;
  var confirm;
  if(username != authorUsername){//if the user is not the author of the comment
    confirm = "Voulez-vous vraiment supprimer le commentaire de : " + author + " ?";
  }
  else {//if the user is the author of the comment
    confirm = "Voulez-vous vraiment supprimer votre commentaire ?";
  }
  document.getElementById("confirm-delete-title").textContent = confirm;

  $("#ethic-confirm-delete-modal").modal("show"); //open the window
}

/**
 * @brief This function is called when we confirm the deletion of a comment
 * @param {*} idComment 
 */
function deleteCommentConfirm(){
  var idComment = document.getElementById("delete-confirm-comment-id").value;
  $.ajax({
    type : 'POST',
    url  : '/ajaxEthicsDeleteComment',
    data : {idComment: idComment},
    dataType : "json",
    success : function(data){
      var event = calendar.getEventById(data["idScheduledActivity"]);
      events=JSON.parse(document.getElementById("events").value.replaceAll("3aZt3r", " "))
      for(var i = 0; i < event._def.extendedProps.comments.length; i++){
        if(event._def.extendedProps.comments[i].idcomment == data["idComment"]){
          event._def.extendedProps.comments.splice(i, 1);
        }
      }
      for(var i = 0; i < events.length; i++){
        if(events[i].id == event._def.publicId){
          for(var j = 0; j < events[i].extendedProps.comments.length; j++){
            if(events[i].extendedProps.comments[j].idcomment == data["idComment"]){
              events[i].extendedProps.comments.splice(j, 1);
            }
          }
        }
      }

      if(event._def.extendedProps.comments.length == 0){//reset color of the event if there is no comment
        
        for(var i = 0; i < events.length; i++){
          if(events[i].id == event._def.publicId){
            events[i].color='#339d39';
            
          }
        }
        event._def.ui.backgroundColor = '#339d39';
        event._def.ui.borderColor = '#339d39';
        event.setStart(event.start);
        document.getElementById("events").value = JSON.stringify(events);
      }
      $("#ethic-activity-modal").modal("hide"); //close the window
      openActivityModal("new",event);
    },
    error: function(data){
        console.log("error");
        window.location.href = '/ethics';
    }
  });
}

/**
 * @brief This function is called when we want to edit a comment
 * @param {*} idDivComment the id of the comment to edit
 * @param {*} id the id of the scheduled activity where the comment is
 */
function editComment(idDivComment,id ){//edit a comment
  var idComment = idDivComment.split('-')[1];
  var commentEdit = document.getElementById("comment-hidden-" + idComment).value;
  document.getElementById("edit-comment-id").value = idComment;
  document.getElementById("edit-comment").value = commentEdit;
  document.getElementById("cancelEdit").onclick=function(){
    openActivityModal("old",id);
  }
  $("#ethic-activity-modal").modal("hide"); //close the window
  $("#ethic-edit-comment-modal").modal("show"); //open the window
}

/**
 * @brief This function is called when we confirm the edition of a comment
 */
function validEditComment(){//valid the edit of a comment
  var idComment = document.getElementById("edit-comment-id").value;
  var commentEdit = document.getElementById("edit-comment").value
  var userName = document.getElementById("OwnUsername").value;

  $.ajax({
    type : 'POST',
    url  : '/ajaxEthicsEditComment',
    data : {commentEdit: commentEdit, idComment: idComment, userName: userName},
    dataType : "json",
    success : function(data){
      var event = calendar.getEventById(data["idScheduledActivity"])
      events=JSON.parse(document.getElementById("events").value.replaceAll("3aZt3r", " "))
      var author = data["userLastname"] + " " + data["userFirstname"];
      var idComment = data["idComment"];
      var commentEdit = data["commentEdit"];
      for(var i = 0; i < event._def.extendedProps.comments.length; i++){
        if(event._def.extendedProps.comments[i].idcomment == idComment){
          event._def.extendedProps.comments[i].comment = commentEdit;
          event._def.extendedProps.comments[i].author = author
        }
      }
      for(var i = 0; i < events.length; i++){
        if(events[i].id == event._def.publicId){
          for(var j = 0; j < event._def.extendedProps.comments.length; j++){
            if(events[i].extendedProps.comments[j].idcomment == idComment){
              events[i].extendedProps.comments[j].comment = commentEdit;
              events[i].extendedProps.comments[j].author = author
            }
          }
        }
      }
      $("#ethic-edit-comment-modal").modal("hide"); //close the window
      openActivityModal("old",data["idScheduledActivity"])
      document.getElementById("events").value = JSON.stringify(events);
    },
    error: function(data){
        console.log("error");
        window.location.href = '/ethics';
    }
  });
}

/**
 * @brief This function is called when we want to add a new activity
 * @param {*} type the type of the activity
 * @param {*} event the event of the activity
 */
function openActivityModal(type="new",event){
  console.log(document.getElementById("buttonNewComment"))
  if(document.getElementById("buttonNewComment")!=null){
    document.getElementById("footerComments").removeChild(document.getElementById("buttonNewComment"));
  }
  //get the data of the event
  if(type == "old"){//if we want to edit an activity
    var id=event;
  }
  else{//if we want to add an activity
    var id = event._def.publicId; //get the id of the event
    
  }
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
  document.getElementById("show-title").textContent = activity.title; //set the title of the activity
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

        let divUsername = document.createElement('input')
        divUsername.setAttribute('id', 'username-' + comments[i].idcomment)
        divUsername.setAttribute('type', 'hidden')
        divUsername.setAttribute('value', comments[i].authorusername)
        
        let divAuthor = document.createElement('input')
        divAuthor.setAttribute('id', 'author-' + comments[i].idcomment)
        divAuthor.setAttribute('type', 'hidden')
        divAuthor.setAttribute('value', comments[i].author)

        let divCommentHidden = document.createElement('input')
        divCommentHidden.setAttribute('id', 'comment-hidden-' + comments[i].idcomment)
        divCommentHidden.setAttribute('type', 'hidden')
        divCommentHidden.setAttribute('value', comments[i].comment)

        let divContainerP = document.createElement('div');
        divContainerP.setAttribute('class', 'container-p');

        let p = document.createElement('p');
        p.style.width = '80%';
        p.innerHTML = comments[i].author + " : " + comments[i].comment.replaceAll("\n","<br>"); //add the comment to the string with a ; and a space
        divContainerP.appendChild(p);
        let divImages = document.createElement('div')
        divImages.setAttribute('class', 'btns')
        console.log(comments[i])
        console.log(document.getElementById("OwnUsername").value)
        if(comments[i].authorusername == document.getElementById("OwnUsername").value){
        let imgEdit = new Image();
        imgEdit.src = '../../img/edit.svg'
        imgEdit.setAttribute('id', 'imge-' + comments[i].idcomment)
        imgEdit.setAttribute('onclick', 'editComment(this.id,"'+id+'")')
        imgEdit.setAttribute('title', 'Édition du commentaire')
        imgEdit.style.width = '20px'
        imgEdit.style.cursor = 'pointer'
        imgEdit.style.marginRight = '10px'
        divImages.appendChild(imgEdit)
        }

        let imgDelete = new Image();
        imgDelete.src = '../../img/delete.svg'
        imgDelete.setAttribute('id', 'imgd-' + comments[i].idcomment)
        imgDelete.setAttribute('onclick', 'deleteCommentModale(this.id)')
        imgDelete.setAttribute('title', 'Supprimer le commentaire')
        imgDelete.style.width = '20px'
        imgDelete.style.cursor = 'pointer'
        divImages.appendChild(imgDelete)

        divComment.appendChild(divUsername);
        divComment.appendChild(divAuthor);
        divComment.appendChild(divCommentHidden);
        divComment.appendChild(divContainerP);
        divComment.appendChild(divImages);
        divComments.appendChild(divComment);
      }
    }
  } else document.getElementById("comments").innerHTML = "Aucun commentaire";
  var footer=document.getElementById("footerComments");
  var btnNew = document.createElement('button');
  btnNew.setAttribute('id', 'buttonNewComment');
  btnNew.setAttribute('class', 'btn-edit btn-secondary');
  btnNew.setAttribute('onclick', 'changeComment("' + id + '")');
  btnNew.setAttribute('data-bs-dismiss', 'modal');
  btnNew.innerHTML = "Nouveau commentaire";
  footer.appendChild(btnNew);

  document.getElementById("id-scheduled-activity").value = id; //set id scheduled activity

  //set data to display in the modal window
  $("#start").val(start.toISOString().substring(0, 19)); //set the start date of the event
  $("#end").val(end.toISOString().substring(0, 19)); //set the end date of the event
  $("#parcours").val(activity.extendedProps.pathway); //set the pathway of the event
  $("#patient").val(activity.extendedProps.patient); //set the patient of the event
  $("#rh").val(humanResourcesNames); //set the human resources of the event
  $("#rm").val(materialResourcesNames); //set the material resources of the event
  $("#ethic-activity-modal").modal("show"); //open the window
}

/**
 * @brief This function is called when we want to create or recreate the calendar
 * @param {*} typeResource the type of resources to display (Patients, Resources...)
 */
function createCalendar(typeResource,useCase, slotDuration,resourcesToDisplay = undefined) {
  var events = JSON.parse(document.getElementById("events").value.replaceAll("3aZt3r", " ")); //get the events from the hidden input
  console.log(events);
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
      openActivityModal("new",event.event);
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

function enableButton(type){
  if(type == 'edit'){
    area=document.getElementById('edit-comment');
  }
  else if(type == 'new'){
    area=document.getElementById('new-comment');
  }
  if (area.value==''){
    $('button[for="save-comment"]')[0].disabled = true;
    $('button[for="save-comment"]')[1].disabled = true;
  }
  else{
    $('button[for="save-comment"]')[0].disabled = false;
    $('button[for="save-comment"]')[1].disabled = false;
  }
}

