var lines= new Array();

/**
 * Allows to display the info modal of a pathway
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
            if(data[0] != undefined){
                drawActivities(data);
            }
            else{
                errorGetActivities();
            }
        },
        error: function () {
            errorGetActivities();
        }
        });

    change_tab('activities');
    $('#infos-pathway-modal').modal("show");
  document.getElementById('load-path').style.visibility = "visible";
}

/**
 * Allows to display a warning message when a pathway has 0 activities
 */
function errorGetActivities(){
    var divContent = document.getElementById('divContent');
    divContent.innerHTML = "Pas d'activités créées !";
    divBr = document.getElementById('modal-br');
    divBr.innerHTML = "";   
    document.getElementById('load-path').style.visibility = "hidden";
}

/**
 * Allows to display a table of appointments linked to the pathway
 */
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

/**
 * Allows to change tab in the info modal pathway
 */
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

/**
 * Allows to draw activities of the pathway with a graph
 */
function drawActivities(data){
    var divContent = document.getElementById('divContent');
    divContent.innerHTML = ""; // reset the content

    arrayActivityByLevel = Array();
 
    /*for(i = 0; i < data.length; i++){
        for(j = 0; j < data[i].length; j++){
            arrayActivityByLevel[i].push(data[i][j]['name'], j, data[i][j]['duration']);
        }
    }*/

    // get the maximum of activities within one level, 
    // used to print some br to have enough space to draw the graph
    maxActivityByLevel = 0;
    for(i = 0; i < data.length; i++){
        if (maxActivityByLevel < data[i].length){
            maxActivityByLevel = data[i].length;
        }       
    }

    var divBr= document.getElementById('modal-br');
    divBr.innerHTML = ""; // reset the previous br to prevent infinite growth of the modal
    for(i = 0; i < 3; i++){
        var br = document.createElement("br");
        divBr.appendChild(br);
    }

    // update flex basis to separate acitivies accordingly
    const style = document.createElement('style');
    style.innerHTML = `
        .block {
            flex-basis:` + Math.round(100/data.length) + `%;
        }
        `;
    document.head.appendChild(style);

    for(i = 0; i < data.length; i++){
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
    for(i=0; i < data.length; i++){
        nbActivity = data[i].length;
        switch(nbActivity){
            // function createActivities(height, level, name, idActivity, duration){
            case 1:
                height = 100/(nbActivity*2);
                createActivities(height, i+1, data[i][0]['name'], data[i][0]['id'], data[i][0]['duration']);
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
                    createActivities(height, i+1, data[i][j]['name'], data[i][j]['id'], data[i][j]['duration']);
                }
            break;
        }
    }
    drawArrows(data);
  document.getElementById('load-path').style.visibility = "hidden";
}

/**
 * Allows to create new activities for a pathway
 */
function createActivities(height, level, name, idActivity, duration){
    var divLevel = document.getElementById('content' + level);

    var div = document.createElement('DIV');
    div.setAttribute('id', 'activity'+ idActivity);
    div.classList.add("pathway-div-activity");
    div.style.transform = 'translate(0%, -' + 100 + '%)';

    var divHeader = document.createElement('div');
    divHeader.classList.add("pathway-div-activity-header");
    divHeader.innerHTML = name;

    var p = document.createElement('p');
    p.style.fontSize = '80%';
    p.innerHTML = "durée : " + duration + "min"; 

    div.appendChild(divHeader); div.appendChild(p);

    divLevel.appendChild(div);

    document.getElementById('infos-pathway-modal').addEventListener('scroll', AnimEvent.add(function() {
        lines.forEach((l) => {
        if(l.start == div || l.end == div){
            l.position();
        }
        }); 
    }), false);

    div.addEventListener('mouseenter', (e) => {
        //hideArrows();
        lines.forEach((l) => {
            if(l.start == div || l.end == div){
                l.show('draw', {duration: 500, timing: [0.58, 0, 0.42, 1]});
            }
            else{
                l.hide('draw', {duration: 500, timing: [0.58, 0, 0.42, 1]})
            }
        }); 
    });
      
    div.addEventListener('mouseleave', (e) => {
        lines.forEach((l) => {
            l.show('draw', {duration: 1500, timing: [0.58, 0, 0.42, 1]});
        }); 
    });
}

/**
 * Allows to draw arrows in the pathway graph
 */
function drawArrows(data){
    lines = new Array();
    for(level = 0; level < data.length; level++){
        for(act = 0; act < data[level].length; act++){ // For each activity in level
            for(s = 0; s < data[level][act]['successor'].length; s++){ // For each successor of this activity
                // 
                start = document.getElementById('activity'+ data[level][act]['id']);
                end = document.getElementById('activity'+ data[level][act]['successor'][s]['idB']);
                l = new LeaderLine(start, end, {color: '#0dac2d', startSocket: 'right', endSocket: 'left'});
                // We store every line to show/hide them when we switch tabs
                // When you click outside the modal, the line array is reset (see end of index.html.twig) 
                lines.push(l);
            }
        }
    }
}

/**
 * Allows to delete arrows in the pathway graph
 */
function deleteArrows(){
    for (var l of lines) {
        l.remove();
    }
    lines = new Array();
}

/**
 * Allows to hide arrows in the pathway graph
 */
function hideArrows(){
    for (var l of lines) {
        l.hide('none');
    }
}

/**
 * Allows to show arrows in the pathway graph
 */
function showArrows(){
    for (var l of lines) {
        l.show();
    }
}