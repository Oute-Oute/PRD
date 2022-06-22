document.addEventListener('DOMContentLoaded', function() 
{
    const height = document.querySelector('div').clientHeight;
    var calendarEl = document.getElementById('calendar');

    //Créer le calendar sous les conditions que l'on souhaite
    var calendar = new FullCalendar.Calendar(calendarEl, 
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
        selectHelper: true,
        editable: true,
        eventDurationEditable: false,

        contentHeight: 9/12*height,
        handleWindowResize: true,

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
        resources: [
        {
            id: 'a',
           title: 'ressource a',
        },
        {
            id: 'b',
            title: 'ressource b',
        }
          ],
        events:[
            {
             id: "1", 
             resourceId: "a", 
             start: "2022-06-22 12:00:00", 
             end: "2022-06-22 17:30:00", 
             title: "event 1",
             color:'rgb(255,255,0)',
             textColor:'#000',
             textFont:'Trebuchet MS'
            }
        ],
        //à supprimer

        //permet d'ouvrir la modal pour la modification d'une activité lorsque l'on click dessus
        eventClick: function(event, element) {
            //récupération des données
            var id = event.event._def.publicId;
            var activity = calendar.getEventById(id);
            var start = activity.start;
            var tmp = activity.end - start;

            //calcul de la durée de l'activité
            length = Math.floor((tmp/1000/60));
            const options = { year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric' };
            date = start.toLocaleDateString(undefined, options);
    
            //set les données à afficher par défault
            $('#new-start').val(date);
            $('#new-title').val(activity.title);
            $('#length').val(length);
            $('#id').val(id);

            //ouvre la modal
            $('#modify-planning-modal').modal("show");
        },
    },
    );

    //affiche le calendar
    calendar.render();
});

//function permettant la modification de l'activité
function modifyEvent(){
    document.getElementById("succ").innerHTML="Modification de l'activité réussie";
    document.getElementById("success").style.display="block";
    unshowDiv('modify-planning-modal');
    setTimeout(()=>{document.getElementById("success").style.display="none";},6000);
}

//function permettant l'ouverture de la modal d'ajout d'un parcours
function addEvent(){
    $('#add-planning-modal').modal("show");
}
