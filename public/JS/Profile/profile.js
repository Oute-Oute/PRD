 document.addEventListener("DOMContentLoaded", function () {
 document.getElementById("old_password").value = null;
 document.getElementById("new_password").value = null;
 document.getElementById("confirm_password").value = null;
})
/**
 * Allows to verify if the new password is the same as the confirmation password
 */
function inputGestion() {
    let newPassword1 = document.getElementById('new_password').value;
    let newPassword2 = document.getElementById('confirm_password').value;

    if (newPassword1 === newPassword2) {
        document.getElementById('MdpDifferent').style.display = 'none';
        document.getElementById('submit_button').disabled = false;

    } else {
        document.getElementById('MdpDifferent').style.display = 'flex';
        document.getElementById('submit_button').disabled = true;
    }
}