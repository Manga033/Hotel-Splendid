// toastr configuration
toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": true,
  "progressBar": true,
  "positionClass": "toast-top-right",
  "preventDuplicates": true,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
};

// Define validation functions globally
window.validateUsername = function() {
  const usernameInput = document.getElementById('exampleInputUsername');
  const usernameError = document.getElementById('username-error');
  
  if (!usernameInput || !usernameError) return true;
  
  const username = usernameInput.value.trim();
  if (username.length === 0) {
    usernameError.innerHTML = 'Username is required.';
    return false;
  }
  usernameError.innerHTML = '<i class="fa-regular fa-circle-check"></i>';
  return true;
};

window.validatePassword = function() {
  const passwordInput = document.getElementById('exampleInputPassword1');
  const passwordError = document.getElementById('password-error');
  
  if (!passwordInput || !passwordError) return true;
  
  const password = passwordInput.value;
  if (password.length === 0) {
    passwordError.innerHTML = 'Password is required.';
    return false;
  }
  passwordError.innerHTML = '<i class="fa-regular fa-circle-check"></i>';
  return true;
};

window.validateForm = function() {
  const userValid = validateUsername();
  const passValid = validatePassword();

  if (!userValid || !passValid) {
    toastr.error("Please fill out the required fields.", "Validation Error");
    return false;
  }

  return true;
};