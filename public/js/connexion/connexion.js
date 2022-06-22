const loginButton = document.getElementById("submit_button");
const loginForm = document.getElementById("login-form");
document.get
var connect = new Boolean(false);




loginButton.addEventListener("click", (e) => {
    e.preventDefault();
    const username = loginForm.login.value;
    const password = loginForm.password.value;

    //parcours base de donnée
    /*
    motdepasse = SELECT MDP FROM BBD WHERE ID = username
    
    if motdepasse = password {
        connect with good status
    }
        connect with good status
    
    if select dont work {
        error
    }
        
    */

    if (username === "a" && password === "a") {
        alert("You have successfully logged in.");
        connect = true;
        location.reload();
       //window.location = Menu acceuil
    }
    
    if (connect == false){
        alert("Ca a pas marché mon grand.");
        location.reload();
    }
           
})