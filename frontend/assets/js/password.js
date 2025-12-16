// Only run if we're on the registration page
var passReg = document.getElementById("password_reg");
var msgReg = document.getElementById("message-pass");
var strenghtReg = document.getElementById("strenght");

if (passReg && msgReg && strenghtReg) {
  passReg.addEventListener('input', () => {
    if (passReg.value.length > 0) {
      msgReg.style.display = "block";
    } else {
      msgReg.style.display = "none";
    }
    
    if (passReg.value.length < 4) {
      strenghtReg.innerHTML = "weak";
      passReg.style.borderColor = "#ff5925";
      msgReg.style.color = "#ff5925";
    } else if (passReg.value.length >= 4 && passReg.value.length < 8) {
      strenghtReg.innerHTML = "medium";
      passReg.style.borderColor = "yellow";
      msgReg.style.color = "yellow";
    } else if (passReg.value.length >= 8) {
      strenghtReg.innerHTML = "strong";
      passReg.style.borderColor = "#26d730";
      msgReg.style.color = "#26d730";
    }
  });
}