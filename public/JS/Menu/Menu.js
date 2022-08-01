const mobileScreen = window.matchMedia("(max-width: 990px )");
var IS_MENU_DISPLAYED = false;

/**
 * Function call at the loading of the pages
 * Allow to apply a different color in a button in the menu
 */
//$(document).ready(function () {
    window.addEventListener('click', function(e){   
        dashboardNav = document.getElementById('dashboard-nav')
        menuButtonContainer = document.getElementById('menu-button-container')
        menuButton = document.getElementById('menu-button')
        if (!document.getElementById('dashboard-nav').contains(e.target) && !menuButtonContainer.contains(e.target) && IS_MENU_DISPLAYED == true){
            IS_MENU_DISPLAYED = false
            dashboardNav.style.animation = "hideMenu 0.7s forwards"
            menuButton.setAttribute('class', 'menu-button');
            menuButton.setAttribute('aria-expanded', 'false');
        } 
      });
window.addEventListener('DOMContentLoaded', () => {   
    var path = window.location.pathname;
    var page = path.split("/").pop();




    switch (page) {
        case 'appointments':
            document.getElementById('appointment').style.backgroundColor = '#71a39c';
            break;
        case 'ConsultationPlanning':
            document.getElementById('planning').style.backgroundColor = '#71a39c';
            break;

        case 'pathways':
            document.getElementById('pathways').style.backgroundColor = '#71a39c';
            break;

        case 'patients':
            document.getElementById('patients').style.backgroundColor = '#71a39c';
            break;

        case 'ethics':
            document.getElementById('ethics').style.backgroundColor = '#71a39c';
            break;

        case 'human-resources':
            document.getElementById('human-resources').style.backgroundColor = '#71a39c';
            break;

        case 'material-resources':
            document.getElementById('material-resources').style.backgroundColor = '#71a39c';
            break;

        case 'settings':
            document.getElementById('settings').style.backgroundColor = '#71a39c';
            break;

        case 'user':
            document.getElementById('user').style.backgroundColor = '#71a39c';
            break;
    }
});
    //});


function displayMenu(buttonThis) {
    
    buttonThis.classList.toggle('opened');
    buttonThis.setAttribute('aria-expanded', buttonThis.classList.contains('opened'));
    dashboardNav = document.getElementById('dashboard-nav');
    if(!IS_MENU_DISPLAYED) {
        IS_MENU_DISPLAYED = true;
        dashboardNav.style.animation = "displayMenu 0.7s forwards"
    } 
    else {
        dashboardNav.style.animation = "hideMenu 0.7s forwards"
        IS_MENU_DISPLAYED = false;
    }
}

