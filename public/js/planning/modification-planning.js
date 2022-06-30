// Timeout pour afficher le popup (pour éviter une modif trop longue)
var popupClicked = false;
var modifAlertTime = 500000000000000; // En millisecondes
setTimeout(showPopup, modifAlertTime);
setTimeout(deleteModifInDB, modifAlertTime+60000);

var calendar;
var headerResources="Ressources Matérielles";
var dateStr=($_GET('date')).replaceAll('%3A',':'); 
var date=new Date(dateStr);
function $_GET(param) {
	var vars = {};
	window.location.href.replace( location.hash, '' ).replace( 
		/[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
		function( m, key, value ) { // callback
			vars[key] = value !== undefined ? value : '';
		}
	);
    
	if ( param ) {
		return vars[param] ? vars[param] : null;	
	}
	return vars;
}

console.log(dateStr); 

document.addEventListener('DOMContentLoaded', function() 
{
    

    //Créer le calendar sous les conditions que l'on souhaite
    createCalendar()

    
});

function unshowDiv(id) {
    document.getElementById(id).style.display = "none";
  }

//function permettant la modification de l'activité
function modifyEvent(){
    var id = document.getElementById('id').value;
    var event = calendar.getEventById(id)
    var today = $_GET('date').substring(0,10);
    var start = today + "T" + document.getElementById('new-start').value;
    var length = document.getElementById('length').value;
    var date = new Date(start.replace("T", " "))

    var end = new Date(date.getTime()+length*60*1000);

    event.setStart(start);
    event.setEnd(formatDate(end).replace(" ", "T"));
    $('#modify-planning-modal').modal('toggle');
}

function formatDate(date){
    return (
        [
          date.getFullYear(),
          (date.getMonth() + 1).toString().padStart(2, '0'),
          (date.getDate()).toString().padStart(2, '0'),
        ].join('-') +
        ' ' +
        [
          (date.getHours()).toString().padStart(2, '0'),
          (date.getMinutes()).toString().padStart(2, '0'),
        ].join(':')
      );
}

function setEvents(){
    document.getElementById('events').value = JSON.stringify(calendar.getEvents());
    document.getElementById('validation-date').value = $_GET('date');
}

//function permettant l'ouverture de la modal d'ajout d'un parcours
function addEvent(){
    let selectContainerCircuit = document.getElementById('select-container-circuit');
    let selectContainerDate = document.getElementById('select-container-date');
    $('#add-planning-modal').modal("show");
}

function showSelectDate(){
    let selectContainerDate = document.getElementById('select-container-date');
    selectContainerDate.style.display = "block";
}

function changePlanning(){
    var selectedItem = document.getElementById("displayList");
      headerResources=document.getElementById("displayList").options[document.getElementById('displayList').selectedIndex].text;
      createCalendar();
  }
  
  function filterShow(){
    if(document.getElementById("filterId").style.display != "none"){
      document.getElementById("filterId").style.display = "none";
    } else {
      document.getElementById("filterId").style.display = "inline-block";
    }
  }

function createCalendar(){
    const height = document.querySelector('div').clientHeight;
    var calendarEl = document.getElementById('calendar');
    var resourcearray=JSON.parse(document.getElementById('Humanresources').value.replaceAll("3aZt3r", " "));
    var eventsarray=JSON.parse(document.getElementById('listeScheduledActivitiesJSON').value.replaceAll("3aZt3r", " "));
    console.log(eventsarray); 



    calendar = new FullCalendar.Calendar(calendarEl, 
        {
            //clé de la license pour utiliser la librairie à des fin non commerciale
            schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
    
            //initialise la vue en colonne par ressource par jour en horaire française
            initialView: 'resourceTimelineDay',
            slotDuration: '00:20:00',
            locale: 'fr',
            timeZone: 'Europe/Paris',
    
            //permet de modifier les events dans le calendar
            selectable: true,
            editable: true,
            eventDurationEditable: false,
            contentHeight: 9/12*height,
            handleWindowResize: true,
            nowIndicator: true,
    
            //modifie l'affichage de l'entête du calendar pour ne laisser que la date du jour
            headerToolbar: {
                start: null,
                center: 'title',
                end: null
            },
    
            //modifie l'affichage des heures de la journée
            slotLabelFormat: { 
                hour: '2-digit',
                minute: '2-digit',
                meridiem: false,
                hour12: false
            },
            
            //à supprimer
            resourceOrder: 'title',
            resourceAreaWidth: '20%',
            resourceAreaHeaderContent: headerResources,
            resources: resourcearray,
            events:eventsarray,
    
            //permet d'ouvrir la modal pour la modification d'une activité lorsque l'on click dessus
            eventClick: function(event, element) {
                //récupération des données
                var id = event.event._def.publicId;
                var activity = calendar.getEventById(id);
                var start = activity.start;
                var tmp = activity.end - start;
    
                //calcul de la durée de l'activité
                length = Math.floor((tmp/1000/60));
        
                //set les données à afficher par défault
                $('#new-start').val(start.toISOString().substring(11,16));
                document.getElementById('show-title').innerHTML = activity.title;
                $('#title').val(activity.title);
                $('#length').val(length);
                $('#id').val(id);
    
                //ouvre la modal
                $('#modify-planning-modal').modal("show");
            },
        },
        );
        //affiche le calendar
    calendar.gotoDate(date);
    calendar.render();
}

function showPopup() {
    $("#divPopup").show();
}

function closePopup() {
    $("#divPopup").hide();
    popupClicked = true;
    setTimeout(showPopup, modifAlertTime);
}

document.addEventListener('DOMContentLoaded', function() {
    var userData = document.querySelector('.js-data');
    var userId = userData.dataset.userId;
});

function deleteModifInDB(popupClicked){
    if(popupClicked){
        popupClicked = false;
        setTimeout(deleteModifInDB, modifAlertTime);
    }
    else{
        // Supprimer modif sur la BDD
        
    }
}
