window.validateName = function() {
  const input = document.getElementById('first_name');
  const error = document.getElementById('name-error');
  if (!input || !error) return true;
  
  var name = input.value.trim();
  if (!name) {
    error.innerHTML = 'Name is required.';
    return false;
  }
  error.innerHTML = '<i class="fa-regular fa-circle-check"></i>';
  return true;
};

window.validatelastname = function() {
  const input = document.getElementById('last_name');
  const error = document.getElementById('lastname-error');
  if (!input || !error) return true;
  
  var lastname = input.value.trim();
  if (!lastname) {
    error.innerHTML = 'Last name is required.';
    return false;
  }
  error.innerHTML = '<i class="fa-regular fa-circle-check"></i>';
  return true;
};

window.validateEmail = function() {
  const input = document.getElementById('email');
  const error = document.getElementById('email-error');
  if (!input || !error) return true;
  
  var email = input.value.trim();
  if (!email) {
    error.innerHTML = 'Email is required.';
    return false;
  }
  if (!email.match(/^[A-Za-z0-9._-]+@[A-Za-z-]+\.[a-z]{2,}$/)) {
    error.innerHTML = 'Invalid Email';
    return false;
  }
  error.innerHTML = '<i class="fa-regular fa-circle-check"></i>';
  return true;
};

window.validatePhone = function() {
  const input = document.getElementById('tel_number');
  const error = document.getElementById('telnum-error');
  if (!input || !error) return true;
  
  var phone = input.value.trim();
  if (!phone) {
    error.innerHTML = 'Telephone number is required.';
    return false;
  }
  if (!/^[0-9]{10}$/.test(phone)) {
    error.innerHTML = 'Telephone number should contain exactly 10 digits.';
    return false;
  }
  error.innerHTML = '<i class="fa-regular fa-circle-check"></i>';
  return true;
};

window.validateUsername = function() {
  const input = document.getElementById('username_reg');
  const error = document.getElementById('username-error');
  if (!input || !error) return true;
  
  var username = input.value.trim();
  if (!username) {
    error.innerHTML = 'Username is required.';
    return false;
  }
  error.innerHTML = '<i class="fa-regular fa-circle-check"></i>';
  return true;
};

window.validateDOB = function() {
  const input = document.getElementById('dob');
  const error = document.getElementById('date-error');
  if (!input || !error) return true;
  
  var val = input.value;
  if (!val) {
    error.innerHTML = '';
    return true;
  }
  var dob = new Date(val);
  var today = new Date();
  var age = today.getFullYear() - dob.getFullYear();
  var m = today.getMonth() - dob.getMonth();
  if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
  if (age < 18) {
    error.innerHTML = 'You must be at least 18 years old.';
    return false;
  }
  error.innerHTML = '';
  return true;
};

window.validateForm = function() {
  const ok =
    validateName() &
    validatelastname() &
    validateEmail() &
    validatePhone() &
    validateUsername() &
    validateDOB();
  
  const submitError = document.getElementById('submit-error');
  if (!ok) {
    if (submitError) {
      submitError.style.display = 'block';
      submitError.innerHTML = 'Please fill out the required fields.';
      setTimeout(() => (submitError.style.display = 'none'), 3000);
    }
    return false;
  }
  return true;
};