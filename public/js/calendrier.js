
var evenements = []

document.addEventListener('DOMContentLoaded', event = () =>  {
    console.log('DOM Loaded...');

    // On recupere notre div qui contiendra le calendrier
    let elementCalendrier = document.getElementById("calendrier")
    console.log(elementCalendrier)

    // Creation des evenements
    evenements = 
    [
        {
        id:'1',
        resourceId:'0',
        title:"Gouter",
        start:"2022-06-17 09:10:00",
        end:"2022-06-17 10:05:00"
        },
        {
        id:'1',
        resourceId:'1',
        title:"Chirurgie",
        start:"2022-06-17 12:00:00",
        end:"2022-06-17 15:00:00"
        },
        {
        id:'1',
        resourceId:'1',
        title:"Rhinoplastie",
        start:"2022-06-17 12:00:00",
        end:"2022-06-17 17:00:00"
        },
        {
        id:'1',
        resourceId:'2',
        title:"Chirurgie",
        start:"2022-06-17 08:10:00",
        end:"2022-06-17 10:05:00"
        },
    ]


    // Creation du calendrier 

    let calendrier = new FullCalendar.Calendar(elementCalendrier, {
        // a
        //plugins: ['dayGrid', 'timeGrid'],
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
        selectable: 'true',
        editable: true,
        initialView: 'resourceTimelineDay',
        events: evenements,

        slotDuration : '00:15:00',
    

        eventDidMount: (arg) =>{
            const eventId = arg.event.id
            arg.el.addEventListener("contextmenu", (jsEvent)=>{

                jsEvent.preventDefault()
                console.log("contextMenu", eventId)
                var contextElement = document.getElementById("context-menu");
                contextElement.style.top = jsEvent.pageY + "px";
                contextElement.style.left = jsEvent.pageX + "px";
                contextElement.classList.add("active")
            })
        }
    })


    // Creation des ressources
    
    Users = [
        {"nom":"Vall", 
        "prenom":"Virgile",
        "id":"1"},
        {"nom":"Nome", 
        "prenom":"Louis",
        "id":"2"},
        {"nom":"Nom", 
        "prenom":"Pierre",
        "id":"3"},
    ]

    console.log(Users)
    console.log(Users[0])

    // Ajout des ressources aux calendriers
    for (let i = 0; i < Users.length; i++) {
        calendrier.addResource({
            id: i,
            title: Users[i].nom + " " +  Users[i].prenom
        }); 
    }


    calendrier.render()

})


window.addEventListener("click", function() {
    this.document.getElementById("context-menu").classList.remove("active");
});

function deleteEvent() {
    console.log("del")
}

function editEvent() {
    console.log("edit")
}

