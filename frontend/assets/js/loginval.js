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

const usernameError = document.getElementById('username-error');
const passwordError = document.getElementById('password-error');

function validateUsername() {
  const username = document.getElementById('exampleInputUsername').value.trim();
  if (username.length === 0) {
    usernameError.innerHTML = 'Username is required.';
    return false;
  }
  usernameError.innerHTML = '<i class="fa-regular fa-circle-check"></i>';
  return true;
}

function validatePassword() {
  const password = document.getElementById('exampleInputPassword1').value;
  if (password.length === 0) {
    passwordError.innerHTML = 'Password is required.';
    return false;
  }
  passwordError.innerHTML = '<i class="fa-regular fa-circle-check"></i>';
  return true;
}

function validateForm() {
  const userValid = validateUsername();
  const passValid = validatePassword();

  if (!userValid || !passValid) {
    toastr.error("Please fill out the required fields.", "Validation Error");
    return false;
  }

  toastr.success("Login successful!", "Success");
  return true;
}
