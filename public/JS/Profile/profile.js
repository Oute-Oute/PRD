function inputGestion(e) {
    let newPassword1 = document.getElementById('new_password').value;
    let newPassword2 = document.getElementById('confirm_password').value;
    
    
    if (newPassword1 === newPassword2) {
    document.getElementById('MdpDifferent').style.display = 'none';
    document.getElementById('submit_button1').style.display = 'none';
    document.getElementById('submit_button2').style.display = 'flex';
    } else {
    document.getElementById('MdpDifferent').style.display = 'flex';
    document.getElementById('submit_button2').style.display = 'none';
    document.getElementById('submit_button1').style.display = 'flex';
    }
    
    }