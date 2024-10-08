document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('registration-form');
  const usernameInput = document.getElementById('username');
  const passwordInput = document.getElementById('password');
  const nameInput = document.getElementById('name');
  const emailInput = document.getElementById('email');

  form.addEventListener('submit', function(event) {
    event.preventDefault();
  
    let isValid = true;
  
    // Validate username
    if (usernameInput.value.trim() === '') {
      setError(usernameInput, 'Please enter a username.');
      isValid = false;
    } else {
      setSuccess(usernameInput);
    }
  
    // Validate password with minimum length
    const minPasswordLength = 8; 
    if (passwordInput.value.trim() === '') {
      setError(passwordInput, 'Please enter a password.');
      isValid = false;
    } else if (passwordInput.value.trim().length < minPasswordLength) {
      setError(passwordInput, `Password must be at least ${minPasswordLength} characters long.`);
      isValid = false;
    } else {
      setSuccess(passwordInput);
    }

    // Validate name
    if (nameInput.value.trim() === '') {
      setError(nameInput, 'Please enter your full name.');
      isValid = false;
    } else {
      setSuccess(nameInput);
    }
  
    // Validate email
    if (emailInput.value.trim() === '') {
      setError(emailInput, 'Please enter an email address.');
      isValid = false;
    } else if (!isValidEmail(emailInput.value.trim())) {
      setError(emailInput, 'Please enter a valid email address.');
      isValid = false;
    } else {
      setSuccess(emailInput);
    }
  
    if (isValid) {
      form.submit();
    }
  });

  function setError(element, message) {
    const parent = element.parentElement;
    const error = parent.querySelector('.error-message');
    if (error) {
      error.textContent = message;
    } else {
      const errorMessage = document.createElement('div');
      errorMessage.classList.add('error-message');
      errorMessage.textContent = message;
      parent.appendChild(errorMessage);
    }
    element.classList.add('error');
  }

  function setSuccess(element) {
    const parent = element.parentElement;
    const error = parent.querySelector('.error-message');
    if (error) {
      error.textContent = '';
    }
    element.classList.remove('error');
  }

  function isValidEmail(email) {
    const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return re.test(String(email).toLowerCase());
  }
});
