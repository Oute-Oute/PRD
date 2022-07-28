var lines= new Array();

/**
 * Permet d'afficher la fenêtre modale d'informations
 */
 function showInfosPathway(idPathway, name) {
    document.getElementById('pathway1').innerHTML = name;
    document.getElementById('pathway2').innerHTML = name;

    var tableBody = document.getElementById('tbodyShow');
    tableBody.innerHTML = ''; // On supprime ce qui a précédemment été écrit dans la modale

    $.ajax({
        type: 'POST',
        url: '/ajaxPathwayAppointments',
        data: { idPathway: idPathway },
        dataType: "json",
        success: function (data) {
            tableAppointment(tableBody, data);
        },
        error: function (data) {
            console.log("error : can't access appointments");
        }
    });

    $.ajax({
        type: 'POST',
        url: '/ajaxPathwayActivities',
        data: { idPathway: idPathway },
        dataType: "json",
        success: function (data) {
            drawActivities(data);
        },
        error: function() {
            var divContent = document.getElementById('divContent');
            divContent.innerHTML = "Pas d'activités créées !";
            document.getElementById('load-path').style.visibility = "hidden";
          }
        });

    change_tab('activities');
    $('#infos-pathway-modal').modal("show");
  document.getElementById('load-path').style.visibility = "visible";
}

function tableAppointment(tableBody, data) {
    if (data.length <= 0) {
        var tr = document.createElement('TR');
        tableBody.appendChild(tr);
        var td = document.createElement('TD');
        td.setAttribute('colspan', 5);
        td.append("Pas de patients prévus pour ce parcours");
        tr.appendChild(td);
    }
    else {
        for (i = 0; i < data.length; i++) {
            var tr = document.createElement('TR');
            tableBody.appendChild(tr);
            var td1 = document.createElement('TD');
            var td2 = document.createElement('TD');
            td1.append(data[i]['lastname'] + ' ' + data[i]['firstname']);
            td2.append(data[i]['date']);
            tr.appendChild(td1); tr.appendChild(td2);
        }
    }
}

function change_tab(id) {
    document.getElementById("activities").className = "notselected";
    document.getElementById("appointments").className = "notselected";
    document.getElementById(id).className = "selected";

    let activities = document.getElementById("pathway-activities");
    let appointments = document.getElementById("pathway-appointments");

    switch (id) {
        case 'activities':
            showArrows();
            activities.style.display = 'block';
            appointments.style.display = 'none';
            break;
        case 'appointments':
            hideArrows();
            activities.style.display = 'none';
            appointments.style.display = 'block';
            break;
    }
}

function drawActivities(data){
    var divContent = document.getElementById('divContent');
    divContent.innerHTML = ""; // reset the content

    maxLevel = 0;
    arrayActivityByLevel = Array();
    
    // get the max level, used to cut the div vertically
    for(i = 0; i < data.length; i++){
        if(maxLevel < data[i]['level']){
            maxLevel = data[i]['level'];
        }
    }

    // for each level, get all the activities from it
    for(i = 0; i < maxLevel; i++){
        arrayActivityByLevel[i] = [0]; // initialize
    }    
    for(i = 0; i < data.length; i++){
        arrayActivityByLevel[data[i]['level']-1][0]++;
        arrayActivityByLevel[data[i]['level']-1].push(data[i]['activity']['name'], i, data[i]['activity']['duration']);
    }

    // get the maximum of activities within one level, 
    // used to print some br to have enough space to draw the graph
    maxActivityByLevel = 0;
    for(i = 0; i < arrayActivityByLevel.length; i++){
        if(maxActivityByLevel < arrayActivityByLevel[i][0]){
            maxActivityByLevel = arrayActivityByLevel[i][0];
        }
    }

    var divBr= document.getElementById('modal-br');
    divBr.innerHTML = ""; // reset the previous br to prevent infinite growth of the modal
    for(i = 0; i < maxActivityByLevel*3; i++){
        var br = document.createElement("br");
        divBr.appendChild(br);
    }

    // update flex basis to separate acitivies accordingly
    const style = document.createElement('style');
    style.innerHTML = `
        .block {
            flex-basis:` + Math.round(100/maxLevel) + `%;
        }
        `;
    document.head.appendChild(style);

    for(i = 0; i < maxLevel; i++){
        var div = document.createElement('DIV');
        div.classList.add("block", "wrapper");
        div.style.flexDirection = "column";
        div.setAttribute('id', 'content'+ (i+1));
        
        divContent.appendChild(div);
    }

    /*  This switch sets the vertical position for all activities :
        If this is the only activity on this level, translate by 50% (middle)
        If there are 2 activities, one is translated by 2% and the other by 125%
        Ect...
    */
    for(i=0; i < arrayActivityByLevel.length; i++){
        nbActivity = arrayActivityByLevel[i][0];
        switch(nbActivity){
            case 1:
                height = 100/(nbActivity*2);
                createActivities(height, i+1, arrayActivityByLevel[i][1], arrayActivityByLevel[i][2], arrayActivityByLevel[i][3]);
            break;
            default:
                for(j = 0; j < nbActivity; j++){
                    if(nbActivity%2 == 0){
                        if(j < nbActivity/2){
                            height = 100 + 50/nbActivity;
                        }
                        else{
                            height = 25;
                        }
                    }
                    else{
                        if(j < nbActivity/2){
                            height = 100 + 50/nbActivity;
                        }
                        if(j == nbActivity/2){
                            height = 50;
                        }
                        if(j > nbActivity/2){
                            height = 50/nbActivity;
                        }
                    }
                    createActivities(height, i+1, arrayActivityByLevel[i][j*3+1], arrayActivityByLevel[i][j*3+2], arrayActivityByLevel[i][j*3+3]);
                }
            break;
        }
    }
    drawArrows(data);
  document.getElementById('load-path').style.visibility = "hidden";
}

function createActivities(height, level, name, idActivity, duration){
    var divLevel = document.getElementById('content' + level);

    var div = document.createElement('DIV');
    div.setAttribute('id', 'activity'+ idActivity);
    div.classList.add("pathway-div-activity");
    div.style.transform = 'translate(0%, -' + height + '%)';

    var divHeader = document.createElement('div');
    divHeader.classList.add("pathway-div-activity-header");
    divHeader.innerHTML = name;

    var p = document.createElement('p');
    p.style.fontSize = '80%';
    p.innerHTML = "durée : " + duration + "min"; 

    div.appendChild(divHeader); div.appendChild(p);

    divLevel.appendChild(div);
}

function drawArrows(data){
    lines = new Array();
    for(i = 0; i < data.length; i++){
        for(j = 0; j < data[i]['successorsIndex'].length; j++){
            start = document.getElementById('activity'+ i);
            end = document.getElementById('activity'+ data[i]['successorsIndex'][j]);
            l = new LeaderLine(start, end, {color: '#0dac2d'});
            // We store every line to show/hide them when we switch tabs
            // When you click outside the modal, the line array is reset (see end of index.html.twig) 
            lines.push(l);
        }
    }
}

function deleteArrows(){
    for (var l of lines) {
        l.remove();
    }
    lines = new Array();
}

function hideArrows(){
    for (var l of lines) {
        l.hide('none');
    }
}

function showArrows(){
    for (var l of lines) {
        l.show();
    }
}