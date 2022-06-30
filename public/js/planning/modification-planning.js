// Timeout pour afficher le popup (pour éviter une modif trop longue)
var popupClicked = false;
var modifAlertTime = 500000000000000; // En millisecondes
//setTimeout(showPopup, modifAlertTime);
//setTimeout(deleteModifInDB, modifAlertTime+60000);

var calendar;
var CoundAddEvent=0; 
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
    var events = calendar.getEvents();
    let resources = [];
    events.forEach((event) => {
        resources.push(event.getResources());
    });
    document.getElementById('events').value = JSON.stringify(calendar.getEvents());
    document.getElementById('list-resource').value = JSON.stringify(resources);
    document.getElementById('validation-date').value = $_GET('date');
}

//function permettant l'ouverture de la modal d'ajout d'un parcours
function addEvent(){
    let selectContainerDate = document.getElementById('select-container-date');
    $('#add-planning-modal').modal("show");
    
}

function AddEventValider(){
    //Récupération de la bdd nécéssaire à l'ajout d'un parcours
    var listeSuccessors=JSON.parse(document.getElementById('listeSuccessors').value); 
    var listeActivities=JSON.parse(document.getElementById('listeActivities').value);
    var listeAppointments=JSON.parse(document.getElementById('listeAppointments').value);
    var appointmentid=document.getElementById('select-appointment').value; 
    var PathwayBeginTime=document.getElementById('timeBegin').value;
    //Date de début du parcours 
    var PathwayBeginDate= new Date(new Date(dateStr.substring(0,10)+' '+PathwayBeginTime).getTime()+2*60*60000); 
    var appointment; 
    //Récupération du rdv choisit par l'utiuilisateur
    for(let i=0; i<listeAppointments.length;i++){
        if(listeAppointments[i]['id']==appointmentid){
            appointment=listeAppointments[i]; 
        }
    }

    //Récupération des activités du parcours
    var activitiesInPathwayAppointment=[]; 
    for(let i=0;i<listeActivities.length;i++){
        if(listeActivities[i]['idPathway']==appointment['idPathway']){
            activitiesInPathwayAppointment.push(listeActivities[i]); 
        }
    }

    //On récupère l'ensemble des id activité b de la table successor pour trouver la première activité du parcours
    var successorsActivitybIdList=[]; 
    for(let i=0; i<listeSuccessors.length;i++){
        successorsActivitybIdList.push(listeSuccessors[i].idactivityb);
    }

    //get the forst activity of the pathway
    for(let i=0; i<activitiesInPathwayAppointment.length;i++){
            if(successorsActivitybIdList.includes(activitiesInPathwayAppointment[i].id)==false){
                var firstActivityPathway=activitiesInPathwayAppointment[i]; 
            }
    }
    
    var idactivitya=firstActivityPathway.id;
    var activitya; 
    var successoracivitya; 

    //Début de la création des events
    do{ 
        var idactivityB=undefined; 
        //find activity with idactivitya id
        for(let i=0; i<listeActivities.length;i++){
            if(listeActivities[i].id==idactivitya){
                activitya=listeActivities[i]; 
            }
        }
        //trouover dans la table successor le correspondant au activiteida
        for(let i=0; i<listeSuccessors.length;i++){
            if(listeSuccessors[i].idactivitya==idactivitya){
                successoracivitya=listeSuccessors[i]; 
                 idactivityB=listeSuccessors[i].idactivityb; 
            }
        }
        //countAddEvent pour avoir un id different pour chaque events ajoutes
        CoundAddEvent++; 
        //Ajout d'un event au calendar
        calendar.addEvent({
            id: 'new'+CoundAddEvent,
            resourceId: '2',
            title: activitya.name,
            start: PathwayBeginDate,
            end: PathwayBeginDate.getTime()+activitya.duration*60000,
          });
          
          //Detection de la dernière activite du parcours
            if(idactivityB != undefined){
                idactivitya=idactivityB;
            }
        PathwayBeginDate=new Date(PathwayBeginDate.getTime()+activitya.duration*60000); 
    }while(idactivityB != undefined); 
    calendar.render();
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
    var resourcearray=JSON.parse(document.getElementById('Resources').value.replaceAll("3aZt3r", " "));
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
