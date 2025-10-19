var nameError = document.getElementById('name-error');
var lastnameError = document.getElementById('lastname-error');
var dateError = document.getElementById('date-error');
var emailError = document.getElementById('email-error');
var usernameError = document.getElementById('username-error');
var telnumError = document.getElementById('telnum-error');
var submitError = document.getElementById('submit-error');

function validateName() {
  var name = document.getElementById('first_name').value.trim();
  if (!name) { nameError.innerHTML = 'Name is required.'; return false; }
  nameError.innerHTML = '<i class="fa-regular fa-circle-check"></i>';
  return true;
}

function validatelastname() {
  var lastname = document.getElementById('last_name').value.trim();
  if (!lastname) { lastnameError.innerHTML = 'Last name is required.'; return false; }
  lastnameError.innerHTML = '<i class="fa-regular fa-circle-check"></i>';
  return true;
}

function validateEmail() {
  var email = document.getElementById('email').value.trim();
  if (!email) { emailError.innerHTML = 'Email is required.'; return false; }
  if (!email.match(/^[A-Za-z0-9._-]+@[A-Za-z-]+\.[a-z]{2,}$/)) {
    emailError.innerHTML = 'Invalid Email';
    return false;
  }
  emailError.innerHTML = '<i class="fa-regular fa-circle-check"></i>';
  return true;
}

function validatePhone() {
  var phone = document.getElementById('tel_number').value.trim();
  if (!phone) { telnumError.innerHTML = 'Telephone number is required.'; return false; }
  if (!/^[0-9]{10}$/.test(phone)) {
    telnumError.innerHTML = 'Telephone number should contain exactly 10 digits.';
    return false;
  }
  telnumError.innerHTML = '<i class="fa-regular fa-circle-check"></i>';
  return true;
}

function validateUsername() {
  var username = document.getElementById('username_reg').value.trim();
  if (!username) { usernameError.innerHTML = 'Username is required.'; return false; }
  usernameError.innerHTML = '<i class="fa-regular fa-circle-check"></i>';
  return true;
}

function validateDOB() {
  var input = document.getElementById('date_of_birth').value;
  if (!input) { dateError.innerHTML = ''; return true; }
  var dob = new Date(input);
  var today = new Date();
  var age = today.getFullYear() - dob.getFullYear();
  var m = today.getMonth() - dob.getMonth();
  if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
  if (age < 18) { dateError.innerHTML = 'You must be at least 18 years old.'; return false; }
  dateError.innerHTML = '';
  return true;
}

function validateForm() {
  const ok =
    validateName() &
    validatelastname() &
    validateEmail() &
    validatePhone() &
    validateUsername() &
    validateDOB();
  if (!ok) {
    submitError.style.display = 'block';
    submitError.innerHTML = 'Please fill out the required fields.';
    setTimeout(() => (submitError.style.display = 'none'), 3000);
    return false;
  }
  return true;
}
